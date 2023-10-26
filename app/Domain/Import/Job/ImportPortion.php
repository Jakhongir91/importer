<?php

namespace App\Domain\Import\Job;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportPortion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $columnNames = [
        "inps" => 1,
        "income" => 2,
    ];
    private array $portion;
    private string $errorLogFilePath;

    public function __construct(array $portion)
    {
        $this->errorLogFilePath = base_path() . "/dump/import-error-log.csv";
        $this->portion = $portion;
    }

    public function handle()
    {
        $incorrectDataLogFile = fopen($this->errorLogFilePath, "a+");

        try {
            $rowsToInsert = [];

            foreach ($this->portion as $csvRowData) {
                $data = [];
                foreach ($this->columnNames as $columnName => $index) {
                    $cellValue = $csvRowData[$index];

                    if (in_array($columnName, ["income"])) {
                        $cellValue = intval($cellValue);
                    }

                    if (in_array($columnName, ["inps", "income"]) && empty($cellValue)) {
                        fputcsv($incorrectDataLogFile, $csvRowData);
                        break;
                    }

                    if ($columnName === "inps" && strlen(strval($cellValue)) !== 14) {
                        fputcsv($incorrectDataLogFile, $csvRowData);
                        break;
                    }

                    $data[$columnName] = $cellValue;
                }

                if (! empty($data)) {
                    $data["created_at"] = now()->format("Y-m-d H:i:s");
                    $data["updated_at"] = now()->format("Y-m-d H:i:s");
                    $data["date"] = now()->format("Y-m-d");
                    $rowsToInsert[] = $data;
                }
            }

            DB::table("incomes")->insertOrIgnore($rowsToInsert);
        } catch (\Throwable $exception) {
            Log::info($exception->getMessage());
            Log::info($exception->getTraceAsString());
            Log::info(print_r($this->portion, true));
        }
    }
}
