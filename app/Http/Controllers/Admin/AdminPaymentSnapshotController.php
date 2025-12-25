<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AdminPaymentSnapshotController extends Controller
{
    private $path;

    public function __construct()
    {
        $this->path = public_path('payment-snapshot');
    }

    public function index()
    {
        $files = File::exists($this->path)
            ? File::files($this->path)
            : [];

        return view('admin.payment-snapshot.index', compact('files'));
    }

    public function view($filename)
    {
        $filePath = $this->path . '/' . $filename;

        if (!File::exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf'
        ]);
    }

    public function download($filename)
    {
        $filePath = $this->path . '/' . $filename;

        if (!File::exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath);
    }
}
