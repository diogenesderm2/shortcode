<?php

namespace App\Http\Controllers\Admin\Review;

use App\Http\Controllers\Controller;
use App\Models\GeneticResult;
use App\Models\Admin\Sample\Sample;
use App\Models\Admin\Animal\Animal;
use App\Models\Admin\Owner\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReviewController extends Controller
{
    /**
     * Display a listing of results for review
     */
    public function index(Request $request)
    {
        $query = GeneticResult::with([
            'sample.animal.owner',
            'marker',
            'reviewer'
        ]);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        } else {
            // Default to pending and under review
            $query->whereIn('status', [GeneticResult::STATUS_PENDING, GeneticResult::STATUS_UNDER_REVIEW]);
        }

        // Filter by animal
        if ($request->has('animal_id') && $request->animal_id !== '') {
            $sampleIds = Sample::where('animal_id', $request->animal_id)->pluck('id');
            $query->whereIn('sample_id', $sampleIds);
        }

        // Filter by owner
        if ($request->has('owner_id') && $request->owner_id !== '') {
            $sampleIds = Sample::whereHas('animal', function($q) use ($request) {
                $q->where('owner_id', $request->owner_id);
            })->pluck('id');
            $query->whereIn('sample_id', $sampleIds);
        }

        // Search by marker name
        if ($request->has('search') && $request->search !== '') {
            $query->whereHas('marker', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Order by sample_id and created_at desc
        $query->orderBy('sample_id', 'desc')->orderBy('created_at', 'desc');

        $allResults = $query->get();
        
        // Group results by sample
        $groupedResults = $allResults->groupBy('sample_id')->map(function ($results, $sampleId) {
            $firstResult = $results->first();
            $sample = $firstResult->sample;
            
            // Determine overall sample status based on individual result statuses
            $statuses = $results->pluck('status')->unique();
            $sampleStatus = $this->determineSampleStatus($statuses);
            
            return [
                'sample_id' => $sampleId,
                'sample' => $sample,
                'results_count' => $results->count(),
                'status' => $sampleStatus,
                'reviewed_by' => $firstResult->reviewed_by ? $firstResult->reviewer : null,
                'reviewed_at' => $firstResult->reviewed_at,
                'created_at' => $firstResult->created_at,
                'results' => $results,
                // For bulk actions, we'll use the first result's ID as reference
                'id' => $firstResult->id
            ];
        })->values();

        // Manual pagination
        $perPage = 20;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedResults = $groupedResults->slice($offset, $perPage);
        
        $results = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedResults,
            $groupedResults->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'pageName' => 'page',
            ]
        );
        $results->withQueryString();

        // Get filter options
        $owners = Owner::select('id', 'name')->orderBy('name')->get();
        $animals = Animal::select('id', 'name', 'owner_id')->with('owner:id,name')->orderBy('name')->get();

        // Statistics - now based on samples, not individual results
        $allSamples = GeneticResult::with('sample')
            ->select('sample_id')
            ->distinct()
            ->get()
            ->map(function ($result) {
                $sampleResults = GeneticResult::where('sample_id', $result->sample_id)->get();
                $statuses = $sampleResults->pluck('status')->unique();
                return $this->determineSampleStatus($statuses);
            });

        $stats = [
            'pending' => $allSamples->filter(fn($status) => $status === 'pending')->count(),
            'under_review' => $allSamples->filter(fn($status) => $status === 'under_review')->count(),
            'approved' => $allSamples->filter(fn($status) => $status === 'approved')->count(),
            'rejected' => $allSamples->filter(fn($status) => $status === 'rejected')->count(),
        ];

        return Inertia::render('Admin/Review/Index', [
            'results' => $results,
            'filters' => $request->only(['status', 'animal_id', 'owner_id', 'search']),
            'owners' => $owners,
            'animals' => $animals,
            'stats' => $stats,
        ]);
    }

    /**
     * Determine sample status based on individual result statuses
     */
    private function determineSampleStatus($statuses)
    {
        $statusArray = $statuses->toArray();
        
        // If any result is rejected, sample is rejected
        if (in_array('rejected', $statusArray)) {
            return 'rejected';
        }
        
        // If all results are approved, sample is approved
        if (count($statusArray) === 1 && $statusArray[0] === 'approved') {
            return 'approved';
        }
        
        // If any result is under review, sample is under review
        if (in_array('under_review', $statusArray)) {
            return 'under_review';
        }
        
        // If all results are approved (multiple statuses but all approved)
        if (count(array_unique($statusArray)) === 1 && $statusArray[0] === 'approved') {
            return 'approved';
        }
        
        // Default to pending
        return 'pending';
    }

    /**
     * Show the form for reviewing a specific result
     */
    public function show(GeneticResult $result)
    {
        $result->load([
            'sample.animal.owner',
            'marker',
            'reviewer'
        ]);

        // Get related results for the same sample
        $relatedResults = GeneticResult::where('sample_id', $result->sample_id)
            ->where('id', '!=', $result->id)
            ->with(['marker', 'reviewer'])
            ->orderBy('marker_id')
            ->get();

        return Inertia::render('Admin/Review/Show', [
            'result' => $result,
            'relatedResults' => $relatedResults,
        ]);
    }

    /**
     * Update the review status
     */
    public function updateStatus(Request $request, GeneticResult $result)
    {
        $request->validate([
            'status' => 'required|in:under_review,approved,rejected',
            'review_notes' => 'nullable|string|max:1000',
            'quality_metrics' => 'nullable|array',
        ]);

        if (!$result->canBeReviewed() && $request->status !== 'under_review') {
            return back()->withErrors(['status' => 'Este resultado não pode ser revisado no status atual.']);
        }

        DB::transaction(function () use ($request, $result) {
            $result->update([
                'status' => $request->status,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'review_notes' => $request->review_notes,
                'quality_metrics' => $request->quality_metrics,
            ]);
        });

        $statusLabel = match($request->status) {
            'under_review' => 'colocado em revisão',
            'approved' => 'aprovado',
            'rejected' => 'rejeitado',
        };

        return back()->with('success', "Resultado {$statusLabel} com sucesso!");
    }

    /**
     * Get all results for a specific sample
     */
    public function getSampleResults(Sample $sample)
    {
        $results = GeneticResult::where('sample_id', $sample->id)
            ->with(['marker', 'reviewer'])
            ->orderBy('marker_id')
            ->get();

        return response()->json([
            'sample' => $sample->load(['animal.owner']),
            'results' => $results,
        ]);
    }

    /**
     * Bulk update status for multiple results
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'result_ids' => 'required|array|min:1',
            'result_ids.*' => 'exists:genetic_results,id',
            'status' => 'required|in:under_review,approved,rejected',
            'review_notes' => 'nullable|string|max:1000',
        ]);

        $results = GeneticResult::whereIn('id', $request->result_ids)->get();
        
        // Check if all results can be reviewed
        foreach ($results as $result) {
            if (!$result->canBeReviewed() && $request->status !== 'under_review') {
                return back()->withErrors(['status' => 'Alguns resultados não podem ser revisados no status atual.']);
            }
        }

        DB::transaction(function () use ($request, $results) {
            foreach ($results as $result) {
                $result->update([
                    'status' => $request->status,
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                    'review_notes' => $request->review_notes,
                ]);
            }
        });

        $count = count($request->result_ids);
        $statusLabel = match($request->status) {
            'under_review' => 'colocados em revisão',
            'approved' => 'aprovados',
            'rejected' => 'rejeitados',
        };

        return back()->with('success', "{$count} resultados {$statusLabel} com sucesso!");
    }

    /**
     * Get results by sample for quick review
     */
    public function getBySample(Sample $sample)
    {
        $results = GeneticResult::where('sample_id', $sample->id)
            ->with(['marker', 'reviewer'])
            ->orderBy('marker_id')
            ->get();

        $sample->load(['animal.owner']);

        return response()->json([
            'success' => true,
            'sample' => $sample,
            'results' => $results,
        ]);
    }
}