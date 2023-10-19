<?php

namespace App\Console\Commands;

use App\Domain\Import\Job\ImportCsv as ImportCsvJob;
use Illuminate\Console\Command;

class ImportCsv extends Command
{
    protected $signature = 'import:csv';
    protected $description = 'Imports large CSV file.';

    public function handle()
    {
        $filePath = __DIR__ . "/../../../dump/large-1mln-rows.csv";
        ImportCsvJob::dispatch($filePath)->onQueue("import");
    }
}
