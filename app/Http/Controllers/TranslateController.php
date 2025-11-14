<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TranslateController extends Controller
{
    public function translate(Request $request)
    {
        $texts = $request->input('texts');

        if (!$texts) {
            return response()->json(['error' => 'No texts provided'], 400);
        }

        $translatedTexts = array_map(function ($text) {
            return $this->dichVanBan($text);
        }, $texts);

        return response()->json(['translatedTexts' => $translatedTexts]);
    }

    function dichVanBan($vanBan)
    {
        try {
            $typeTrans = app()->getLocale();
            $tl = $typeTrans;
            $sl = 'en';

            $url = "https://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl=$sl&tl=$tl&hl=hl&q=" . urlencode($vanBan);

            if (!$typeTrans) {
                $url .= '&dt=t';
            }

            $response = file_get_contents($url);
            $data = json_decode($response, true);

            if (isset($data[0][0][0])) {
                $ketQuaDich = $data[0][0][0];
                return $ketQuaDich;
            } else {
                throw new Exception('Lỗi khi dịch văn bản.');
            }
        } catch (Exception $e) {
            error_log('Lỗi: ' . $e->getMessage());
            return null;
        }
    }
}
