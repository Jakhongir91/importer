<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AddCompositeUniqueIndex extends Command
{
    protected $description = "Add unique composite index";

    public function handle()
    {
        $result = DB::select("select inps, income, extract(year from date), extract(month from date), count(*)
                from incomes
                group by inps, income, extract(year from date), extract(month from date)
                HAVING count(*) > 1;");

        if (count($result) > 0) {
            $this->info(print_r($result, true));
            $this->info("Duplicates in incomes table should be deleted first.");
            return;
        }

        DB::statement("create unique index date_inps_income_unique
                on incomes (extract(year from date), extract(month from date), inps, income);");

        $this->info("Successfully added composite unique index.");
    }
}
