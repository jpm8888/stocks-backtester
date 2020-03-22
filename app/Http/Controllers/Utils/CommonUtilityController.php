<?php
/**
 * User: jpm
 * Date: 2020-03-21
 * Time: 11:33
 */

namespace App\Http\Controllers\Utils;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CommonUtilityController extends Controller
{

    public function getTables(){
        $tables = DB::select('SHOW TABLES');
        $table_names = [];
        $field_name = 'Tables_in_' . Constants::getDatabaseName();
        foreach($tables as $table) {
            $table_names[] =  ['id' => $table->$field_name, 'name' => $table->$field_name];
        }
        return $table_names;
    }

    public function getTableColumnsInfo($table_name){
        $description = DB::select("DESC $table_name");
        return $description;
    }

}
