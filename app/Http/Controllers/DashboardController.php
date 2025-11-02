<?php

namespace App\Http\Controllers;

use App\Models\Admin\Animal\Animal;
use App\Models\Admin\Owner\Owner;
use App\Models\Admin\Sample\Sample;
use App\Models\GeneticResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Estatísticas gerais
        $totalAnimals = Animal::count();
        $totalOwners = Owner::count();
        $totalSamples = Sample::count();
        $totalGeneticResults = GeneticResult::count();
        $totalUsers = User::count();

        // Cadastros por mês (últimos 12 meses)
        $monthlyAnimals = Animal::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', Carbon::now()->subMonths(12))
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get()
        ->map(function ($item) {
            return [
                'period' => Carbon::create($item->year, $item->month)->format('M Y'),
                'count' => $item->count
            ];
        });

        $monthlySamples = Sample::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', Carbon::now()->subMonths(12))
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get()
        ->map(function ($item) {
            return [
                'period' => Carbon::create($item->year, $item->month)->format('M Y'),
                'count' => $item->count
            ];
        });

        // Resultados genéticos por mês
        $monthlyGeneticResults = GeneticResult::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', Carbon::now()->subMonths(12))
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get()
        ->map(function ($item) {
            return [
                'period' => Carbon::create($item->year, $item->month)->format('M Y'),
                'count' => $item->count
            ];
        });

        // Cadastros por usuário (top 10) - baseado em owners
        $userRegistrations = User::with('owners')
            ->withCount('owners')
            ->orderBy('owners_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                // Contar animais através dos owners
                $animalsCount = 0;
                $samplesCount = 0;
                
                if ($user->owners->count() > 0) {
                    $ownerIds = $user->owners->pluck('id');
                    $animalsCount = Animal::whereIn('owner_id', $ownerIds)->count();
                    $samplesCount = Sample::whereIn('owner_id', $ownerIds)->count();
                }
                
                return [
                    'name' => $user->name,
                    'owners' => $user->owners_count ?? 0,
                    'animals' => $animalsCount,
                    'samples' => $samplesCount,
                    'total' => ($user->owners_count ?? 0) + $animalsCount + $samplesCount
                ];
            });

        // Distribuição por tipo de animal
        $animalTypes = Animal::join('animal_types', 'animals.animal_type', '=', 'animal_types.id')
            ->select('animal_types.name', DB::raw('COUNT(*) as count'))
            ->groupBy('animal_types.id', 'animal_types.name')
            ->orderBy('count', 'desc')
            ->get();

        // Status das amostras (baseado em is_released)
        $sampleStatus = Sample::select(
            DB::raw('CASE 
                WHEN is_released = 1 THEN "Liberado"
                WHEN is_released = 0 THEN "Pendente"
                ELSE "Não definido"
            END as status'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('is_released')
        ->get()
        ->map(function ($item) {
            return [
                'status' => $item->status,
                'count' => $item->count
            ];
        });

        // Atividade recente (últimos 7 dias)
        $recentActivity = [
            'animals' => Animal::where('created_at', '>=', Carbon::now()->subDays(7))->count(),
            'samples' => Sample::where('created_at', '>=', Carbon::now()->subDays(7))->count(),
            'owners' => Owner::where('created_at', '>=', Carbon::now()->subDays(7))->count(),
            'genetic_results' => GeneticResult::where('created_at', '>=', Carbon::now()->subDays(7))->count(),
        ];

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalAnimals' => $totalAnimals,
                'totalOwners' => $totalOwners,
                'totalSamples' => $totalSamples,
                'totalGeneticResults' => $totalGeneticResults,
                'totalUsers' => $totalUsers,
            ],
            'charts' => [
                'monthlyAnimals' => $monthlyAnimals,
                'monthlySamples' => $monthlySamples,
                'monthlyGeneticResults' => $monthlyGeneticResults,
                'userRegistrations' => $userRegistrations,
                'animalTypes' => $animalTypes,
                'sampleStatus' => $sampleStatus,
            ],
            'recentActivity' => $recentActivity,
        ]);
    }
}