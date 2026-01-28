<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proje Yönetim Paneli</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8F9FD; }
        .sidebar-active { background: linear-gradient(135deg, #A855F7 0%, #7E22CE 100%); color: white; shadow: 0 10px 15px -3px rgba(168, 85, 247, 0.4); }
        .custom-shadow { box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05); }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-gray-700">

    <aside class="w-72 bg-white border-r border-gray-100 flex flex-col p-8">
        <div class="flex items-center gap-3 mb-12 px-2">
            <div class="p-2.5 bg-purple-600 rounded-xl text-white shadow-lg shadow-purple-200">
                <i class="fas fa-layer-group text-xl"></i>
            </div>
            <span class="text-2xl font-bold tracking-tight text-gray-800">
                {{ auth()->user()->company?->name ?? 'Şirket Yok' }}
            </span>
        </div>

        <nav class="flex-1 space-y-3">
            <p class="text-[11px] text-gray-400 font-bold uppercase tracking-[2px] mb-4 px-2">Genel</p>
            
            <a href="#" class="sidebar-active flex items-center gap-4 p-4 rounded-2xl shadow-xl shadow-purple-100 transition-all">
                <i class="fas fa-chart-pie w-5"></i> 
                <span class="font-semibold">Panel</span>
            </a>
            
            <a href="#" class="flex items-center gap-4 p-4 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-2xl transition-all">
                <i class="fas fa-list-check w-5"></i> 
                <span class="font-medium">Görevlerim</span>
            </a>

            <a href="{{ route('projects.index') }}" class="flex items-center gap-4 p-4 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-2xl transition-all">
                <i class="fas fa-folder w-5"></i> 
                <span class="font-medium">Projeler</span>
            </a>

            <a href="#" class="flex items-center gap-4 p-4 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-2xl transition-all">
                <i class="fas fa-calendar-alt w-5"></i> 
                <span class="font-medium">Takvim</span>
            </a>
        </nav>

        <div class="mt-auto pt-10">
            <a href="#" 
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="flex items-center gap-4 p-4 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-2xl transition-all group">
                
                <div class="w-10 h-10 bg-red-50 text-red-500 rounded-xl flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-all">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                
                <span class="font-bold">Çıkış Yap</span>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto p-10">
        <div class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 leading-tight">Hoşgeldin, {{ auth()->user()->name }}!</h1>
            </div>
            
            <div class="relative w-1/3">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" placeholder="Bir şeyler ara..." 
                       class="w-full bg-white border-none rounded-2xl py-4 pl-14 shadow-sm focus:ring-2 focus:ring-purple-100 transition-all outline-none">
            </div>
        </div>

        <div class="grid grid-cols-3 gap-8 mb-12">
            <div class="bg-gradient-to-br from-purple-500 to-purple-700 p-8 rounded-[40px] text-white shadow-2xl shadow-purple-200 relative overflow-hidden group">
                <div class="relative z-10">
                    <span class="text-6xl font-bold block mb-2">{{ $completedProjectsCount }}</span>
                    <span class="text-lg font-medium opacity-90">Tamamlanan</span>
                </div>
                <i class="fas fa-check-circle absolute -right-6 -bottom-6 text-white opacity-10 text-[140px] group-hover:scale-110 transition-transform"></i>
            </div>
            
            <div class="bg-gradient-to-br from-orange-400 to-orange-500 p-8 rounded-[40px] text-white shadow-2xl shadow-orange-100 relative overflow-hidden group">
                <div class="relative z-10">
                    <span class="text-6xl font-bold block mb-2">{{ $continuingProjectsCount }}</span>
                    <span class="text-lg font-medium opacity-90">Devam Eden</span>
                </div>
                <i class="fas fa-clock absolute -right-6 -bottom-6 text-white opacity-10 text-[140px] group-hover:scale-110 transition-transform"></i>
            </div>

            <div class="bg-white p-8 rounded-[40px] custom-shadow border border-gray-50 flex flex-col justify-between">
                <span class="text-gray-400 font-bold text-xs uppercase tracking-widest">Ekip Üyeleri</span>
                
                <div class="flex -space-x-3 my-4">
                {{-- Sadece ilk 2 üyeyi gösteriyoruz (Tasarımın bozulmaması için) --}}
                @foreach($teamMembers->take(2) as $member)
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=random" 
                        class="w-12 h-12 rounded-full border-4 border-white shadow-sm" 
                        title="{{ $member->name }}">
                @endforeach

                {{-- Eğer toplam üye sayısı 2'den fazlaysa, geri kalanı +X olarak göster --}}
                @if($teamCount > 2)
                    <div class="w-12 h-12 rounded-full border-4 border-white bg-gray-50 flex items-center justify-center text-xs font-bold text-gray-500 shadow-sm">
                        +{{ $teamCount - 2 }}
                    </div>
                @endif
            </div>
                <div class="flex flex-col">
                    <span class="text-3xl font-bold text-gray-800">{{ $personalHours }}</span>
                    <span class="text-sm font-medium text-gray-400 uppercase tracking-tighter">Haftalık Çalışma Saatin</span>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold text-gray-800">Son Görevler</h3>
        <a href="{{ route('projects.index') }}" class="text-purple-600 font-bold text-sm hover:underline">Hepsini Gör</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        @forelse($continuingProjects as $project)
            @php
                // Sırayla mor ve turuncu gitmesi için (tek sayılar mor, çift sayılar turuncu)
                $isPurple = $loop->iteration % 2 != 0; 
                
                $deadline = $project->end_date ? \Illuminate\Support\Carbon::parse($project->end_date) : null;
                // ceil kullanarak günü yukarı yuvarlıyoruz, böylece küsuratlı sayı çıkmaz
                $daysLeft = $deadline ? ceil(now()->diffInDays($deadline, false)) : null;
            @endphp

            <div class="bg-white p-8 rounded-[40px] shadow-sm border border-gray-50 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-6">
                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $isPurple ? 'bg-purple-50 text-purple-600' : 'bg-orange-50 text-orange-600' }}">
                        @if($daysLeft !== null)
                            @if($daysLeft > 0)
                                {{ $daysLeft }} GÜN KALDI
                            @elseif($daysLeft == 0)
                                BUGÜN SON GÜN
                            @else
                                SÜRESİ DOLDU
                            @endif
                        @else
                            TARİH BELİRTİLMEDİ
                        @endif
                    </span>
                    <button class="text-gray-300 hover:text-gray-500 transition-colors">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                </div>

                <h4 class="text-xl font-extrabold text-gray-800 mb-8 leading-tight h-12 overflow-hidden">
                    {{ $project->title }}
                </h4>

                <div class="space-y-4">
                    <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest">
                        <span class="text-gray-400">İlerleme</span>
                        <span class="{{ $isPurple ? 'text-purple-600' : 'text-orange-500' }}">
                            %{{ $project->progress ?? 0 }}
                        </span>
                    </div>
                    
                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div class="{{ $isPurple ? 'bg-purple-600' : 'bg-orange-500' }} h-full rounded-full transition-all duration-700 ease-out" 
                            style="width: {{ $project->progress ?? 0 }}%;">
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-2 text-center p-20 bg-white rounded-[40px] text-gray-400 border-2 border-dashed border-gray-100">
                <i class="fas fa-tasks text-3xl mb-4 block opacity-20"></i>
                <p class="font-bold">Henüz devam eden bir görev bulunamadı.</p>
            </div>
        @endforelse
    </div>
        
    </main>

    <aside class="w-80 bg-white border-l border-gray-100 p-8 flex flex-col">
        <div class="bg-gray-50 rounded-[40px] p-8 mb-10 text-center custom-shadow">
    <div class="flex justify-between text-gray-400 mb-6 text-xs font-bold uppercase tracking-widest">
        <span>Zamanlayıcı</span>
        <i class="fas fa-ellipsis-v"></i>
    </div>
    <div class="text-4xl font-black text-gray-800 mb-8 tracking-tighter italic" id="timerDisplay">
    00:00:00
</div>

<div class="flex justify-center gap-5">
        <button id="saveBtn" class="w-12 h-12 rounded-full bg-orange-100 text-orange-500 flex items-center justify-center hover:bg-orange-200 transition-all">
            <i class="fas fa-save"></i> </button>
        
        <button id="startBtn" class="w-12 h-12 rounded-full bg-purple-600 text-white flex items-center justify-center shadow-lg shadow-purple-200 hover:scale-110 transition-all">
            <i class="fas fa-play text-xs" id="playIcon"></i>
        </button>
    </div>
</div>

<div class="mb-10">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-bold text-lg">{{ $currentDate->translatedFormat('F Y') }}</h3>
        <div class="flex gap-2">
            <button class="p-1 text-gray-300"><i class="fas fa-chevron-left text-xs"></i></button>
            <button class="p-1 text-gray-800"><i class="fas fa-chevron-right text-xs"></i></button>
        </div>
    </div>
    <div class="grid grid-cols-7 gap-y-4 text-center text-xs font-semibold text-gray-400">
        <span>Pzt</span><span>Sal</span><span>Çar</span><span>Per</span><span>Cum</span><span>Cmt</span><span>Paz</span>
        
        @for($i = 0; $i < 7; $i++)
            @php 
                $date = $startOfWeek->copy()->addDays($i); 
            @endphp
            
            @if($date->isToday())
                <span class="bg-purple-600 text-white w-6 h-6 flex items-center justify-center rounded-full mx-auto">
                    {{ $date->day }}
                </span>
            @else
                <span class="text-gray-800 flex items-center justify-center h-6">
                    {{ $date->day }}
                </span>
            @endif
        @endfor
    </div>
</div>

        <div>
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg">Mesajlar</h3>
                <button class="text-purple-600 text-xs font-bold">Hepsini Gör</button>
            </div>
            <div class="space-y-6">
                <div class="flex items-center gap-4">
                    <img src="https://ui-avatars.com/api/?name=Ahmet+Yilmaz" class="w-10 h-10 rounded-full">
                    <div class="flex-1">
                        <h4 class="text-sm font-bold">Ahmet Yılmaz</h4>
                        <p class="text-xs text-gray-400 truncate w-32">Dosyaları gönderdim...</p>
                    </div>
                    <span class="text-[10px] text-gray-300 font-bold">15:18</span>
                </div>
                </div>
        </div>
    </aside>

</body>
</html>


<script>
    let timer;
    let totalSeconds = 0; 
    let isRunning = false;

    const display = document.getElementById('timerDisplay');
    const startBtn = document.getElementById('startBtn');
    const saveBtn = document.getElementById('saveBtn');

    function updateDisplay() {
        let hrs = Math.floor(totalSeconds / 3600);
        let mins = Math.floor((totalSeconds % 3600) / 60);
        let secs = totalSeconds % 60;
        display.textContent = `${hrs.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }

    startBtn.addEventListener('click', function() {
        if (!isRunning) {
            isRunning = true;
            timer = setInterval(() => {
                totalSeconds++;
                updateDisplay();
            }, 1000);
            // İkonu duraklat yap ve butonu salla (hover etkisi için)
            this.innerHTML = '<i class="fas fa-pause text-xs"></i>';
            this.classList.add('bg-red-500'); // Çalışırken kırmızıya dönebilir (isteğe bağlı)
        } else {
            clearInterval(timer);
            isRunning = false;
            this.innerHTML = '<i class="fas fa-play text-xs"></i>';
            this.classList.remove('bg-red-500');
        }
    });

    saveBtn.addEventListener('click', function() {
        if (totalSeconds < 1) return alert("Kaydedilecek bir süre yok!");

        // Gönderirken butonu pasif yap ki çift kayıt olmasın
        saveBtn.disabled = true;

        fetch('/save-work-hours', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ seconds: parseInt(totalSeconds) })
        })
        .then(res => res.json())
        .then(data => {
            location.reload(); 
        })
        .catch(err => {
            alert("Hata!");
            saveBtn.disabled = false;
        });
    });
</script>