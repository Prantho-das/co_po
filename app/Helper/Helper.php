<?php
namespace App\Helper;

use Illuminate\Support\Facades\DB;

class Helper{

    public static function uploadCsv(String $file, String $table){
        dump($file);
        dd($table);
        $file = fopen($file, "r");
        while (!feof($file)) {
            $item = fgetcsv($file);
            if ($item) {
                DB::table($table)->insert([
                    'name' => $item[0],
                    's_name' => $item[1],
                    'f_group' => $item[2],
                    's_group' => $item[3],
                ]);
            }
        }
        fclose($file);
    }
}
