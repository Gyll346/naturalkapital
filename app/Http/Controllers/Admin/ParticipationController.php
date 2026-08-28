<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participation;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ParticipationController extends Controller
{
    public function index(Request $request)
    {
        $query = Participation::latest();

        if ($request->has('unread') && $request->unread == '1') {
            $query->where('is_read', false);
        }

        if ($request->filled('interest')) {
            $query->where('interest', $request->interest);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $participations = $query->paginate(15)->withQueryString();
        $unreadCount = Participation::where('is_read', false)->count();

        return view('admin.participations.index', compact('participations', 'unreadCount'));
    }

    public function show($id)
    {
        $participation = Participation::findOrFail($id);
        if (!$participation->is_read) {
            $participation->update(['is_read' => true]);
        }

        return view('admin.participations.show', compact('participation'));
    }

    public function markAsRead($id)
    {
        $participation = Participation::findOrFail($id);
        $participation->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Pendaftaran ditandai sebagai sudah dibaca.');
    }

    public function markAsUnread($id)
    {
        $participation = Participation::findOrFail($id);
        $participation->update(['is_read' => false]);

        return redirect()->back()->with('success', 'Pendaftaran ditandai sebagai belum dibaca.');
    }

    public function destroy($id)
    {
        $participation = Participation::findOrFail($id);
        $name = $participation->name;
        $participation->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE_PARTICIPATION',
            'module' => 'PARTICIPATION',
            'description' => "Menghapus formulir partisipasi dari {$name}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.participations.index')->with('success', "Formulir dari {$name} berhasil dihapus.");
    }
}
