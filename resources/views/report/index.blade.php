<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stratejik Raporlama Merkezi | {{ $project->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8F9FD; }
        .custom-shadow { box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05); }
        .gradient-purple { background: linear-gradient(135deg, #A855F7 0%, #7E22CE 100%); }
        .glass-card { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="p-8 md:p-12 text-gray-800">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
        <div class="flex items-center gap-6">
            <a href="{{ route('dashboard') }}" 
               class="group flex items-center justify-center w-14 h-14 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:bg-purple-600 transition-all duration-300">
                <i class="fas fa-chevron-left text-gray-400 group-hover:text-white transition-colors"></i>
            </a>
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Stratejik Raporlama Merkezi</h1>
                <p class="text-gray-400 text-[11px] font-bold uppercase tracking-[2px] mt-1 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    SİSTEM DOĞAL ÇIKTISI <span class="text-gray-200">|</span> {{ $project->title }}
                </p>
            </div>
        </div>
        
        <div class="flex gap-4 w-full md:w-auto">
            <button onclick="window.print()" class="flex-1 md:flex-none flex items-center justify-center gap-3 px-6 py-4 bg-white border border-gray-100 rounded-2xl text-gray-600 font-bold text-sm hover:bg-gray-50 transition-all custom-shadow">
                <i class="fas fa-print"></i> Yazdır
            </button>
            <button class="flex-1 md:flex-none flex items-center justify-center gap-3 px-8 py-4 bg-purple-600 rounded-2xl text-white font-bold text-sm shadow-xl shadow-purple-200 hover:scale-105 transition-all">
                <i class="fas fa-file-export"></i> Veriyi Dışa Aktar
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        <div class="bg-white p-8 rounded-[40px] custom-shadow border border-gray-50 group hover:border-purple-200 transition-all">
            <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-xl mb-6">
                <i class="fas fa-project-diagram"></i>
            </div>
            <span class="text-3xl font-black block mb-1 text-gray-800">{{ $projects->count() }}</span>
            <span class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Aktif Portföy</span>
        </div>

        <div class="bg-white p-8 rounded-[40px] custom-shadow border border-gray-50 group hover:border-blue-200 transition-all">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl mb-6">
                <i class="fas fa-tasks"></i>
            </div>
            <span class="text-3xl font-black block mb-1 text-gray-800">{{ $tasksCount }}</span>
            <span class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Operasyonel Çıktı</span>
        </div>

        <div class="bg-white p-8 rounded-[40px] custom-shadow border border-gray-50 group hover:border-orange-200 transition-all">
            <div class="w-14 h-14 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl mb-6">
                <i class="fas fa-chart-line"></i>
            </div>
            <span class="text-3xl font-black block mb-1 text-gray-800">%{{ number_format($efficiencyScore, 1) }}</span>
            <span class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Sistem Verimliliği</span>
        </div>

        <div class="bg-white p-8 rounded-[40px] custom-shadow border border-gray-50 group hover:border-green-200 transition-all">
            <div class="w-14 h-14 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-xl mb-6">
                <i class="fas fa-wallet"></i>
            </div>
            <span class="text-3xl font-black block mb-1 text-gray-800">{{ number_format($totalRevenue, 0, ',', '.') }} ₺</span>
            <span class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Toplam Finansal Hacim</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            
            <div class="bg-white p-10 rounded-[45px] custom-shadow border border-gray-50">
                <div class="flex justify-between items-center mb-10">
                    <div>
                        <h3 class="text-xl font-bold tracking-tight text-gray-800">Finans & Operasyon Dengesi</h3>
                        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mt-1">Veri Kaynağı: Muhasebe & Görev Takibi</p>
                    </div>
                    <div class="flex gap-4">
                        <span class="flex items-center gap-2 text-[10px] font-black text-purple-600"><i class="fas fa-circle text-[6px]"></i> PROJE GELİRİ</span>
                        <span class="flex items-center gap-2 text-[10px] font-black text-gray-300"><i class="fas fa-circle text-[6px]"></i> OPERASYONEL GİDER</span>
                    </div>
                </div>
                <div class="h-72">
                    <canvas id="strategicChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-[45px] custom-shadow border border-gray-50 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-xl font-bold tracking-tight text-gray-800">Proje Bazlı Performans Matrisi</h3>
                    <span class="text-[10px] font-black text-purple-600 bg-purple-50 px-4 py-2 rounded-xl uppercase">Canlı Analiz</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50">
                            <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                <th class="px-8 py-5">Proje Detayı</th>
                                <th class="px-8 py-5">İlerleme Durumu</th>
                                <th class="px-8 py-5">Finansal Değer</th>
                                <th class="px-8 py-5 text-right">Risk</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($projects as $p)
                            <tr class="hover:bg-gray-50/30 transition-all">
                                <td class="px-8 py-6">
                                    <div class="font-bold text-gray-700">{{ $p->title }}</div>
                                    <div class="text-[10px] text-gray-400 font-medium uppercase mt-0.5">Sorumlu: Atanmadı</div>
                                </td>
                                <td class="px-8 py-6 text-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 bg-gray-100 h-1.5 rounded-full min-w-[80px]">
                                            <div class="bg-purple-600 h-full rounded-full" style="width: {{ $p->progress }}%"></div>
                                        </div>
                                        <span class="text-xs font-black text-gray-600">%{{ $p->progress }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 font-bold text-gray-600 text-sm">
                                    {{ number_format($p->budget, 0, ',', '.') }} ₺
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <span class="px-3 py-1.5 {{ $p->progress > 50 ? 'bg-green-50 text-green-600' : 'bg-orange-50 text-orange-600' }} rounded-lg text-[10px] font-black uppercase tracking-tighter">
                                        {{ $p->progress > 50 ? 'GÜVENLİ' : 'TAKİP GEREK' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-span-1 space-y-8">
            
            <div class="bg-gray-900 p-10 rounded-[45px] text-white shadow-2xl relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-purple-600 rounded-xl flex items-center justify-center animate-pulse">
                            <i class="fas fa-brain text-sm"></i>
                        </div>
                        <h3 class="text-xl font-bold tracking-tight">Karar Destek Sistemi</h3>
                    </div>
                    
                    <p class="text-gray-400 text-sm leading-relaxed mb-8 font-medium">
                        Sistem verilerine göre; operasyonel hızınız geçen aya oranla <span class="text-green-400 font-bold">%14 arttı</span>. 
                        Finansal kayıtlar, AR-GE tabanlı projelerin bütçe verimliliğinde lider olduğunu gösteriyor.
                    </p>

                    <div class="space-y-4">
                        <div class="bg-white/5 border border-white/10 p-5 rounded-3xl">
                            <span class="text-[10px] font-black uppercase tracking-widest text-purple-400 block mb-2">Stratejik Öneri</span>
                            <p class="text-xs font-bold italic opacity-90">"Nakit akışını, ilerlemesi %40'ın altında kalan projelere kanalize edin."</p>
                        </div>
                        <button class="w-full py-4 bg-white/10 hover:bg-white/20 border border-white/10 rounded-2xl text-xs font-bold transition-all uppercase tracking-widest">
                            Yapay Zeka Analizi Çalıştır
                        </button>
                    </div>
                </div>
                <i class="fas fa-chart-pie absolute -right-16 -bottom-16 text-white/5 text-[220px]"></i>
            </div>

            <div class="bg-white p-10 rounded-[45px] custom-shadow border border-gray-50">
                <h3 class="text-lg font-bold mb-8 text-gray-800">Veri Besleme Kanalları</h3>
                <div class="space-y-6">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                        <div class="flex items-center gap-4">
                            <div class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.5)]"></div>
                            <span class="text-xs font-bold text-gray-600 uppercase tracking-widest">FİNANS MODÜLÜ</span>
                        </div>
                        <i class="fas fa-link text-gray-300 text-xs"></i>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                        <div class="flex items-center gap-4">
                            <div class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.5)]"></div>
                            <span class="text-xs font-bold text-gray-600 uppercase tracking-widest">GÖREV TAKİBİ</span>
                        </div>
                        <i class="fas fa-link text-gray-300 text-xs"></i>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                        <div class="flex items-center gap-4">
                            <div class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></div>
                            <span class="text-xs font-bold text-gray-600 uppercase tracking-widest">OPERASYON MERKEZİ</span>
                        </div>
                        <span class="text-[9px] font-black text-orange-500">AKTIĞI GİBİ...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('strategicChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz'],
                    datasets: [{
                        label: 'Gelir',
                        data: [45, 52, 48, 70, 65, 85],
                        borderColor: '#A855F7',
                        backgroundColor: 'rgba(168, 85, 247, 0.1)',
                        borderWidth: 4,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }, {
                        label: 'Gider',
                        data: [30, 35, 40, 38, 45, 42],
                        borderColor: '#E2E8F0',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        fill: false,
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { display: false },
                        x: { 
                            grid: { display: false },
                            ticks: { font: { size: 10, weight: 'bold' }, color: '#94a3b8' } 
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>