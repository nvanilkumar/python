<?php

namespace App\Http\Controllers;

class DocumentationController extends Controller
{
    public function getSwagger()
    {
        $path = base_path() . '/resources/';
        $file = 'swagger.json';

        $fullPath = $path . $file;

        $contents = null;

        if (file_exists($fullPath)) {
            $contents = json_decode(file_get_contents($fullPath));
        } else {
            throw new \Exception('File \'' . $fullPath . '\' not found');
        }

        return response()->json($contents);
    }
}