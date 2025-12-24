<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class AdminVatController extends Controller
{
    private $path;

    public function __construct()
    {
        $this->path = resource_path('setting/vat.php');
    }

    public function index()
    {
        $vat = include $this->path;

        return view('admin.vat-setting.index', compact('vat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'  => 'required|in:percent,amount',
            'value' => 'required|numeric|min:0'
        ]);

        $content = "<?php\n\nreturn [\n".
            "    'type'  => '{$request->type}',\n".
            "    'value' => {$request->value}\n".
            "];\n";

        file_put_contents($this->path, $content);

        return back()->with('success', 'VAT Save Successfully');
    }
}
