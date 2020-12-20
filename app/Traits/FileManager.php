<?php

namespace App\FileManager;

use Illuminate\Http\Request;

trait FileManager
{
    /**
     * This method is used to store files inside the project and return the name
     *
     * @param Request $request
     * @param $key
     * @param $folder_name
     * @return string
     */
    protected function storeFile(Request $request, $key, $folder_name)
    {
        if($file = $request->file($key)){
            $name=time().".".$file->getClientOriginalExtension();
            $directory = (config('constants.product_image_directory')."/$folder_name");
            $file->move(public_path($directory), $name);
            return $directory."/".$name;
        }

        return false;
    }

    protected function deleteFile($file_path)
    {
        if (file_exists($file_path)) unlink($file_path);
    }
}
