<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminLanguageController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function index(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService); 
        parent::checkAdminInPage($user);
        $languages = ['en', 'es', 'vi', 'ja', 'zh', 'fr'];
        $translations = [];

        foreach ($languages as $lang) {
            $path = resource_path("lang/{$lang}/message.php");
            if (File::exists($path)) {
                $translations[$lang] = include($path);
            }
        }
        return view('admin.languages.index', compact('translations', 'languages'));
    }

    public function update(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService); 
        parent::checkAdminInPage($user);
        $data = $request->input('translations');

        foreach ($data as $lang => $values) {
            $filePath = resource_path("lang/{$lang}/message.php");

            $content = "<?php\n\nreturn [\n";
            foreach ($values as $key => $val) {
                $val = addslashes($val);
                $content .= "    '{$key}' => '{$val}',\n";
            }
            $content .= "];\n";

            File::put($filePath, $content);
        }

        return redirect()->back()->with('success', 'Translations updated successfully!');
    }
}