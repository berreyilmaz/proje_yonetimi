<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Proje Ekle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#F8F9FD] flex">

    <main class="flex-1 p-10">
        <div class="max-w-3xl mx-auto">
            <div class="mb-10 flex items-center gap-4">
                <a href="{{ route('projects.index') }}" class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-gray-400 hover:text-purple-600 shadow-sm transition-all">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-extrabold text-gray-900">Yeni Proje Oluştur</h1>
            </div>

            <form action="{{ route('projects.store') }}" method="POST" class="bg-white p-10 rounded-[40px] shadow-sm border border-gray-50 space-y-6">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 tracking-widest mb-3">Proje Başlığı</label>
                    <input type="text" name="title" placeholder="Örn: Mobil Uygulama Geliştirme" 
                           class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-purple-100 outline-none transition-all">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-widest mb-3">Durum</label>
                        <select name="status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-purple-100 outline-none appearance-none">
                            <option value="continuing">Devam Ediyor</option>
                            <option value="completed">Tamamlandı</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-widest mb-3">İlerleme (%)</label>
                        <input type="number" name="progress" min="0" max="100" placeholder="0" 
                               class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-purple-100 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 tracking-widest mb-3">Bitiş Tarihi</label>
                    <input type="date" name="end_date" 
                           class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-purple-100 outline-none">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-purple-600 text-white py-5 rounded-2xl font-bold shadow-lg shadow-purple-200 hover:bg-purple-700 transition-all">
                        Projeyi Kaydet
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>