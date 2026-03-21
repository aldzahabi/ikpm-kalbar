<?php

namespace App\Http\Controllers;

use App\Models\LandingContent;
use App\Models\Setting;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Display a listing of news.
     */
    public function index()
    {
        $news = LandingContent::where('type', 'news')
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->paginate(9);
        
        return view('berita.index', compact('news'));
    }

    /**
     * Display the specified news.
     */
    public function show($id)
    {
        $berita = LandingContent::where('type', 'news')
            ->where('is_active', true)
            ->findOrFail($id);
        
        // Get related news (exclude current)
        $relatedNews = LandingContent::where('type', 'news')
            ->where('is_active', true)
            ->where('id', '!=', $id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        return view('berita.show', compact('berita', 'relatedNews'));
    }
}
