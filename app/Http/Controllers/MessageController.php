<?php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Mesajlar Ana Sayfası (Genel Sohbet)
     */
    public function index()
    {
        $authId = Auth::id();
        $companyId = Auth::user()->company_id;

        // 1. Şirketteki diğer kullanıcıları çek (Sol liste için)
        $users = User::where('company_id', $companyId)
                     ->where('id', '!=', $authId)
                     ->get();

        // 2. Genel Mesajları çek (receiver_id'si NULL olanlar genel chat sayılır)
        // Şirket bazlı filtreleme istersen sender'ın company_id'sine bakabilirsin
        $messages = Message::whereNull('receiver_id')
                    ->with('sender') // Gönderen bilgilerini (avatar vb.) çekmek için
                    ->orderBy('created_at', 'asc')
                    ->get();

        return view('messages.index', compact('users', 'messages'));
    }

    /**
     * Özel Mesajlaşma Sayfası
     */
    public function show(User $user)
    {
        $authId = Auth::id();
        $companyId = Auth::user()->company_id;

        // Sol liste için kullanıcılar tekrar lazım
        $users = User::where('company_id', $companyId)
                     ->where('id', '!=', $authId)
                     ->get();

        // İki kullanıcı arasındaki özel mesaj geçmişi
        $messages = Message::where(function($q) use ($authId, $user) {
                        $q->where('sender_id', $authId)->where('receiver_id', $user->id);
                    })->orWhere(function($q) use ($authId, $user) {
                        $q->where('sender_id', $user->id)->where('receiver_id', $authId);
                    })
                    ->with('sender')
                    ->orderBy('created_at', 'asc')
                    ->get();

        return view('messages.index', compact('users', 'messages', 'user'));
    }

    /**
     * Mesaj Kaydetme / Gönderme
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:5000',
            'receiver_id' => 'nullable|exists:users,id' // null ise genel chat'e gider
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id, // Formdan gizli input ile gelecek
            'content' => $request->content,
        ]);

        return back()->with('status', 'Mesaj gönderildi.');
    }
}