<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Finans - Yönetim Paneli</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#F8F9FD] flex">

    <main class="flex-1 p-10">
        <div class="mb-8">
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center gap-3 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-2xl font-bold">
                <div class="bg-white/20 rounded-lg p-1">
                    <i class="fas fa-arrow-left text-sm"></i>
                </div>
                <span class="text-sm">Ana Sayfaya Dön</span>
            </a>
        </div>

        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Finans</h1>
                <p class="text-gray-400 mt-1">Çalışanların haftalık çalışma saatleri, görevleri ve aldıkları ödemeler.</p>
            </div>
            {{-- İsteğe bağlı: yeni ödeme ekle butonu --}}
            <a href="{{ route('finans.create') }}" class="bg-purple-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg hover:bg-purple-700 transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i> Yeni Kayıt
            </a>
        </div>

        <div class="bg-white rounded-[40px] shadow-sm border border-gray-50 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="p-6 text-xs font-bold uppercase text-gray-400 tracking-widest">Çalışan</th>
                        <th class="p-6 text-xs font-bold uppercase text-gray-400 tracking-widest">Görev Sayısı</th>
                        <th class="p-6 text-xs font-bold uppercase text-gray-400 tracking-widest">Haftalık Çalışma</th>
                        <th class="p-6 text-xs font-bold uppercase text-gray-400 tracking-widest">Toplam Ödeme</th>
                        <th class="p-6 text-xs font-bold uppercase text-gray-400 tracking-widest text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($employees as $employee)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="p-6">
                            <div class="font-bold text-gray-800">{{ $employee->name }}</div>
                            @if(!empty($employee->roles) && $employee->roles->count())
                                <div class="text-xs text-gray-400 mt-1">{{ $employee->roles->pluck('name')->join(', ') }}</div>
                            @else
                                <div class="text-xs text-gray-400 mt-1">{{ $employee->email ?? '' }}</div>
                            @endif
                        </td>

                        <td class="p-6">
                            <a href="{{ route('tasks.index', ['assigned_to' => $employee->id]) }}"
                            title="Bu kullanıcıya atanan görevleri gör"
                            class="text-sm text-gray-700 font-bold hover:underline">
                                {{ $employee->tasks_count ?? ($employee->tasksAssigned->count() ?? 0) }}
                            </a>
                        </td>

                        <td class="p-6">
                            @php
                                $whText = $employee->weekly_hours_text ?? null;
                                if (!$whText) {
                                    $sec = (int) ($employee->weekly_work_hours ?? 0);
                                    $h = intdiv($sec, 3600);
                                    $m = intdiv($sec % 3600, 60);
                                    $whText = "{$h} sa {$m} dk";
                                }
                            @endphp
                            <span class="text-sm text-gray-700 font-bold">{{ $whText }}</span>
                        </td>

                        <td class="p-6">
                            @php
                                $paid = $employee->total_paid ?? ( method_exists($employee, 'finansPayments') ? $employee->finansPayments->sum('amount') : 0 );
                            @endphp
                            <span class="text-sm font-bold text-gray-700">{{ number_format((float)$paid, 2, ',', '.') }} ₺</span>
                        </td>

                        <td class="p-6 text-right">
                            <div class="flex justify-end gap-2">
                                {{-- Detay / Profil / Ödeme butonları --}}
                                <a href="{{ route('finans.show', $employee->id) }}" class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('users.edit', $employee->id) }}"
                                class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all"
                                title="Düzenle">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-20 text-center text-gray-400">
                            <i class="fas fa-wallet text-4xl mb-4 block opacity-20"></i>
                            Henüz finans verisi bulunmamaktadır.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>