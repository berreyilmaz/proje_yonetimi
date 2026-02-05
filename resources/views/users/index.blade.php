<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Yönetimi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#F8F9FD] min-h-screen">

    <div class="p-10">
        <div class="max-w-[1400px] mx-auto">
            
            <div class="mb-10 flex items-end">
                <div>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#6366f1] text-white rounded-2xl shadow-lg shadow-indigo-100 hover:bg-[#4f46e5] transition-all font-bold text-sm mb-8">
                        <i class="fas fa-arrow-left text-xs"></i>
                        Ana Sayfaya Dön
                    </a>

                    <h1 class="text-4xl font-extrabold text-[#1A1C21] tracking-tight">Tüm Kullanıcılar</h1>
                    <p class="text-gray-400 mt-3 text-lg font-medium">Sistemdeki personellerin yetki ve bilgilerini yönetin.</p>
                </div>

                <div class="ml-auto">
                    @can('kullanici.ekle')
                    <a href="{{ route('yenikullanici.create') }}" class="group inline-flex items-center gap-3 bg-purple-600 text-white px-8 py-4 rounded-[24px] font-bold shadow-xl shadow-purple-100 hover:bg-purple-700 hover:-translate-y-1 transition-all whitespace-nowrap">
                        <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center group-hover:rotate-90 transition-all duration-300">
                            <i class="fas fa-plus text-sm"></i>
                        </div>
                        <span class="text-lg">Yeni Kullanıcı Ekle</span>
                    </a>
                    @endcan
                </div>
            </div>


            <div class="bg-white rounded-[40px] shadow-sm border border-gray-50 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-gray-400 text-[11px] uppercase tracking-[0.2em] bg-gray-50/40 border-b border-gray-50">
                                <th class="px-12 py-8 font-bold">Personel</th>
                                <th class="px-12 py-8 font-bold">E-Posta</th>
                                <th class="px-12 py-8 font-bold">Rol / Yetki</th>
                                <th class="px-12 py-8 font-bold text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-50/30 transition-all group">
                                <td class="px-12 py-7">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center font-bold text-base border border-indigo-100 uppercase">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <span class="font-bold text-[#1A1C21] text-lg">{{ $user->name }}</span>
                                    </div>
                                </td>

                                <td class="px-12 py-7 text-gray-500 font-medium">
                                    {{ $user->email }}
                                </td>

                                <td class="px-12 py-7">
                                    <span class="inline-flex items-center px-4 py-1.5 bg-gray-100 text-gray-600 rounded-xl text-[10px] font-bold uppercase tracking-wider">
                                        {{ $user->roles->pluck('name')->first() ?? 'Kullanıcı' }}
                                    </span>
                                </td>

                                <td class="px-12 py-7 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('users.edit', $user->id) }}" 
                                        class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <button type="button" 
                                                onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}')" 
                                                class="text-red-500 hover:text-red-700 transition-colors">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>

                                        <form id="delete-form-{{ $user->id }}" 
                                            action="{{ route('users.destroy', $user->id) }}" 
                                            method="POST" 
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

<script>
    function confirmDelete(userId, userName) {
        if (confirm(userName + ' isimli kullanıcıyı silmek istediğinize emin misiniz? Bu işlem geri alınamaz.')) {
            document.getElementById('delete-form-' + userId).submit();
        }
    }
</script>