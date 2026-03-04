<?php

namespace App\Http\Controllers;

use App\Models\Law;
use Illuminate\Http\Request;

class LawController extends Controller
{
    public function index()
    {
        $englishLaw = Law::where('language', 'english')->first();
        $arabicLaw = Law::where('language', 'arabic')->first();
        $frenchLaw = Law::where('language', 'french')->first();

        // If specific language laws don't exist, we just pass nulls. 
        // The view should handle empty states or we can replicate the default creation logic if critical,
        // but for a public viewer, read-only is safer. 
        // Admin side ensures creation.

        return view('law', compact('englishLaw', 'arabicLaw', 'frenchLaw'));
    }
}
