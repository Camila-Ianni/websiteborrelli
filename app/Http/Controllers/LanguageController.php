<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LanguageController extends Controller
{
    private const SUPPORTED = ['es', 'en', 'ko'];

    public function switch(string $locale): RedirectResponse
    {
        if (! in_array($locale, self::SUPPORTED, true)) {
            abort(404);
        }

        session(['locale' => $locale]);

        return back();
    }
}
