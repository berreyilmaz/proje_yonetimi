<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finansal Operasyon Merkezi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8F9FD;
            scroll-behavior: smooth;
        }

        .custom-shadow {
            box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.05);
        }

        .purple-gradient {
            background: linear-gradient(135deg, #7E22CE 0%, #4F46E5 100%);
        }

        .modal-animate {
            animation: modalIn 0.3s ease-out;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    </style>
</head>

<body class="p-4 md:p-10">

    <div class="max-w-[1600px] mx-auto">
        <div class="mb-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm hover:bg-purple-600 hover:text-white transition-all">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-gray-900">Finans Merkezi</h1>
                    <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mt-1">Gerçek Zamanlı Maliyet ve Kar Analizi</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="bg-white px-8 py-4 rounded-[25px] border border-gray-100 shadow-sm">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Kümülatif Bütçe</p>
                    <p class="text-xl font-black text-purple-600">{{ number_format($projects->sum('budget'), 0, ',', '.') }} ₺</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[40px] border border-purple-100 shadow-sm mb-12 relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-lg font-black mb-6 flex items-center gap-3"><i class="fas fa-edit text-purple-600"></i> Proje Bütçe Ataması</h2>
                <form action="{{ route('projects.updateBatchBudget') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @csrf
                    <select name="project_id" class="px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-sm outline-none focus:ring-2 focus:ring-purple-500">
                        @foreach($projects as $p) <option value="{{ $p->id }}">{{ $p->title }}</option> @endforeach
                    </select>
                    <input type="number" name="budget" placeholder="Yeni Bütçe (₺)" class="px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-sm outline-none focus:ring-2 focus:ring-purple-500">
                    <input type="number" name="reserve_margin" placeholder="Yedek Akçe %" class="px-5 py-4 bg-gray-50 border-none rounded-2xl font-bold text-sm outline-none focus:ring-2 focus:ring-purple-500">
                    <button type="submit" class="purple-gradient text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-105 transition-all shadow-lg">GÜNCELLE</button>
                </form>
            </div>
        </div>

        <h2 class="text-xs font-black text-gray-400 uppercase tracking-[3px] mb-6 ml-4">Aktif Projeler (Detay için tıkla)</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            @foreach($projects as $project)
            <div onclick="openProjectDetails({{ json_encode($project) }})"
                class="group bg-white p-8 rounded-[45px] border border-gray-50 custom-shadow cursor-pointer hover:border-purple-400 transition-all relative">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl group-hover:bg-purple-600 group-hover:text-white transition-all">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="text-right">
                        @php $usage = $project->budget > 0 ? ($project->total_expense / $project->budget) * 100 : 0; @endphp
                        <span class="text-[10px] font-black px-3 py-1 rounded-lg uppercase {{ $usage > 90 ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }}">
                            %{{ number_format($usage, 1) }} Doluluk
                        </span>
                    </div>
                </div>
                <h3 class="text-xl font-black text-gray-800 mb-2">{{ $project->title }}</h3>
                <div class="flex items-center gap-2 mb-6">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Bütçe:</span>
                    <span class="text-xs font-black text-gray-700">{{ number_format($project->budget, 0, ',', '.') }} ₺</span>
                </div>
                <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
                    <div class="h-full bg-purple-600 transition-all duration-1000" style="width: {{ min($usage, 100) }}%"></div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="bg-white rounded-[45px] border border-gray-50 custom-shadow overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-black text-gray-800">Personel Gider Dökümü</h3>
                <i class="fas fa-users text-gray-200 text-2xl"></i>
            </div>
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest bg-gray-50/50">
                        <th class="px-10 py-5">Çalışan</th>
                        <th class="px-10 py-5">Haftalık Efor</th>
                        <th class="px-10 py-5">Maliyet (₺)</th>
                        <th class="px-10 py-5 text-right">İşlem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($employees as $e)
                    <tr class="hover:bg-gray-50 transition-all">
                        <td class="px-10 py-6 font-bold text-gray-800">{{ $e->name }}</td>
                        <td class="px-10 py-6 text-sm text-gray-500 font-medium">
                            {{ intdiv($e->weekly_work_hours ?? 0, 3600) }} sa
                        </td>
                        <td class="px-10 py-6 font-black text-gray-800">
                            {{ number_format($e->total_paid ?? 0, 0, ',', '.') }} ₺
                        </td>
                        <td class="px-10 py-6 text-right">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('finans.show', $e->id) }}" class="text-gray-400 hover:text-purple-600 transition-colors p-2" title="Bordro Görüntüle">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </a>

                                <a href="{{ route('finans.edit', $e->id) }}"
                                    class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all shadow-sm shadow-indigo-100"
                                    title="Finansal Ayarları Düzenle">
                                    <i class="fas fa-user-cog text-sm"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="projectModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm shadow-2xl" onclick="closeModal()"></div>
        <div class="relative bg-white w-full max-w-4xl rounded-[50px] shadow-2xl overflow-hidden modal-animate">
            <div class="p-10 md:p-14">
                <div class="flex justify-between items-start mb-12">
                    <div>
                        <h2 id="mTitle" class="text-3xl font-black text-gray-900">Proje Adı</h2>
                        <span class="inline-block mt-2 px-4 py-1.5 bg-purple-50 text-purple-600 rounded-xl text-[10px] font-black uppercase tracking-widest">Finansal Derin Analiz</span>
                    </div>
                    <button onclick="closeModal()" class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition-all">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                    <div class="bg-green-50 p-7 rounded-[35px] border border-green-100">
                        <p class="text-[10px] font-black text-green-600 uppercase mb-2">Toplam Gelir</p>
                        <p id="mBudget" class="text-2xl font-black text-green-800">0 ₺</p>
                    </div>
                    <div class="bg-red-50 p-7 rounded-[35px] border border-red-100">
                        <p class="text-[10px] font-black text-red-600 uppercase mb-2">Toplam Gider</p>
                        <p id="mExpense" class="text-2xl font-black text-red-800">0 ₺</p>
                    </div>
                    <div class="bg-gray-900 p-7 rounded-[35px] shadow-xl shadow-gray-200">
                        <p class="text-[10px] font-black text-purple-400 uppercase mb-2">Net Kar Durumu</p>
                        <p id="mProfit" class="text-2xl font-black text-white">0 ₺</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-[40px] p-8">
                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Operasyonel Gider Dağılımı</h4>
                    <div id="mStaffList" class="space-y-4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openProjectDetails(project) {
            const modal = document.getElementById('projectModal');
            document.getElementById('mTitle').innerText = project.title;

            const budget = parseFloat(project.budget);
            const expense = parseFloat(project.total_expense);
            const profit = budget - expense;

            document.getElementById('mBudget').innerText = new Intl.NumberFormat('tr-TR').format(budget) + ' ₺';
            document.getElementById('mExpense').innerText = new Intl.NumberFormat('tr-TR').format(expense) + ' ₺';
            document.getElementById('mProfit').innerText = new Intl.NumberFormat('tr-TR').format(profit) + ' ₺';

            // Basit bir maliyet kırılımı simülasyonu
            const list = document.getElementById('mStaffList');
            list.innerHTML = `
                <div class="flex justify-between items-center bg-white p-4 rounded-2xl border border-gray-100">
                    <span class="text-sm font-bold text-gray-600">Personel Maaş & Yan Haklar</span>
                    <span class="text-sm font-black text-red-500">${new Intl.NumberFormat('tr-TR').format(expense)} ₺</span>
                </div>
                <div class="flex justify-between items-center bg-white p-4 rounded-2xl border border-gray-100 opacity-50">
                    <span class="text-sm font-bold text-gray-600">Genel Şirket Payı (Sabit)</span>
                    <span class="text-sm font-black text-gray-400">0 ₺</span>
                </div>
            `;

            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('projectModal').classList.add('hidden');
        }
    </script>
</body>

</html>