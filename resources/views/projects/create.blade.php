<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Proje Ekle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* Tasarımına uyum sağlaması için özel CSS */
        .ts-control { 
            background-color: #F9FAFB !important; /* bg-gray-50 */
            border: none !important; 
            border-radius: 1rem !important; /* rounded-2xl */
            padding: 12px 18px !important;
        }
        .ts-dropdown { border-radius: 1rem !important; border: none !important; box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1); }
        .ts-control .item { 
            background: #F3E8FF !important; /* Purple-100 */
            color: #7E22CE !important; /* Purple-700 */
            border-radius: 8px !important;
            padding: 2px 10px !important;
            font-weight: 600;
        }
    </style>
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
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-widest mb-3">
                            Proje Yöneticisi
                        </label>
                        <select name="project_manager_id"
                                class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-purple-100 outline-none appearance-none">
                            <option value="">Seçilmedi</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-widest mb-3">Proje Üyeleri</label>
                        <select id="member-select" name="members[]" multiple autocomplete="off">
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
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
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    new TomSelect("#member-select",{
        plugins: ['remove_button'],
        placeholder: "Üye seçin veya arayın...",
        maxItems: 20,
        render: {
            option: function(data, escape) {
                return '<div class="py-2 px-3"><strong>' + escape(data.text) + '</strong></div>';
            }
        }
    });
</script>
</html>
