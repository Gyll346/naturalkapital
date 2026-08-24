<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeamMember;
use App\Models\TeamCategory;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class TeamMemberController extends Controller
{
    public function index()
    {
        $members = TeamMember::with('category')->orderBy('sort_order')->paginate(20);
        return view('admin.teams.index', compact('members'));
    }

    public function create()
    {
        $categories = TeamCategory::orderBy('sort_order')->get();
        return view('admin.teams.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:team_categories,id',
            'full_name' => 'required|string|max:150',
            'position' => 'required|string|max:100',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'linkedin_url' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:100',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,alumni',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('teams', 'public');
        }

        $member = TeamMember::create([
            'category_id' => $validated['category_id'],
            'full_name' => $validated['full_name'],
            'position' => $validated['position'],
            'bio' => $validated['bio'] ?? null,
            'photo_path' => $photoPath,
            'linkedin_url' => $validated['linkedin_url'] ?? null,
            'email' => $validated['email'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'status' => $validated['status'],
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE_TEAM_MEMBER',
            'module' => 'TEAM',
            'record_id' => $member->id,
            'details' => "Menambahkan anggota tim {$member->full_name} ({$member->position})",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.teams.index')->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $member = TeamMember::findOrFail($id);
        $categories = TeamCategory::orderBy('sort_order')->get();
        return view('admin.teams.edit', compact('member', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $member = TeamMember::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:team_categories,id',
            'full_name' => 'required|string|max:150',
            'position' => 'required|string|max:100',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'linkedin_url' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:100',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,alumni',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('teams', 'public');
        }

        $member->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE_TEAM_MEMBER',
            'module' => 'TEAM',
            'record_id' => $member->id,
            'details' => "Memperbarui profil anggota tim {$member->full_name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.teams.index')->with('success', 'Profil anggota tim berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $member = TeamMember::findOrFail($id);
        $name = $member->full_name;
        $member->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE_TEAM_MEMBER',
            'module' => 'TEAM',
            'record_id' => $id,
            'details' => "Menghapus anggota tim {$name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.teams.index')->with('success', 'Anggota tim berhasil dihapus.');
    }
}
