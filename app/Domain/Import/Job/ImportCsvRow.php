<?php

namespace App\Domain\Import\Job;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportCsvRow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $csvLine;
    private array $csvColumnsMap = [
        "region",
        "country",
        "item_type",
        "sales_channel",
        "order_priority",
        "order_date",
        "order_id",
        "ship_date",
        "units_sold",
        "unit_price",
        "unit_cost",
        "total_revenue",
        "total_cost",
        "total_profit",
    ];

    public function __construct(array $csvLine)
    {
        $this->csvLine = $csvLine;
    }

    public function handle()
    {
        try {
            $arrayMap = [];

            foreach ($this->csvColumnsMap as $key => $value) {
                $cellValue = $this->csvLine[$key];

                if (in_array($value, ["unit_price", "unit_cost", "total_revenue", "total_cost", "total_profit"])) {
                    $cellValue = str_replace(".", "", $cellValue);
                    $cellValue = intval($cellValue);
                }

                $arrayMap[$value] = $cellValue;
            }

            $arrayMap["created_at"] = now()->format("Y-m-d H:i:s");
            $arrayMap["updated_at"] = now()->format("Y-m-d H:i:s");

            DB::table("sales")->insert($arrayMap);
        } catch (\Throwable $exception) {
            Log::info($exception->getMessage());
            Log::info($exception->getTraceAsString());
            Log::info(print_r($this->csvLine, true));
        }
    }
}
