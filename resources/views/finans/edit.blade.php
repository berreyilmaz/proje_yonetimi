<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>Finans Düzenle - {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#F8F9FD]">
    <main class="max-w-3xl mx-auto p-10">
        <div class="mb-6">
            <a href="{{ route('finans.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-xl shadow-sm border border-gray-100 text-gray-600 hover:bg-gray-50 transition-all text-sm font-semibold">
                <i class="fas fa-arrow-left"></i> Geri Dön
            </a>
        </div>

        <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-100">
            <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-50">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Finansal Ayarlar</h1>
                    <p class="text-sm text-gray-400">{{ $user->name }} kullanıcısı için maaş ve maliyet yapısını yapılandırın.</p>
                </div>
            </div>

            <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Aylık Sabit Maaş (₺)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-semibold">₺</span>
                            <input type="number" step="0.01" name="base_salary" value="{{ $user->base_salary ?? 0 }}" 
                                   class="w-full pl-10 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-100 focus:bg-white outline-none transition-all font-medium text-gray-700" placeholder="Örn: 45000">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Saatlik Mesai Ücreti (₺)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-semibold">₺</span>
                            <input type="number" step="0.01" name="overtime_rate" value="{{ $user->overtime_rate ?? 0 }}" 
                                   class="w-full pl-10 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-100 focus:bg-white outline-none transition-all font-medium text-gray-700" placeholder="Örn: 450">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Aylık Normal Çalışma Eşiği (Saat)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-semibold"><i class="fas fa-business-time"></i></span>
                            <input type="number" name="monthly_limit_hours" value="{{ $user->monthly_limit_hours ?? 180 }}" 
                                   class="w-full pl-10 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-100 focus:bg-white outline-none transition-all font-medium text-gray-700">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Mevcut Birikmiş Süre (Saniye)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-semibold"><i class="fas fa-clock"></i></span>
                            <input type="number" name="weekly_work_hours" value="{{ $user->weekly_work_hours ?? 0 }}" 
                                   class="w-full pl-10 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-100 focus:bg-white outline-none transition-all font-medium text-gray-700">
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-50 border border-indigo-100 p-5 rounded-2xl flex gap-4">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-indigo-500 shadow-sm shrink-0">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <p class="text-xs text-indigo-800 leading-relaxed">
                        <b>Sistem Mantığı:</b> Personel aylık eşik süresini ({{ $user->monthly_limit_hours ?? 180 }} saat) doldurana kadar projeye ek maliyet yansımaz. Eşik aşıldığında, her yeni görev <b>Mesai Ücreti</b> üzerinden projenin gider hanesine eklenir.
                    </p>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-4 rounded-xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
                        Finansal Profili Güncelle
                    </button>
                    <a href="{{ route('finans.index') }}" class="px-8 bg-gray-50 text-gray-500 py-4 rounded-xl font-bold hover:bg-gray-100 transition-all text-center">
                        İptal
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>