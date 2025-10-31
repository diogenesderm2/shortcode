<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Jobs\ProcessReportJob;

class ReportController extends Controller
{

    public string $status = 'pending';

   public function index(Request $request)
   {
    $status = $request->query('status', $this->status);
       return Inertia::render('Admin/Reports/Index', [
           'status' => $status,
       ]);
   }

   // Novo método: processa o relatório e despacha o Job
   public function processReport(Request $request)
   {
       ProcessReportJob::dispatch();

       // Redirect back so Inertia is satisfied
       return redirect()->route('admin.reports.index');
   }
}
