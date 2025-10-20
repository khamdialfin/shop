<?php

namespace App\Http\Controllers\User;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageController extends Controller
{
   // Simpan pesan dari user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:500',
            'message' => 'required|string|min:10|max:2000',
        ]);

        try {
            // Simpan pesan ke database
            Message::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'is_read' => false,
            ]);

            // Redirect dengan SweetAlert success message
            return redirect()->route('user.contact.index')
                ->with('success', 'Pesan berhasil dikirim! Admin akan membalas via email dalam 1-2 jam.');

        } catch (\Exception $e) {
            // Redirect dengan SweetAlert error message
            return redirect()->route('user.contact.index')
                ->with('error', 'Gagal mengirim pesan. Silakan coba lagi.')
                ->withInput();
        }
    }
}
