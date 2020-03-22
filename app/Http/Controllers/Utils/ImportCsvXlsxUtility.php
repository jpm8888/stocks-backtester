<?php
/**
 * User: jpm
 * Date: 2020-03-05
 * Time: 18:06
 */

namespace App\Http\Controllers\Utils;
use App\Http\ExcelModels\DynamicExcelModel;
use App\ModelTempFile;
use App\Rules\ExcelRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Excel;

class ImportCsvXlsxUtility extends CommonUtilityController {

    use UploadUtils;
    public function index(){
        $data = [
            'div_id' => 'div_import_csv_xlsx',
        ];
        return view('common.react_empty', $data);
    }


    public function getMap($file_id){
        try{
            $temp = ModelTempFile::where('id', $file_id)->first();
            $excel = (new DynamicExcelModel(null, null))->toArray('public/' . $temp->path);
            $keys = (array_keys($excel[0][0]));
            $output = [];
            foreach ($keys as $k){
                $output [] = ['id' => $k, 'name' => $k];
            }
            return response()->json([
                'status' => 1,
                'csvColumnNames' => $output
            ]);

        }catch (\Exception $e){
            return response()->json([
                'status' => 0,
                'msg' => $e->getMessage(),
            ]);
        }


    }


    public function upload_file(Request $request){
        if ($request->hasFile("file")) {
            $validator = Validator::make(Input::all(), ['file' => ['required', new ExcelRule($request->file('file'))]]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'msg' => $validator->getMessageBag()->first()
                ]);
            }

            $file = $request->file('file');
            $tempFile = new ModelTempFile();
            $tempFile->path = $this->save_file($file);
            $tempFile->added_by = Auth::id();
            $tempFile->save();
            return response()->json([
                'status' => 1,
                'msg' => 'file uploaded successfully : ' . $tempFile->path,
                'file' => $tempFile,
            ]);
        }
    }

    public function import(Request $request){

        try{
            $tableName = $request->input('tableName');
            $columnMap = $request->input('columnMap');
            $file_id = $request->input('fileId');
            $hasTimeStamp = $request->input('hasTimeStamp');

            $file = ModelTempFile::where('id' , $file_id)->first();

            $extensions = explode('.', $file->path);

            $ext = (count($extensions) > 1) ? strtolower($extensions[1]) : 'csv';

            $type = Excel::CSV;
            switch($ext){
                case 'csv':
                    $type = Excel::CSV;
                    break;
                case 'xls':
                    $type = Excel::XLS;
                    break;
                case 'xlsx':
                    $type = Excel::XLSX;
                    break;
                case 'tsv':
                    $type = Excel::TSV;
                    break;
            }

            $excelModel = new DynamicExcelModel($tableName, $columnMap, $hasTimeStamp, $type);
            ($excelModel)->import("public/$file->path", null, $type);

            return response()->json([
                'status' => 1,
                'msg' => 'success',
                'imported_records' => $excelModel->counter,
                'errors' => $excelModel->errors,
            ]);

        }catch (\Exception $e){
            return response()->json([
                'status' => 0,
                'msg' => $e->getMessage(),
            ]);
        }
    }
}
