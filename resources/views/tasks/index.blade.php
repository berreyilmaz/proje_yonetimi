<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Görevler - Yönetim Paneli</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#F8F9FD] flex">

    <main class="flex-1 p-10">
        {{-- Üst Navigasyon --}}
        <div class="mb-8">
            <a href="{{ route('dashboard') }}" 
            class="inline-flex items-center gap-3 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-purple-200 hover:shadow-purple-400 hover:-translate-x-1 transition-all duration-300 group">
                <div class="bg-white/20 rounded-lg p-1 group-hover:bg-white/30 transition-colors">
                    <i class="fas fa-arrow-left text-sm"></i>
                </div>
                <span class="text-sm tracking-wide">Ana Sayfaya Dön</span>
            </a>
        </div>

        {{-- Başlık ve Aksiyon Alanı --}}
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Tüm Görevler</h1>
                <p class="text-gray-400 mt-1">Operasyonel süreçleri ve iş atamalarını yönetin.</p>
            </div>
            
            @can('görev.ekle')
            <a href="{{ route('tasks.create') }}" class="bg-purple-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-purple-200 hover:bg-purple-700 transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i> Yeni Görev Ekle
            </a>
            @endcan
        </div>

        {{-- Görev Tablosu --}}
        <div class="bg-white rounded-[40px] shadow-sm border border-gray-50 overflow-hidden">
            <table class="w-full text-left">
            <table class="w-full text-left">
    <thead class="bg-gray-50 border-b border-gray-100">
        <tr>
            <th class="p-6 text-xs font-bold uppercase text-gray-400 tracking-widest">Görev Tanımı</th>
            <th class="p-6 text-xs font-bold uppercase text-gray-400 tracking-widest">Proje</th>
            <th class="p-6 text-xs font-bold uppercase text-gray-400 tracking-widest">Sorumlu</th>
            <th class="p-6 text-xs font-bold uppercase text-gray-400 tracking-widest">Durum</th>
            <th class="p-6 text-xs font-bold uppercase text-gray-400 tracking-widest">İşlemler</th>
        </tr>
    </thead>

    <tbody>
        @forelse($tasks as $task)
            <tr class="border-b border-gray-50 hover:bg-gray-50 transition-all">
                {{-- Görev Tanımı --}}
                <td class="p-6 text-sm font-semibold text-gray-800">
                    {{ $task->title }}
                </td>

                <td class="p-6 text-sm text-gray-600">
                    {{ $task->project?->title ?? 'Genel' }}
                </td>

                {{-- Sorumlu --}}
                <td class="p-6 text-sm text-gray-600">
                    {{ $task->assignedUser->name ?? '-' }}
                </td>

                {{-- Durum --}}
                <td class="p-6 text-sm">
                    {{ $task->status }}
                </td>

                {{-- İşlemler --}}
                <td class="p-6 text-sm">
                    <div class="flex justify-end items-center gap-3">
                        {{-- Düzenle --}}
                        <a href="{{ route('tasks.edit', $task) }}"
                        class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition"
                        title="Düzenle">
                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                        </a>

                        {{-- Sil --}}
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                            onsubmit="return confirm('Bu görevi silmek istediğinize emin misiniz?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-full bg-red-50 text-red-600 hover:bg-red-100 transition" title="Sil">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="p-6 text-center text-gray-400">
                    Henüz herhangi bir görev bulunmuyor.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>