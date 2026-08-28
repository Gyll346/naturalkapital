<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::latest();

        if ($request->has('unread') && $request->unread == '1') {
            $query->where('is_read', false);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $messages = $query->paginate(15)->withQueryString();
        $unreadCount = ContactMessage::where('is_read', false)->count();

        return view('admin.contact_messages.index', compact('messages', 'unreadCount'));
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('admin.contact_messages.show', compact('message'));
    }

    public function markAsRead($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Pesan ditandai sebagai sudah dibaca.');
    }

    public function markAsUnread($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['is_read' => false]);

        return redirect()->back()->with('success', 'Pesan ditandai sebagai belum dibaca.');
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $name = $message->name;
        $message->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE_CONTACT_MESSAGE',
            'module' => 'CONTACT',
            'description' => "Menghapus pesan kontak dari {$name}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.contact-messages.index')->with('success', "Pesan dari {$name} berhasil dihapus.");
    }
}
