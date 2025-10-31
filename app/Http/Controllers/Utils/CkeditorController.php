<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Exception;


class CkeditorController extends Controller

{
    /**
     * Write code on Method
     *
     * @return response()
     */

    public function index(): View
    {
        return view('ckeditor');
    }

    public function upload(Request $request)
    {
        try {
            if ($request->hasFile('upload')) {
                // Get original file name and construct new file name
                $originName = $request->file('upload')->getClientOriginalName();
                $fileName = pathinfo($originName, PATHINFO_FILENAME);
                $extension = $request->file('upload')->getClientOriginalExtension();
                $fileName = $fileName . '_' . time() . '.' . $extension;

                // Move the uploaded file to the public directory
                $request->file('upload')->move(public_path('ckeditor/media'), $fileName);

                // Generate file URL
                $url = asset('ckeditor/media/' . $fileName);

                // Return response that CKEditor expects
                return response()->json([
                    'uploaded' => true,
                    'fileName' => $fileName,
                    'url' => $url
                ]);
            }

            // Handle the case where no file was uploaded
            return response()->json([
                'uploaded' => false,
                'error' => ['message' => 'No file was uploaded.']
            ]);
        } catch (Exception $e) {
            // Handle exception and return error message
            return response()->json([
                'uploaded' => false,
                'error' => ['message' => $e->getMessage()]
            ]);
        }
    }
}
