<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ImageController extends Controller
{
    protected $imagePath = 'images';

    public function index()
    {
        $images = File::allFiles(public_path($this->imagePath));

        $images = array_map(function ($file) {
            $name = $file->getFilename();

            $path = str_replace(public_path(), '', $file->getRealPath());

            $path = str_replace('\\', '/', $path);

            $path = ltrim($path, '/');

            return [
                'name' => $name,
                'path' => $path,
            ];
        }, $images);

        return view('admin.defaultImages.index', compact('images'));
    }

    public function update(Request $request, $image)
    {
        $request->validate([
            'image_file' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        $imagePath = public_path($this->imagePath . '/' . $image);

        if (!file_exists($imagePath)) {
            return back()->with('error', 'Image not found.');
        }

        $uploadedFile = $request->file('image_file');

        try {
            $uploadedFile->move(dirname($imagePath), basename($imagePath));
            return back()->with('success', 'Image updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update image: ' . $e->getMessage());
        }
    }
}
