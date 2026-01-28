
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Projeler - Yönetim Paneli</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#F8F9FD] flex">

    <main class="flex-1 p-10">
        <div class="mb-8">
            <a href="{{ route('dashboard') }}" 
            class="inline-flex items-center gap-3 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-purple-200 hover:shadow-purple-400 hover:-translate-x-1 transition-all duration-300 group">
                
                <div class="bg-white/20 rounded-lg p-1 group-hover:bg-white/30 transition-colors">
                    <i class="fas fa-arrow-left text-sm"></i>
                </div>
                
                <span class="text-sm tracking-wide">Ana Sayfaya Dön</span>
            </a>
        </div>
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Tüm Projeler</h1>
                <p class="text-gray-400 mt-1">Sistemdeki tüm kayıtlı projeleri yönetin.</p>
            </div>
            
            <a href="{{ route('projects.create') }}" class="bg-purple-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-purple-200 hover:bg-purple-700 transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i> Yeni Proje Ekle
            </a>
        </div>

        <div class="bg-white rounded-[40px] shadow-sm border border-gray-50 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="p-6 text-xs font-bold uppercase text-gray-400 tracking-widest">Proje Adı</th>
                        <th class="p-6 text-xs font-bold uppercase text-gray-400 tracking-widest">Durum</th>
                        <th class="p-6 text-xs font-bold uppercase text-gray-400 tracking-widest">İlerleme</th>
                        <th class="p-6 text-xs font-bold uppercase text-gray-400 tracking-widest text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($projects as $project)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="p-6">
                            <span class="font-bold text-gray-800">{{ $project->title }}</span>
                        </td>
                        <td class="p-6">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $project->status == 'completed' ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-600' }}">
                                {{ $project->status == 'completed' ? 'Tamamlandı' : 'Devam Ediyor' }}
                            </span>
                        </td>
                        <td class="p-6">
                            <div class="flex items-center gap-3">
                                <div class="w-24 bg-gray-100 rounded-full h-1.5">
                                    <div class="bg-purple-600 h-1.5 rounded-full" style="width: {{ $project->progress }}%"></div>
                                </div>
                                <span class="text-xs font-bold text-gray-500">%{{ $project->progress }}</span>
                            </div>
                        </td>
                        <td class="p-6 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('projects.edit', $project->id) }}" class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                
                                <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Bu projeyi silmek istediğinize emin misiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-20 text-center text-gray-400">
                            <i class="fas fa-folder-open text-4xl mb-4 block opacity-20"></i>
                            Henüz kayıtlı bir proje bulunmuyor.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>