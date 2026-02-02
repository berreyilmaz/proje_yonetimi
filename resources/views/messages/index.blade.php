<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesajlar - Proje Yönetimi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8F9FD; }
        .custom-shadow { box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05); }
        .chat-gradient { background: linear-gradient(135deg, #A855F7 0%, #7E22CE 100%); }
        /* Scrollbar'ı gizle veya şıklaştır */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-gray-700">

    <aside class="w-72 bg-white border-r border-gray-100 flex flex-col p-8">
        <div class="flex items-center gap-3 mb-12 px-2">
            <div class="p-2.5 bg-purple-600 rounded-xl text-white shadow-lg shadow-purple-200">
                <i class="fas fa-layer-group text-xl"></i>
            </div>
            <span class="text-2xl font-bold tracking-tight text-gray-800">
                {{ auth()->user()->company?->name ?? 'Panel' }}
            </span>
        </div>
        <nav class="flex-1 space-y-3">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-4 p-4 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-2xl transition-all">
                <i class="fas fa-chart-pie w-5"></i> <span class="font-medium">Panel</span>
            </a>
            <a href="{{ route('messages.index') }}" class="{{ !isset($user) ? 'sidebar-active bg-purple-600 text-white shadow-xl shadow-purple-100' : 'text-gray-400 hover:text-purple-600 hover:bg-purple-50' }} flex items-center gap-4 p-4 rounded-2xl transition-all">
                <i class="fas fa-comments w-5"></i> <span class="font-semibold">Mesajlar</span>
            </a>

        </nav>
    </aside>

    <main class="flex-1 flex p-6 gap-6 overflow-hidden">
        
        <div class="w-1/3 bg-white rounded-[40px] border border-gray-50 flex flex-col custom-shadow overflow-hidden">
            <div class="p-8 border-b border-gray-50">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Sohbetler</h2>
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" placeholder="Kişi ara..." class="w-full bg-gray-50 border-none rounded-2xl py-3 pl-12 text-sm focus:ring-2 focus:ring-purple-100 outline-none">
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-2">
                <a href="{{ route('messages.index') }}" class="flex items-center gap-4 p-4 rounded-[30px] {{ !isset($user) ? 'bg-purple-50 border border-purple-100' : 'hover:bg-gray-50' }} transition-all group">
                    <div class="w-12 h-12 chat-gradient text-white rounded-2xl flex items-center justify-center shadow-lg shadow-purple-200">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-purple-900">Genel Sohbet</h4>
                        </div>
                        <p class="text-xs text-purple-600/70 font-medium">Şirket Duyuruları</p>
                    </div>
                </a>

                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[2px] mt-6 mb-2 px-4">Kişiler</p>

                @foreach($users as $u)
                <a href="{{ route('messages.show', $u->id) }}" class="flex items-center gap-4 p-4 rounded-[30px] {{ (isset($user) && $user->id == $u->id) ? 'bg-purple-50 border border-purple-100' : 'hover:bg-gray-50' }} transition-all group">
                    <div class="relative">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&background=random" class="w-12 h-12 rounded-2xl object-cover">
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-4 border-white rounded-full"></div>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-800 group-hover:text-purple-600 transition-all text-sm">{{ $u->name }}</h4>
                        <p class="text-[10px] text-gray-400 font-medium uppercase tracking-tighter">Çevrimiçi</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <div class="flex-1 bg-white rounded-[40px] border border-gray-50 flex flex-col custom-shadow overflow-hidden">
            
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-white/50 backdrop-blur-md">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 chat-gradient rounded-2xl flex items-center justify-center text-white shadow-lg shadow-purple-100">
                        @if(isset($user))
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=ffffff&color=7E22CE" class="w-full h-full rounded-2xl">
                        @else
                            <i class="fas fa-users text-lg"></i>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">{{ isset($user) ? $user->name : 'Genel Sohbet' }}</h3>
                        <p class="text-xs text-green-500 font-bold uppercase tracking-wider">Aktif Sohbet</p>
                    </div>
                </div>
            </div>

            <div id="message-container" class="flex-1 overflow-y-auto p-8 space-y-6 bg-[#FBFBFF]">
                @forelse($messages as $msg)
                    @if($msg->sender_id == auth()->id())
                        <div class="flex items-start gap-4 flex-row-reverse max-w-[80%] ml-auto">
                            <div class="text-right">
                                <p class="text-[10px] font-bold text-purple-400 mb-1 mr-1">Siz • {{ $msg->created_at->format('H:i') }}</p>
                                <div class="chat-gradient p-4 rounded-[24px] rounded-tr-none shadow-xl shadow-purple-100">
                                    <p class="text-sm font-medium text-white leading-relaxed">{{ $msg->content }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-start gap-4 max-w-[80%]">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($msg->sender->name) }}&background=random" class="w-8 h-8 rounded-xl mt-1">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 mb-1 ml-1">{{ $msg->sender->name }} • {{ $msg->created_at->format('H:i') }}</p>
                                <div class="bg-white p-4 rounded-[24px] rounded-tl-none shadow-sm border border-gray-100 text-gray-700">
                                    <p class="text-sm font-medium leading-relaxed">{{ $msg->content }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-gray-300">
                        <i class="fas fa-comments text-5xl mb-4 opacity-20"></i>
                        <p class="font-bold">Henüz mesaj yok. İlk adımı sen at!</p>
                    </div>
                @endforelse
            </div>

            <div class="p-6 bg-white border-t border-gray-50">
                <form action="{{ route('messages.store') }}" method="POST" class="flex items-center gap-4">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ isset($user) ? $user->id : '' }}">
                    
                    <div class="flex-1 relative">
                        <input type="text" name="content" autocomplete="off" required placeholder="Mesajınızı buraya yazın..." 
                            class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-purple-100 outline-none font-medium">
                    </div>
                    <button type="submit" class="w-14 h-14 chat-gradient text-white rounded-2xl flex items-center justify-center shadow-lg shadow-purple-200 hover:-translate-y-1 transition-all">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Sayfa açıldığında mesajların en altına in
        const container = document.getElementById('message-container');
        container.scrollTop = container.scrollHeight;
    </script>

</body>
</html>