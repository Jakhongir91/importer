<?php

namespace App\Domain\Import\Job;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        $fileStream = fopen($this->filePath, "r");

        $skipFirstRow = true;
        while (($line = fgetcsv($fileStream)) !== false) {
            if ($skipFirstRow) {
                $skipFirstRow = false;
                continue;
            }

            ImportCsvRow::dispatch($line)->onQueue("import");
        }
    }
}
