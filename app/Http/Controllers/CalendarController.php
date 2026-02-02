<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event; // Etkinlikler için bir modeliniz olduğunu varsayıyorum
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index() {
        $events = Event::all(); // Veya auth()->user()->events
        return view('calendar.index', compact('events'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'color' => 'required|string',
        ]);

        $data['user_id'] = Auth::id(); // Giriş yapan kullanıcının ID'sini ekle

        Event::create($data);

        return back()->with('success', 'Etkinlik eklendi!');
    }
}