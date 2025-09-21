<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoActivity; // Pastikan model VideoActivity ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\SchoolProfile; // <--- TAMBAHKAN INI

class VideoActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videoActivities = VideoActivity::orderBy('order', 'asc')->get();
        
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.video_activities.index', compact('videoActivities', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.video_activities.create', compact('schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|url|max:255',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        VideoActivity::create($validatedData);

        return redirect()->route('admin.video-activities.index')->with('success', 'Video kegiatan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VideoActivity $videoActivity)
    {
        // --- Tambahkan ini untuk $schoolProfile ---
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        // -----------------------------------------

        return view('admin.video_activities.edit', compact('videoActivity', 'schoolProfile')); // <--- Tambahkan 'schoolProfile' di compact
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VideoActivity $videoActivity)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|url|max:255',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $videoActivity->update($validatedData);

        return redirect()->route('admin.video-activities.index')->with('success', 'Video kegiatan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VideoActivity $videoActivity)
    {
        try {
            $videoActivity->delete();
            return redirect()->route('admin.video-activities.index')->with('success', 'Video kegiatan berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting video activity: ' . $e->getMessage());
            return redirect()->route('admin.video-activities.index')->with('error', 'Gagal menghapus video kegiatan.');
        }
    }
}