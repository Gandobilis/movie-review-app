<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    public function uploadFile($file, $storagePath = 'images'): string
    {
        $originalFileName = $file->getClientOriginalName();
        $newFileName = Str::random() . $originalFileName;
        return $file->storeAs($storagePath, $newFileName, 'public');
    }

    public function deleteFile($path): bool
    {
        $path = 'public/' . $path;
        if (Storage::exists($path)) return Storage::delete($path);
        return false;
    }
}
