<?php

namespace App\Http\Controllers\Admin;

use App\Models\Message;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageController extends Controller
{
       // Tampilkan semua pesan
    public function index()
    {
        $messages = Message::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $unreadCount = Message::unread()->count();

        return view('admin.messages.index', compact('messages', 'unreadCount'));
    }

    // Tampilkan detail pesan
    public function show($id)
    {
        $message = Message::with('user')->findOrFail($id);

        // Tandai sebagai sudah dibaca
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('admin.messages.show', compact('message'));
    }

    // Hapus pesan
    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.messages.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }

    // Tandai sebagai sudah dibaca
    public function markAsRead($id)
    {
        $message = Message::findOrFail($id);
        $message->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Pesan ditandai sebagai sudah dibaca.');
    }

    // Tandai semua sebagai sudah dibaca
    public function markAllAsRead()
    {
        Message::where('is_read', false)->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Semua pesan ditandai sebagai sudah dibaca.');
    }
}
