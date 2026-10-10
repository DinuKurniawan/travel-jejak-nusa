<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'folder' => ['sometimes', 'string', 'in:packages,articles,gallery,team'],
        ]);

        $path = $data['image']->store($data['folder'] ?? 'packages', 'public');

        return response()->json(['url' => '/storage/'.$path], 201);
    }
}
