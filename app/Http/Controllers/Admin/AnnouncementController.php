<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GlobalAnnouncement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = GlobalAnnouncement::latest()->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        GlobalAnnouncement::create([
            'title'     => $request->title,
            'message'   => $request->message,
            'is_active' => $request->has('is_active'),
            'starts_at' => $request->starts_at,
            'ends_at'   => $request->ends_at,
            'recurrence_type' => $request->recurrence_type ?? 'none',
            'recurrence_time' => $request->recurrence_time,

        ]);

        return back()->with('success', 'Announcement created successfully');
    }

    public function toggle($id)
    {
        $ann = GlobalAnnouncement::findOrFail($id);
        $ann->is_active = !$ann->is_active;
        $ann->save();

        return back();
    }

    public function destroy($id)
    {
        GlobalAnnouncement::findOrFail($id)->delete();
        return back();
    }
}
