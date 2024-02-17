<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\File;

class FileDownloadController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->file(public_path() . Str::after($request->query('path'), env('APP_URL')));
    }
}
