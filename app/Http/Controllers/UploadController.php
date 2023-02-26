<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function uploadSingle(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            if ($file->isValid()) {
                $filePath = $file->store('/', ['disk' => 's3', 'visibility' => 'public']);
                $fileName = basename($filePath);

                return response()->json(['message' => 'files uploaded.']);
            }
        }
        return response()->json(['message' => 'Unable to upload file.']);
    }

    public function uploadMultiple(Request $request)
    {
        if ($request->hasFile('files')) {
            $files = $request->file('files');

            foreach ($files as $key => $file) {
                if($file->isValid()) {
                    $filePath = $file->store('/', ['disk' => 's3', 'visibility' => 'public']);
                    $fileName = basename($filePath);
                }
            }
            return response()->json(['message' => 'files uploaded.']);
        }
        return response()->json(['message' => 'Unable to upload files.']);
    }

    public function uploadSingleCustom(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            if ($file->isValid()) {
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = $originalName . '-' . uniqid() . '.' . $extension;
                Storage::disk('s3')->put($fileName, file_get_contents($file), 'public');

                return response()->json(['message' => 'file uploaded.']);
            }
        }
        return response()->json(['message' => 'Unable to upload file.']);
    }

    public function uploadMultipleCustom(Request $request)
    {
        if ($request->hasFile('files')) {
            $files = $request->file('files');

            foreach ($files as $key => $file) {
                if ($file->isValid()) {
                    $extension = $file->getClientOriginalExtension();
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $fileName = $originalName . '-' . uniqid() . '.' . $extension;
                    Storage::disk('s3')->put($fileName, file_get_contents($file), 'public');
                }
            }
            return response()->json(['message' => 'files uploaded.']);
        }
        return response()->json(['message' => 'Unable to upload files.']);
    }
}
