<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Proje Düzenle - {{ $project->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<style>
    /* Tasarıma uyum sağlayan özel stiller */
    .ts-control {
        background-color: #F9FAFB !important; /* bg-gray-50 */
        border: none !important;
        border-radius: 1rem !important; /* rounded-2xl */
        padding: 12px 24px !important;
        min-height: 56px;
    }
    .ts-wrapper.multi .ts-control > div {
        background: #F3E8FF !important; /* Purple-100 */
        color: #7E22CE !important; /* Purple-700 */
        border-radius: 12px !important;
        padding: 4px 12px !important;
        font-weight: 600;
        margin-right: 5px;
    }
    .ts-dropdown { 
        border-radius: 1rem !important; 
        border: none !important; 
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); 
    }
    .ts-control .item .remove { border-left: 1px solid #ddd !important; margin-left: 5px !important; }
</style>
    
</head>
<body class="bg-[#F8F9FD] flex">

    <main class="flex-1 p-10">
        @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        <div class="max-w-3xl mx-auto">
            <div class="mb-10 flex items-center gap-4">
                <a href="{{ route('projects.index') }}" class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-gray-400 hover:text-purple-600 shadow-sm transition-all">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-extrabold text-gray-900">Projeyi Düzenle</h1>
            </div>

            <form action="{{ route('projects.update', $project->id) }}" method="POST" class="bg-white p-10 rounded-[40px] shadow-sm border border-gray-50 space-y-6">
                @csrf
                @method('PUT') <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 tracking-widest mb-3">Proje Başlığı</label>
                    <input type="text" name="title" value="{{ $project->title }}" required
                           class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-purple-100 outline-none transition-all">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-widest mb-3">Durum</label>
                        <select name="status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-purple-100 outline-none appearance-none">
                            <option value="continuing" {{ $project->status == 'devam_ediyor' || $project->status == 'continuing' ? 'selected' : '' }}>Devam Ediyor</option>
                            <option value="completed" {{ $project->status == 'tamamlandi' || $project->status == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
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
                                <option value="{{ $employee->id }}"
                                    @selected($project->project_manager_id == $employee->id)>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-bold uppercase text-gray-400 tracking-widest mb-1">Proje Üyeleri</label>
                    <div class="relative">
                        <select id="member-select" name="members[]" multiple autocomplete="off" class="w-full">
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" 
                                    @selected(in_array($employee->id, $currentMembers ?? []))>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2 ml-1 italic font-medium uppercase tracking-tight">
                        <i class="fas fa-info-circle mr-1"></i> Mevcut üyeleri listeden görebilir veya yeni üyeler ekleyebilirsiniz.
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 tracking-widest mb-3">Bitiş Tarihi</label>
                    <input type="date" name="end_date" value="{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') : '' }}"
                           class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-purple-100 outline-none">
                </div>

                <div class="pt-4 flex gap-4">
                    <button type="submit" class="flex-1 bg-purple-600 text-white py-5 rounded-2xl font-bold shadow-lg shadow-purple-200 hover:bg-purple-700 transition-all">
                        Değişiklikleri Kaydet
                    </button>
                    <a href="{{ route('projects.index') }}" class="py-5 px-8 bg-gray-100 text-gray-500 rounded-2xl font-bold hover:bg-gray-200 transition-all text-center">
                        İptal
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var el = document.getElementById('member-select');
        var control = new TomSelect(el, {
            plugins: ['remove_button'],
            placeholder: "Üye seçin...",
            maxItems: 50,
        });

        // Form gönderilirken TomSelect'i senkronize et
        document.querySelector('form').addEventListener('submit', function() {
            control.sync();
        });
    });
</script>
</html>