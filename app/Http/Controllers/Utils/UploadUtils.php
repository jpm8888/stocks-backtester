<?php
/**
 * User: jpm
 * Date: 2020-02-25
 * Time: 11:23
 */

namespace App\Http\Controllers\Utils;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait UploadUtils
{
    public function getImageRulesForValidation($isMultiple = false) {
        $input_name = 'file';
        if ($isMultiple) {
            $input_name = 'file.*';
        }

        $rules = [
            $input_name => 'image|max:500',
        ];
        return $rules;
    }


    /*
     * mimes : 'jpg,jpgeg,png,bmp' do not add spaces in the string.
//     * https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types
//     * maxFileSize : in kb
//     * isMultiple  : multiple files in one go.
//     * */
//    public function getExcelRulesForValidation($is_required, $maxFileSize, $isMultiple = false) {
//        $input_name = ($isMultiple) ? 'file.*' : 'file';
//        $required_text = ($is_required) ? 'required|' : '';
//
//        $rules = [
//            $input_name => $required_text . "mimes:$mimes" . '|' . "max:$maxFileSize",
//        ];
//        return $rules;
//    }

    public function save_file($file) {
        $folder_name = date('Y-m-d');
        $base_path = Constants::getProdServerDir() . '/' . $folder_name;
        if (!Storage::exists($base_path)) {
            Storage::makeDirectory($base_path);
        }

        $file_name = $this->getRandomFileName() . $file->getClientOriginalExtension();
        Storage::putFileAs($base_path, $file, $file_name);
        $public_file_path = 'uploads/' . $folder_name . '/' . $file_name;
        return $public_file_path;
    }

    public function deleteImageFromStorage($image_path){
        if ($image_path) Storage::delete('public/' . $image_path);
    }

    private function getRandomFileName() {
        return (Str::random(10)) . ".";
    }

    private function getRandomNumber($min, $max) {
        return rand($min, $max);
    }
}
