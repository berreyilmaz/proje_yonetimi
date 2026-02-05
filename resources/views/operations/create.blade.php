<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Operasyon Kaydı</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FAFBFF; }</style>
</head>
<body class="antialiased text-[#2B3674]">
    @if ($errors->any())
    <div class="text-red-500 font-bold">
        {{ implode('', $errors->all(':message')) }}
    </div>
@endif
    <div class="max-w-4xl mx-auto p-6 md:p-12">
        <div class="mb-10">
            <a href="{{ route('operations.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-purple-600 transition-colors mb-6 group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                Merkeze Dön
            </a>
            <h1 class="text-4xl font-extrabold tracking-tight">Yeni Operasyon Talebi</h1>
            <p class="text-gray-400 font-medium mt-2">Mevcut planı değiştirecek operasyonel koşulları belirleyin.</p>
        </div>

        <div class="bg-white rounded-[45px] shadow-xl shadow-purple-50/50 p-8 md:p-12 border border-gray-50">
            <form action="{{ route('operations.store') }}" method="POST" class="space-y-10">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-4">
                        <label class="flex items-center text-xs font-black uppercase text-gray-400 tracking-[0.2em] ml-2">
                            <i class="fas fa-project-diagram mr-2 text-purple-500"></i> İlgili Proje
                        </label>
                        <select name="project_id" required class="w-full bg-gray-50 border-2 border-transparent focus:border-purple-100 focus:bg-white rounded-[22px] py-5 px-7 outline-none font-bold text-[#2B3674] transition-all appearance-none cursor-pointer">
                            <option value="" disabled selected>Proje Seçiniz...</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-4">
                        <label class="flex items-center text-xs font-black uppercase text-gray-400 tracking-[0.2em] ml-2">
                            <i class="fas fa-tag mr-2 text-purple-500"></i> Operasyon Türü
                        </label>
                        <select name="type" required class="w-full bg-gray-50 border-2 border-transparent focus:border-purple-100 focus:bg-white rounded-[22px] py-5 px-7 outline-none font-bold text-[#2B3674] transition-all appearance-none cursor-pointer">
                            <option value="time">Süre Değişikliği (Zaman)</option>
                            <option value="budget">Bütçe Revizesi (Finans)</option>
                            <option value="resource">Kaynak Değişimi (İnsan Kaynağı)</option>
                            <option value="process">Süreç Değişikliği (Metodoloji)</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="flex items-center text-xs font-black uppercase text-gray-400 tracking-[0.2em] ml-2">
                        <i class="fas fa-chart-line mr-2 text-purple-500"></i> Etki Miktarı / Değişim Değeri
                    </label>
                    <div class="relative">
                        <input type="number" name="impact_value" step="0.01" required placeholder="Örn: 15 (Gün) veya 5000 (TL)" 
                               class="w-full bg-gray-50 border-2 border-transparent focus:border-purple-100 focus:bg-white rounded-[22px] py-5 px-7 outline-none font-bold text-[#2B3674] transition-all">
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-300 font-bold">
                            birim bazlı
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-400 ml-4 font-bold italic">* Negatif değerler azaltma, pozitif değerler artırma anlamına gelir.</p>
                </div>

                <div class="space-y-4">
                    <label class="flex items-center text-xs font-black uppercase text-gray-400 tracking-[0.2em] ml-2">
                        <i class="fas fa-pen-fancy mr-2 text-purple-500"></i> Operasyonel Gerekçe
                    </label>
                    <textarea name="description" rows="4" required placeholder="Bu operasyonun neden yapılması gerekiyor? Kayıt altına alınması zorunludur..." 
                              class="w-full bg-gray-50 border-2 border-transparent focus:border-purple-100 focus:bg-white rounded-[30px] py-5 px-7 outline-none font-medium text-[#2B3674] transition-all resize-none"></textarea>
                </div>

                <div class="pt-6 flex flex-col md:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-[#7E22CE] to-[#6366F1] text-white font-black py-5 rounded-[25px] shadow-xl shadow-purple-100 hover:shadow-purple-300 hover:-translate-y-1 transition-all uppercase tracking-widest text-sm">
                        Operasyon Talebini Yayınla
                    </button>
                    <a href="{{ route('operations.index') }}" class="px-10 py-5 bg-gray-100 text-gray-500 font-bold rounded-[25px] hover:bg-gray-200 transition-all text-center uppercase tracking-widest text-sm">
                        İptal
                    </a>
                </div>
            </form>
        </div>

        <div class="mt-10 p-6 bg-purple-50 rounded-[30px] border border-purple-100 flex items-start gap-4">
            <i class="fas fa-info-circle text-purple-500 mt-1"></i>
            <p class="text-xs text-purple-800 font-medium leading-relaxed">
                <strong>Unutmayın:</strong> Akademik kural gereği operasyonlar "iş yapmak" değildir; işin koşullarını değiştirmektir. 
                Gönderdiğiniz talep, yetkili makamlarca onaylanmadan finansal veya zamansal bir etki yaratmayacaktır.
            </p>
        </div>
    </div>

</body>
</html>