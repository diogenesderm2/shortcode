<?php

namespace App\Jobs;

use App\Events\ReportProcessed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        // Simula processamento por 5 segundos
        sleep(5);

        // Emite o evento; o Echo/Reverb captura no frontend
        broadcast(new ReportProcessed());
    }
}