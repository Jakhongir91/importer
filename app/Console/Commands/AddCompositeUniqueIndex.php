<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddCompositeUniqueIndex extends Command
{
    protected $signature = "incomes:add-unique-index";
    protected $description = "Add unique composite index";

    public function handle()
    {
        $result = DB::select("select inps, income, count(*)
                from incomes
                group by inps, income
                HAVING count(*) > 1;");

        if (count($result) > 0) {
            $this->info("Duplicates in incomes table should be deleted first.");
            return;
        }

        DB::statement("create unique index date_inps_income_unique
                on incomes (extract(month from date), inps, income);");

        $this->info("Successfully added composite unique index.");
    }
}
