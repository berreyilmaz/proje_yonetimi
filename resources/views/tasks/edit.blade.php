<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Görev Düzenle - Yönetim Paneli</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#F8F9FD] flex">

    <main class="flex-1 p-10 flex justify-center">
        <div class="w-full max-w-2xl">
            {{-- Geri Dön Butonu --}}
            <div class="mb-8">
                <a href="{{ route('tasks.index') }}" 
                   class="inline-flex items-center gap-3 px-5 py-2.5 bg-white text-gray-600 rounded-2xl font-bold shadow-sm border border-gray-100 hover:shadow-md hover:-translate-x-1 transition-all duration-300 group">
                    <i class="fas fa-arrow-left text-sm"></i>
                    <span class="text-sm">Listeye Dön</span>
                </a>
            </div>

            <div class="bg-white rounded-[40px] shadow-sm border border-gray-50 p-10">
                <div class="mb-10">
                    <h1 class="text-3xl font-extrabold text-gray-900">Görevi Düzenle</h1>
                    <p class="text-gray-400 mt-1">Mevcut görev bilgilerini güncelleyin.</p>
                </div>

                <form action="{{ route('tasks.update', $task) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Görev Başlığı --}}
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-widest mb-2 ml-1">Görev Başlığı</label>
                        <input type="text" name="title" required
                               value="{{ old('title', $task->title) }}"
                               class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-purple-100 focus:border-purple-600 outline-none transition-all font-medium">
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        {{-- İlgili Proje --}}
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-400 tracking-widest mb-2 ml-1">İlgili Proje</label>
                            <select name="project_id"
                                    class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:border-purple-600 transition-all font-medium appearance-none">
                                <option value="">Genel (Proje Bağımsız)</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}"
                                        @selected(old('project_id', $task->project_id) == $project->id)>
                                        {{ $project->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Sorumlu Kişi --}}
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-400 tracking-widest mb-2 ml-1">Sorumlu Kişi</label>
                            <select name="assigned_to" required
                                    class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:border-purple-600 transition-all font-medium appearance-none">
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        @selected(old('assigned_to', $task->assigned_to) == $employee->id)>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Durum Seçimi --}}
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 tracking-widest mb-2 ml-1">Durum</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="beklemede" class="peer hidden"
                                       @checked(old('status', $task->status) == 'beklemede')>
                                <div class="p-4 text-center rounded-2xl bg-gray-50 border border-gray-100 peer-checked:bg-purple-50 peer-checked:border-purple-600 peer-checked:text-purple-600 transition-all font-bold text-sm">
                                    Beklemede
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="devam_ediyor" class="peer hidden"
                                       @checked(old('status', $task->status) == 'devam_ediyor')>
                                <div class="p-4 text-center rounded-2xl bg-gray-50 border border-gray-100 peer-checked:bg-blue-50 peer-checked:border-blue-600 peer-checked:text-blue-600 transition-all font-bold text-sm">
                                    Devam Ediyor
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="tamamlandi" class="peer hidden"
                                       @checked(old('status', $task->status) == 'tamamlandi')>
                                <div class="p-4 text-center rounded-2xl bg-gray-50 border border-gray-100 peer-checked:bg-green-50 peer-checked:border-green-600 peer-checked:text-green-600 transition-all font-bold text-sm">
                                    Tamamlandı
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Güncelle Butonu --}}
                    <button type="submit"
                            class="w-full py-5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-2xl font-extrabold shadow-xl shadow-purple-200 hover:shadow-purple-400 hover:-translate-y-1 transition-all duration-300 mt-4">
                        Görevi Güncelle
                    </button>

                </form>
            </div>
        </div>
    </main>

</body>
</html>