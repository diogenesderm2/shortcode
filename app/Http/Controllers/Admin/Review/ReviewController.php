<?php

namespace App\Http\Controllers\Admin\Review;

use App\Http\Controllers\Controller;
use App\Models\GeneticResult;
use App\Models\Admin\Sample\Sample;
use App\Models\Admin\Animal\Animal;
use App\Models\Admin\Owner\Owner;
use App\Models\User;
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
        // Use join approach to avoid relationship issues
        $query = Sample::select('samples.*')
            ->join('genetic_results', 'samples.id', '=', 'genetic_results.sample_id')
            ->with('animal.owner')
            ->distinct();

        // Filter by status based on is_released field
        if ($request->has('status') && $request->status !== '') {
            switch ($request->status) {
                case 'pending':
                    $query->where('is_released', 0);
                    break;
                case 'approved':
                    $query->where('is_released', 1);
                    break;
                // For compatibility, we can add other statuses if needed
                default:
                    $query->where('is_released', 0);
            }
        } else {
            // Default to pending (not released)
            $query->where('is_released', 0);
        }

        // Filter by animal
        if ($request->has('animal_id') && $request->animal_id !== '') {
            $query->where('animal_id', $request->animal_id);
        }

        // Filter by owner
        if ($request->has('owner_id') && $request->owner_id !== '') {
            $query->whereHas('animal', function($q) use ($request) {
                $q->where('owner_id', $request->owner_id);
            });
        }

        // Search by marker name
        if ($request->has('search') && $request->search !== '') {
            $query->join('genetic_results as gr_search', 'samples.id', '=', 'gr_search.sample_id')
                  ->join('genetic_markers as gm_search', 'gr_search.marker_id', '=', 'gm_search.id')
                  ->where('gm_search.name', 'like', '%' . $request->search . '%');
        }

        // Order by id desc
        $query->orderBy('id', 'desc');

        $allSamples = $query->get();
        
        // Transform samples to match expected format
        $groupedResults = $allSamples->map(function ($sample) {
            // Determine status based on is_released field
            $sampleStatus = $sample->is_released ? 'approved' : 'pending';
            
            // Get genetic results count using direct query to avoid relationship issues
            $resultsCount = \DB::table('genetic_results')->where('sample_id', $sample->id)->count();
            
            // Get genetic results using direct query
            $results = \DB::table('genetic_results')
                ->join('genetic_markers', 'genetic_results.marker_id', '=', 'genetic_markers.id')
                ->leftJoin('users', 'genetic_results.reviewed_by', '=', 'users.id')
                ->where('genetic_results.sample_id', $sample->id)
                ->select(
                    'genetic_results.*',
                    'genetic_markers.name as marker_name',
                    'users.name as reviewer_name'
                )
                ->get();
            
            return [
                'sample_id' => $sample->id,
                'sample' => $sample,
                'results_count' => $resultsCount,
                'status' => $sampleStatus,
                'reviewed_by' => $sample->user_released ? User::find($sample->user_released) : null,
                'reviewed_at' => $sample->released_at,
                'created_at' => $sample->created_at,
                'results' => $results,
                // For bulk actions, we'll use the sample ID as reference
                'id' => $sample->id
            ];
        });

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

        // Statistics - based on samples is_released field
        $allSamplesForStats = Sample::select('samples.*')
            ->join('genetic_results', 'samples.id', '=', 'genetic_results.sample_id')
            ->distinct()
            ->get();
        
        $stats = [
            'pending' => $allSamplesForStats->where('is_released', 0)->count(),
            'under_review' => 0, // Not used with is_released logic
            'approved' => $allSamplesForStats->where('is_released', 1)->count(),
            'rejected' => 0, // Not used with is_released logic
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
     * Show all genetic results for a specific sample
     */
    public function showSample(Sample $sample)
    {
        $sample->load([
            'animal.owner'
        ]);

        // Get all genetic results for this sample
        $results = GeneticResult::where('sample_id', $sample->id)
            ->with(['marker', 'reviewer', 'sample.animal.owner'])
            ->orderBy('marker_id')
            ->get();

        // If there are results, show the first one as the main result
        $mainResult = $results->first();
        $relatedResults = $results->skip(1)->values()->toArray();

        if (!$mainResult) {
            return redirect()->route('admin.review.index')
                ->with('error', 'Nenhum resultado genético encontrado para esta amostra.');
        }

        return Inertia::render('Admin/Review/Show', [
            'result' => $mainResult,
            'relatedResults' => $relatedResults,
        ]);
    }

    /**
     * Update the review status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved',
            'review_notes' => 'nullable|string'
        ]);

        try {
            // Find the sample instead of genetic result
            $sample = Sample::findOrFail($id);
            
            // Update sample is_released field
            $sample->is_released = $request->status === 'approved' ? 1 : 0;
            $sample->user_released = $request->status === 'approved' ? auth()->id() : null;
            $sample->released_at = $request->status === 'approved' ? now() : null;
            $sample->save();

            // Update all genetic results for this sample
            GeneticResult::where('sample_id', $sample->id)
                ->update([
                    'status' => $request->status,
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                    'review_notes' => $request->review_notes,
                ]);

            $statusLabel = $request->status === 'approved' ? 'aprovada' : 'marcada como pendente';
            return back()->with('success', "Amostra {$statusLabel} com sucesso!");

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao atualizar status: ' . $e->getMessage()]);
        }
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
     * Bulk update status for multiple samples
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'status' => 'required|in:pending,approved',
            'review_notes' => 'nullable|string'
        ]);

        try {
            // Update samples instead of genetic results
            $samples = Sample::whereIn('id', $request->ids)->get();
            
            foreach ($samples as $sample) {
                $sample->is_released = $request->status === 'approved' ? 1 : 0;
                $sample->user_released = $request->status === 'approved' ? auth()->id() : null;
                $sample->released_at = $request->status === 'approved' ? now() : null;
                $sample->save();

                // Optionally update all genetic results for this sample
                if ($request->has('review_notes')) {
                    GeneticResult::where('sample_id', $sample->id)
                        ->update([
                            'review_notes' => $request->review_notes,
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                        ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' amostras atualizadas com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update status for multiple results
     */
    /**
     * Bulk update status for samples (approve/reject all results in selected samples)
     */
    public function bulkUpdateSamples(Request $request)
    {
        $request->validate([
            'sample_ids' => 'required|array|min:1',
            'sample_ids.*' => 'exists:samples,id',
            'status' => 'required|in:approved,rejected',
            'review_notes' => 'nullable|string|max:1000',
        ]);

        $samples = Sample::whereIn('id', $request->sample_ids)->get();
        $totalResults = 0;

        DB::transaction(function () use ($request, $samples, &$totalResults) {
            foreach ($samples as $sample) {
                // Update sample status
                $sample->update([
                    'is_released' => $request->status === 'approved' ? 1 : 0,
                    'user_released' => $request->status === 'approved' ? auth()->id() : null,
                    'released_at' => $request->status === 'approved' ? now() : null,
                ]);

                // Update all genetic results for this sample
                $results = GeneticResult::where('sample_id', $sample->id)->get();
                foreach ($results as $result) {
                    $result->update([
                        'status' => $request->status,
                        'reviewed_by' => auth()->id(),
                        'reviewed_at' => now(),
                        'review_notes' => $request->review_notes,
                    ]);
                    $totalResults++;
                }
            }
        });

        $statusLabel = $request->status === 'approved' ? 'aprovados' : 'rejeitados';
        return back()->with('success', "{$totalResults} resultados {$statusLabel} com sucesso!");
    }

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