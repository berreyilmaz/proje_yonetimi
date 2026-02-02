<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Finans - {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#F8F9FD] flex">

    <main class="flex-1 p-10">
        <div class="mb-6">
            <a href="{{ route('finans.index') }}" 
               class="inline-flex items-center gap-3 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-2xl font-bold shadow-lg">
                <div class="bg-white/20 rounded-lg p-1">
                    <i class="fas fa-arrow-left text-sm"></i>
                </div>
                <span class="text-sm tracking-wide">Finans Listesine Dön</span>
            </a>
        </div>

        <div class="bg-white rounded-[40px] shadow-sm border border-gray-50 p-8 mb-8">
            <div class="flex justify-between items-start gap-6">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900">{{ $user->name }}</h1>
                    <p class="text-sm text-gray-400 mt-1">{{ $user->email }}</p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('tasks.index', ['assigned_to' => $user->id]) }}" 
                       class="px-4 py-2 rounded-2xl bg-purple-50 text-purple-600 font-bold hover:bg-purple-100 transition">
                        Atanan Görevler
                    </a>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-[16px]">
                    <span class="text-xs font-bold text-gray-400 uppercase">Haftalık Çalışma</span>
                    <div class="text-xl font-black text-gray-900 mt-2">{{ $weekly_hours_text }}</div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-[16px]">
                    <span class="text-xs font-bold text-gray-400 uppercase">Toplam Ödeme</span>
                    <div class="text-xl font-black text-gray-900 mt-2">{{ number_format($total_paid,2,',','.') }} ₺</div>
                </div>

                <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-[16px]">
                    <span class="text-xs font-bold text-gray-400 uppercase">Görev Sayısı</span>
                    <div class="text-xl font-black text-gray-900 mt-2">{{ $tasks->count() }}</div>
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <section class="bg-white rounded-[20px] p-6 shadow-sm border border-gray-50">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg">Görevler</h3>
                    <a href="{{ route('tasks.index', ['assigned_to' => $user->id]) }}" class="text-purple-600 text-sm font-bold">Hepsini Gör</a>
                </div>

                @forelse($tasks as $t)
                    <div class="py-4 border-b last:border-b-0">
                        <a href="{{ route('tasks.show', $t) }}" class="font-bold text-gray-800 hover:underline">{{ $t->title }}</a>
                        <div class="text-sm text-gray-500 mt-1">
                            {{ $t->project?->title ?? 'Genel' }} — <span class="uppercase">{{ $t->status }}</span>
                        </div>
                        @if($t->description)
                            <div class="text-sm text-gray-400 mt-2 truncate">{{ $t->description }}</div>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-400">Atanmış görev bulunmamaktadır.</p>
                @endforelse
            </section>

            <section class="bg-white rounded-[20px] p-6 shadow-sm border border-gray-50">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg">Ödemeler</h3>
                    <span class="text-sm text-gray-400">{{ $payments->count() }} kayıt</span>
                </div>

                @forelse($payments as $p)
                    <div class="py-4 border-b last:border-b-0">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="font-bold">{{ number_format($p->amount,2,',','.') }} ₺</div>
                                <div class="text-sm text-gray-500">{{ $p->description ?? '-' }}</div>
                            </div>
                            <div class="text-xs text-gray-400">{{ $p->date?->translatedFormat('d M Y') }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400">Ödeme kaydı bulunmamaktadır.</p>
                @endforelse
            </section>
        </div>
    </main>

</body>
</html>