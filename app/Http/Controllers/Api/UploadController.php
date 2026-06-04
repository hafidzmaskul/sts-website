<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            // Store in 'public/editor'
            $path = $request->file('file')->store('editor', 'public');

            // TinyMCE expects this specific JSON format: { location: "url" }
            return response()->json(['location' => url('storage/'.$path)]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
