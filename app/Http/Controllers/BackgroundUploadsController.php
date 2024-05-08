<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BackgroundUploadsController extends Controller
{
    public function process(Request $request)
    {
        $files = [];
        
        foreach($request->allFiles() as $field => $file) 
        {
            if($file->getMimeType() == 'application/octet-stream')
                $files[$field] = $file->storePubliclyAs('', Str::replaceLast('.bin', '.'.$file->getClientOriginalExtension(), $file->hashName()), 'temp');
            else
                $files[$field] = $file->storePublicly(options: 'temp');
        }
        
        return compact('files');
    }

    public function revert(Request $request)
    {
        foreach($request->json('files') as  $file)
        {
            Storage::disk('temp')->delete($file);
        }
    }
}
