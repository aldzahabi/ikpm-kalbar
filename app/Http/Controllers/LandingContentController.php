<?php

namespace App\Http\Controllers;

use App\Models\LandingContent;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('isAdmin');
        
        $sliders = LandingContent::where('type', 'slider')
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $news = LandingContent::where('type', 'news')
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get contact settings
        $contactSettings = [
            'footer_address' => Setting::get('footer_address', ''),
            'footer_whatsapp' => Setting::get('footer_whatsapp', ''),
            'footer_email' => Setting::get('footer_email', ''),
            'footer_instagram' => Setting::get('footer_instagram', ''),
            'footer_facebook' => Setting::get('footer_facebook', ''),
            'footer_youtube' => Setting::get('footer_youtube', ''),
            'footer_maps' => Setting::get('footer_maps', ''),
        ];
        
        return view('landing-content.index', compact('sliders', 'news', 'contactSettings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('isAdmin');
        $type = $request->get('type', 'slider');
        return view('landing-content.create', compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('isAdmin');
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'type' => 'required|in:slider,news',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        // Upload gambar
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('landing-content', $imageName, 'public');
            $validated['image'] = $imagePath;
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['order'] = $request->order ?? 0;

        LandingContent::create($validated);

        $tab = $validated['type'] === 'slider' ? 'slider' : 'news';
        return redirect()->route('landing-content.index', ['tab' => $tab])
            ->with('success', 'Konten berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LandingContent $landingContent)
    {
        $this->authorize('isAdmin');
        return view('landing-content.edit', compact('landingContent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LandingContent $landingContent)
    {
        $this->authorize('isAdmin');
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'type' => 'required|in:slider,news',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        // Upload gambar baru jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($landingContent->image && Storage::disk('public')->exists($landingContent->image)) {
                Storage::disk('public')->delete($landingContent->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('landing-content', $imageName, 'public');
            $validated['image'] = $imagePath;
        } else {
            // Jika tidak ada gambar baru, pertahankan gambar lama
            unset($validated['image']);
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['order'] = $request->order ?? $landingContent->order;

        $landingContent->update($validated);

        $tab = $validated['type'] === 'slider' ? 'slider' : 'news';
        return redirect()->route('landing-content.index', ['tab' => $tab])
            ->with('success', 'Konten berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LandingContent $landingContent)
    {
        $this->authorize('isAdmin');
        
        $tab = $landingContent->type === 'slider' ? 'slider' : 'news';
        
        // Hapus gambar dari storage
        if ($landingContent->image && Storage::disk('public')->exists($landingContent->image)) {
            Storage::disk('public')->delete($landingContent->image);
        }

        $landingContent->delete();

        return redirect()->route('landing-content.index', ['tab' => $tab])
            ->with('success', 'Konten berhasil dihapus.');
    }

    /**
     * Update contact/footer settings
     */
    public function updateContact(Request $request)
    {
        $this->authorize('isAdmin');
        
        $validated = $request->validate([
            'footer_address' => 'nullable|string|max:500',
            'footer_whatsapp' => 'nullable|string|max:20',
            'footer_email' => 'nullable|email|max:255',
            'footer_instagram' => 'nullable|string|max:100',
            'footer_facebook' => 'nullable|string|max:100',
            'footer_youtube' => 'nullable|string|max:100',
            'footer_maps' => 'nullable|url|max:1000',
        ]);

        // Save each setting
        Setting::set('footer_address', $validated['footer_address'] ?? '', 'text', 'landing', 'Alamat Footer');
        Setting::set('footer_whatsapp', $validated['footer_whatsapp'] ?? '', 'string', 'landing', 'WhatsApp');
        Setting::set('footer_email', $validated['footer_email'] ?? '', 'string', 'landing', 'Email');
        Setting::set('footer_instagram', $validated['footer_instagram'] ?? '', 'string', 'landing', 'Instagram');
        Setting::set('footer_facebook', $validated['footer_facebook'] ?? '', 'string', 'landing', 'Facebook');
        Setting::set('footer_youtube', $validated['footer_youtube'] ?? '', 'string', 'landing', 'YouTube');
        Setting::set('footer_maps', $validated['footer_maps'] ?? '', 'string', 'landing', 'Google Maps');

        return redirect()->route('landing-content.index', ['tab' => 'contact'])
            ->with('success', 'Informasi kontak berhasil disimpan.');
    }
}
