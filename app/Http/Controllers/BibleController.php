<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Http\Request;

class BibleController extends Controller
{
    public function translations()
    {
        return [
            'translations' => Translation::with('copyright')->get()
        ];
    }

    public function translation(string $abbr)
    {
        return [
            'translation' => Translation::with('copyright')->where('abbr', $abbr)->first()
        ];
    }
}
