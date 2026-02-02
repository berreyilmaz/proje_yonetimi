<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Takvim | Proje Yönetimi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8F9FD; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .chat-gradient { background: linear-gradient(135deg, #A855F7 0%, #7E22CE 100%); }
        .custom-shadow { box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05); }
        
        /* FullCalendar Modern Tasarım */
        .fc { --fc-border-color: #f1f1f1; --fc-today-bg-color: #f5f3ff; height: 100%; }
        .fc .fc-toolbar-title { font-weight: 800; font-size: 1.25rem; color: #1f2937; }
        .fc .fc-button-primary { background-color: white; border: 1px solid #e5e7eb; color: #374151; font-weight: 600; border-radius: 12px; padding: 8px 16px; }
        .fc .fc-button-primary:hover { background-color: #f9fafb; color: #7E22CE; }
        .fc .fc-button-active { background-color: #7E22CE !important; border-color: #7E22CE !important; color: white !important; }
        .fc th { padding: 12px 0 !important; font-size: 0.7rem; text-transform: uppercase; color: #9ca3af; }
        .fc-event { border: none; padding: 3px 6px; border-radius: 6px; font-size: 0.7rem; font-weight: 600; cursor: pointer; }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-gray-700">

    <aside class="w-72 bg-white border-r border-gray-100 flex flex-col p-8 overflow-y-auto no-scrollbar">
        <div class="flex items-center gap-3 mb-12 px-2">
            <div class="p-2.5 bg-purple-600 rounded-xl text-white shadow-lg shadow-purple-200">
                <i class="fas fa-layer-group text-xl"></i>
            </div>
            <span class="text-2xl font-bold tracking-tight text-gray-800">
                {{ auth()->user()->company?->name ?? 'Panel' }}
            </span>
        </div>

        <nav class="flex-1 space-y-3">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-4 p-4 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-2xl transition-all group">
                <i class="fas fa-chart-pie w-5"></i> 
                <span class="font-medium">Panel</span>
            </a>
            
            <a href="{{ route('calendar.index') }}" class="sidebar-active flex items-center gap-4 p-4 rounded-2xl bg-purple-600 text-white shadow-xl shadow-purple-100 transition-all font-bold">
                <i class="fas fa-calendar-alt w-5"></i> 
                <span>Takvim</span>
            </a>


        </nav>

        <div class="mt-auto">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-4 p-4 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-2xl transition-all font-bold">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Çıkış Yap</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto p-10 no-scrollbar">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 leading-tight">Takvim</h1>
                <p class="text-gray-400 font-medium italic">Projelerini ve özel etkinliklerini takip et.</p>
            </div>
            
            <button onclick="openModal()" class="chat-gradient text-white px-8 py-4 rounded-[20px] font-bold shadow-lg shadow-purple-200 hover:-translate-y-1 transition-all flex items-center gap-3">
                <i class="fas fa-plus"></i>
                <span>Yeni Etkinlik</span>
            </button>
        </div>

        <div class="grid grid-cols-12 gap-8">
            <div class="col-span-12 lg:col-span-9 bg-white p-8 rounded-[40px] custom-shadow border border-gray-50 h-[750px]">
                <div id="calendar"></div>
            </div>

            <div class="col-span-12 lg:col-span-3 space-y-6">
                <div class="bg-white p-6 rounded-[32px] border border-gray-50 custom-shadow">
                    <h3 class="font-bold text-lg mb-6 flex items-center gap-2">
                        <i class="fas fa-bolt text-orange-400"></i> Yaklaşanlar
                    </h3>
                    
                    <div class="space-y-4 max-h-[400px] overflow-y-auto no-scrollbar">
                        @forelse($events as $event)
                        <div class="group p-4 rounded-2xl bg-gray-50 hover:bg-purple-50 transition-all border-l-4" style="border-color: {{ $event->color ?? '#7E22CE' }}">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">
                                {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y') }}
                            </p>
                            <h4 class="font-bold text-gray-800 group-hover:text-purple-700 transition-colors">{{ $event->title }}</h4>
                        </div>
                        @empty
                        <div class="text-center py-6 text-gray-400">
                            <i class="fas fa-calendar-day mb-2 text-2xl opacity-20"></i>
                            <p class="text-xs">Henüz planlanmış bir etkinlik yok.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-gradient-to-br from-indigo-600 to-purple-700 p-6 rounded-[32px] text-white shadow-xl">
                    <i class="fas fa-info-circle mb-3 text-2xl opacity-50"></i>
                    <p class="text-sm font-medium leading-relaxed">
                        Etkinlik ekleyerek ekibinin teslim tarihlerinden haberdar olmasını sağlayabilirsin.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <div id="eventModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-md rounded-[40px] p-10 shadow-2xl relative animate-in fade-in zoom-in duration-300">
            <button onclick="closeModal()" class="absolute top-8 right-8 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
            
            <h2 class="text-2xl font-black text-gray-800 mb-8">Yeni Etkinlik</h2>
            
            <form action="{{ route('calendar.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 block ml-1">Etkinlik Başlığı</label>
                    <input type="text" name="title" required placeholder="Toplantı, Teslim vb." 
                           class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-purple-200 outline-none font-medium">
                </div>
                
                <div>
                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 block ml-1">Tarih</label>
                    <input type="date" name="event_date" required 
                           class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-purple-200 outline-none font-medium">
                </div>

                <div>
                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 block ml-1">Kategori Rengi</label>
                    <div class="flex gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="color" value="#7E22CE" checked class="hidden peer">
                            <div class="w-10 h-10 rounded-xl bg-purple-600 peer-checked:ring-4 ring-purple-200 transition-all flex items-center justify-center text-white">
                                <i class="fas fa-check text-xs opacity-0 peer-checked:opacity-100"></i>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="color" value="#F97316" class="hidden peer">
                            <div class="w-10 h-10 rounded-xl bg-orange-500 peer-checked:ring-4 ring-orange-200 transition-all flex items-center justify-center text-white">
                                <i class="fas fa-check text-xs opacity-0 peer-checked:opacity-100"></i>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="color" value="#EF4444" class="hidden peer">
                            <div class="w-10 h-10 rounded-xl bg-red-500 peer-checked:ring-4 ring-red-200 transition-all flex items-center justify-center text-white">
                                <i class="fas fa-check text-xs opacity-0 peer-checked:opacity-100"></i>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full chat-gradient text-white py-5 rounded-2xl font-bold shadow-lg shadow-purple-100 hover:scale-[1.02] active:scale-95 transition-all">
                    Kaydet
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'tr',
                firstDay: 1, // Pazartesi başlasın
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                buttonText: {
                    today: 'Bugün',
                    month: 'Ay',
                    week: 'Hafta'
                },
                events: @json($events->map(fn($e) => [
                    'title' => $e->title,
                    'start' => $e->event_date->format('Y-m-d'),
                    'color' => $e->color ?? '#7E22CE'
                ])),
                eventTimeFormat: { hour: '2-digit', minute: '2-digit', meridiem: false }
            });
            calendar.render();
        });

        function openModal() { document.getElementById('eventModal').classList.remove('hidden'); }
        function closeModal() { document.getElementById('eventModal').classList.add('hidden'); }
    </script>
</body>
</html>