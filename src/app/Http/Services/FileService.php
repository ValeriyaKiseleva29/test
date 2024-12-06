<?php

namespace App\Http\Services;
use Illuminate\Http\Request;

class FileService
{
 public function storeFile(Request $request, $model)
 {

     $uploadedFile = $request->file('file');
     $path = $uploadedFile->store('projects', 'public');

     $model->files()->create([
         'name' => $uploadedFile->getClientOriginalName(),
         'path' => $path,
         'size' => $uploadedFile->getSize(),
         'type' => $uploadedFile->getClientMimeType(),
     ]);
 }


}
