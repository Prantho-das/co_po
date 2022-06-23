<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;

class Helper
{

    public static function uploadCsv(String $file, String $table, array $columns)
    {
        $file = fopen($file, "r");
        while ($item = fgetcsv($file)) {
            if ($item) {
                DB::table($table)->insert([
                    $columns[0] => $item[0],
                    $columns[1] => $item[1],
                    $columns[2] => $item[2],
                    $columns[3] => $item[3],
                ]);
            }
        }
        fclose($file);
    }
}
