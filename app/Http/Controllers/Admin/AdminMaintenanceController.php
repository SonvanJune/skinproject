<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminMaintenanceController extends Controller
{
    public function index()
    {
        $config = include resource_path('setting/maintenance.php');
        $status = $config['status'] ?? 'off';

        return view('admin.maintenance.index', compact('status'));
    }

    public function update(Request $request)
    {
        $newStatus = $request->input('status') === 'on' ? 'on' : 'off';

        $filePath = resource_path('setting/maintenance.php');
        $content = "<?php\n\nreturn [\n    'status' => '$newStatus',\n];\n";

        file_put_contents($filePath, $content);
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate(resource_path($filePath), true);
        }

        return redirect()->back()->with('success', 'Updates status succesfully!');
    }
}
