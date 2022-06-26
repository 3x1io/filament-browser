<?php

namespace io3x1\FilamentBrowser\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class BrowserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('folder_path')) {
            $request->validate([
                "folder_path" => "required",
                "folder_name" => "required",
                "type" => "required",
            ]);

            $root = $request->get('folder_path');
            $name = $request->get('folder_name');
            $type = $request->get('type');
        } else if ($request->has('file_path')) {
            $name = $request->get('file_name');
            $setFilePath = $request->get('file_path');
            $root = str_replace(DIRECTORY_SEPARATOR . $name, '', $request->get('file_path'));
        } else {
            $root = base_path();
            $name = base_path();
            $type = "home";
        }



        if ($request->has('file_path')) {
            $getFile = File::get($setFilePath);

            $folders =  File::directories($root);
            $files =  File::files($root);
            $foldersArray = [];
            $filesArray = [];

            foreach ($folders as $folder) {
                array_push($foldersArray, [
                    "path" => $folder,
                    "name" => str_replace($root . DIRECTORY_SEPARATOR, '', $folder),
                ]);
            }

            foreach ($files as $file) {
                array_push($filesArray, [
                    "path" => $file->getRealPath(),
                    "name" => str_replace($root . DIRECTORY_SEPARATOR, '', $file),
                ]);
            }



            $exploadName = explode(DIRECTORY_SEPARATOR, $root);
            $count = count($exploadName);
            $setName = $exploadName[$count - 1];

            $ex = File::extension($setFilePath);

            if ($ex === 'webp' || $ex === 'jpg' || $ex === 'png' || $ex === 'svg' || $ex === 'jpeg' || $ex === 'ico' ||  $ex === 'gif' || $ex === 'tif') {
                $imagBase64 = base64_encode($getFile);
                $getFile = $imagBase64;
            }

            return response()->json([
                "folders" => $foldersArray,
                "files" => $filesArray,
                "back_path" => $root,
                "back_name" => $setName,
                "current_path" => $root,
                "file" => $getFile,
                "ex" => $ex,
                "path" => $setFilePath
            ], 200);
        } elseif ($request->has('content')) {
            $checkIfFileEx = File::exists($request->get('path'));
            if ($checkIfFileEx) {
                File::put($request->get('path'), $request->get('content'));

                return response()->json([
                    "success" => true
                ]);
            }
        } else {
            $folders =  File::directories($root);
            $files =  File::files($root);
            $foldersArray = [];
            $filesArray = [];

            foreach ($folders as $folder) {
                array_push($foldersArray, [
                    "path" => $folder,
                    "name" => str_replace($root . DIRECTORY_SEPARATOR, '', $folder),
                ]);
            }

            foreach ($files as $file) {
                $ex = File::extension($file);
                array_push($filesArray, [
                    "path" => $file->getRealPath(),
                    "name" => str_replace($root . DIRECTORY_SEPARATOR, '', $file),
                    "ex" => $ex
                ]);
            }

            if ($root == base_path()) {
                array_push($filesArray, [
                    "path" => base_path('.env'),
                    "name" => ".env",
                ]);
            }

            $exploadName = explode(DIRECTORY_SEPARATOR, $root);
            $count = count($exploadName);
            $setName = $exploadName[$count - 2];



            return response()->json([
                "folders" => $foldersArray,
                "files" => $filesArray,
                "back_path" => str_replace(DIRECTORY_SEPARATOR . $name, '', $root),
                "back_name" => $setName,
                "current_path" => $root,
            ], 200);
        }
    }
}
