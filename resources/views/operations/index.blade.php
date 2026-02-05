<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operasyon Merkezi | Proje Yönetimi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FAFBFF; }
    </style>
</head>
<body class="antialiased text-[#2B3674]">

    <div class="max-w-[1440px] mx-auto p-4 md:p-10 space-y-10">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex flex-col gap-6"> <div>
                    <a href="{{ route('dashboard') }}" 
                    class="inline-flex items-center gap-3 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-purple-200 hover:shadow-purple-400 hover:-translate-x-1 transition-all duration-300 group">
                        <div class="bg-white/20 rounded-lg p-1 group-hover:bg-white/30 transition-colors">
                            <i class="fas fa-arrow-left text-sm"></i>
                        </div>
                        <span class="text-sm tracking-wide">Ana Sayfaya Dön</span>
                    </a>
                </div>

                <div>
                    <h1 class="text-4xl font-extrabold tracking-tight text-[#2B3674]">Operasyon Merkezi</h1>
                    <p class="text-gray-400 font-medium mt-2 uppercase text-xs tracking-[0.2em]">Karar Kayıtları ve Değişiklik Yönetimi</p>
                </div>
            </div>

            <a href="{{ route('operations.create') }}" class="bg-[#7E22CE] hover:bg-[#6B21A8] text-white px-8 py-4 rounded-[20px] font-bold transition-all shadow-xl shadow-purple-100 flex items-center gap-3 active:scale-95">
                <i class="fas fa-plus-circle"></i>
                Yeni Operasyon Talebi
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-[35px] shadow-sm border border-gray-50 flex items-center gap-6">
                <div class="w-16 h-16 bg-orange-50 text-orange-500 rounded-[22px] flex items-center justify-center text-2xl">
                    <i class="fas fa-hourglass-start"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Bekleyen Onaylar</p>
                    <h3 class="text-3xl font-extrabold mt-1 tracking-tight">{{ $stats['pending'] ?? 0 }}</h3>
                </div>
            </div>
            <div class="bg-white p-8 rounded-[35px] shadow-sm border border-gray-50 flex items-center gap-6">
                <div class="w-16 h-16 bg-green-50 text-green-500 rounded-[22px] flex items-center justify-center text-2xl">
                    <i class="fas fa-lira-sign"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Onaylı Bütçe Etkisi</p>
                    <h3 class="text-3xl font-extrabold mt-1 tracking-tight text-green-600">
                        {{ isset($stats['total_budget']) ? number_format($stats['total_budget'], 0, ',', '.') : '0' }} TL
                    </h3>
                </div>
            </div>
            <div class="bg-white p-8 rounded-[35px] shadow-sm border border-gray-50 flex items-center gap-6">
                <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-[22px] flex items-center justify-center text-2xl">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Kayıtlı Operasyon</p>
                    <h3 class="text-3xl font-extrabold mt-1 tracking-tight">{{ $operations->total() ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[40px] shadow-sm border border-gray-50 overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                <h4 class="font-extrabold text-xl">Operasyon Günlüğü</h4>
                <div class="flex items-center gap-3">
                    <button class="bg-gray-50 text-gray-500 px-4 py-2 rounded-xl text-xs font-bold hover:bg-gray-100 transition-colors italic">Filtrele</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">Operasyon Türü</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">Proje</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">Değişim/Etki</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">Talep Eden</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">Durum</th>
                            <th class="px-8 py-5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($operations as $op)
                        <tr class="hover:bg-gray-50/30 transition-all group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    @php
                                        $typeConfig = match($op->type) {
                                            'time' => ['icon' => 'clock', 'color' => 'blue', 'label' => 'Süre Uzatımı'],
                                            'budget' => ['icon' => 'wallet', 'color' => 'green', 'label' => 'Bütçe Revizesi'],
                                            'resource' => ['icon' => 'users', 'color' => 'purple', 'label' => 'Kaynak Değişimi'],
                                            default => ['icon' => 'cog', 'color' => 'gray', 'label' => 'Genel']
                                        };
                                    @endphp
                                    <div class="w-12 h-12 flex items-center justify-center bg-{{ $typeConfig['color'] }}-50 text-{{ $typeConfig['color'] }}-500 rounded-2xl text-lg">
                                        <i class="fas fa-{{ $typeConfig['icon'] }}"></i>
                                    </div>
                                    <span class="font-extrabold text-sm tracking-tight">{{ $typeConfig['label'] }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-sm font-semibold text-gray-500">{{ $op->project->title }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-base font-black text-[#2B3674]">
                                        {{ $op->impact_value > 0 ? '+' : '' }}{{ $op->impact_value }} 
                                        {{ $op->type == 'time' ? 'Gün' : ($op->type == 'budget' ? 'TL' : 'Kişi') }}
                                    </span>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter mt-0.5 truncate max-w-[180px]">
                                        {{ $op->description }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-[10px] font-black text-purple-700 ring-2 ring-white">
                                        {{ strtoupper(substr($op->requester->name, 0, 2)) }}
                                    </div>
                                    <span class="text-xs font-bold text-gray-600">{{ $op->requester->name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border
                                    {{ $op->status == 'approved' ? 'bg-green-50 text-green-600 border-green-100' : ($op->status == 'pending' ? 'bg-orange-50 text-orange-600 border-orange-100' : 'bg-red-50 text-red-600 border-red-100') }}">
                                    {{ $op->status == 'approved' ? 'Tamamlandı' : ($op->status == 'pending' ? 'İşleniyor' : 'İptal') }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
    <div class="flex items-center justify-end gap-3">
        @if($op->status == 'pending')
            <form action="{{ route('operations.approve', $op->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-green-100 active:scale-95">
                    <i class="fas fa-check mr-1"></i> Onayla
                </button>
            </form>

            <form action="{{ route('operations.reject', $op->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-red-100 active:scale-95">
                    <i class="fas fa-times mr-1"></i> Reddet
                </button>
            </form>
        @else
            <div class="flex flex-col items-end">
                @if($op->status == 'approved')
                    <div class="flex items-center gap-2 text-green-600 bg-green-50 px-4 py-2 rounded-xl border border-green-100">
                        <i class="fas fa-check-double text-[10px]"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest">Onaylandı</span>
                    </div>
                @else
                    <div class="flex items-center gap-2 text-red-600 bg-red-50 px-4 py-2 rounded-xl border border-red-100">
                        <i class="fas fa-ban text-[10px]"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest">Reddedildi</span>
                    </div>
                @endif
                
                {{-- 8.1 Kayıt Altındadır: İşlemi yapan kullanıcıyı göster --}}
                <span class="text-[9px] text-gray-400 mt-2 font-bold italic tracking-tight">
                    <i class="fas fa-user-shield mr-1"></i> {{ $op->approver->name ?? 'Sistem Yetkilisi' }}
                </span>
            </div>
        @endif
    </div>
</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-200 text-3xl italic">!</div>
                                    <p class="text-gray-400 font-bold text-sm">Henüz bir operasyonel kayıt oluşturulmamış.</p>
                                </div>
                            </td>
                        </tr>
                        
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($operations->hasPages())
            <div class="p-8 bg-gray-50/50 border-t border-gray-50">
                {{ $operations->links() }}
            </div>
            @endif
        </div>
    </div>

</body>
</html>