<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->name }} | Finansal Analiz</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #F8FAFC; /* Görseldeki hafif gri-beyaz arka plan */
        }
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
        }
        .purple-gradient {
            background: linear-gradient(135deg, #7C3AED 0%, #6366F1 100%);
        }
    </style>
</head>
<body class="antialiased text-slate-900">

    <div class="max-w-[1440px] mx-auto p-6 lg:p-10">
        
        <div class="mb-8">
            <a href="/finans" class="inline-flex items-center gap-3 px-5 py-3 purple-gradient text-white rounded-2xl hover:opacity-90 transition-all shadow-lg shadow-purple-200 font-bold text-sm">
                <i class="fas fa-arrow-left"></i>
                Ana Sayfaya Dön
            </a>
        </div>

        <header class="mb-10">
            <h1 class="text-4xl font-extrabold text-[#0F172A] tracking-tight mb-2">
                {{ $project->name }}
            </h1>
            <p class="text-slate-400 font-medium">
                Operasyonların parasal sonuç kaydı ve bütçe gerçekleşme analizi.
            </p>
        </header>

        <div class="grid grid-cols-12 gap-8">
            
            <div class="col-span-12 lg:col-span-4 space-y-6">
                
                <div class="bg-white rounded-[2rem] p-8 card-shadow border border-slate-50 relative overflow-hidden">
                    <div class="flex justify-between items-start mb-10">
                        <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                            Bütçe İçi
                        </span>
                    </div>

                    <h3 class="text-slate-400 text-[11px] font-bold uppercase tracking-widest mb-1 italic">Planlanan Bütçe</h3>
                    <div class="text-3xl font-black text-slate-800 mb-8 tracking-tighter">
                        {{ number_format($project->budget, 0, ',', '.') }} ₺
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between text-xs font-bold text-slate-500 uppercase tracking-tighter">
                            <span>Gerçekleşen Harcama</span>
                            @php
                                $totalExpense = $project->financialTransactions->where('type', 'expense')->sum('amount');
                                $percent = $project->budget > 0 ? min(100, ($totalExpense / $project->budget) * 100) : 0;
                            @endphp
                            <span class="text-purple-600">%{{ round($percent) }}</span>
                        </div>
                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full purple-gradient transition-all duration-1000" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-50/50 border border-indigo-100 p-6 rounded-[2rem]">
                    <h4 class="text-indigo-900 text-xs font-bold uppercase mb-2 flex items-center gap-2">
                        <i class="fas fa-info-circle"></i> Sistem Notu
                    </h4>
                    <p class="text-[11px] text-indigo-700 leading-relaxed font-medium">
                        Bu sistem bir muhasebe altyapısı değildir. Finansal veriler, operasyonel kararların izlenebilirliği için kaydedilir.
                    </p>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-8">
                <div class="bg-white rounded-[2rem] card-shadow border border-slate-50 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-50 bg-white">
                        <h2 class="text-sm font-black text-slate-400 uppercase tracking-[0.2em]">Operasyon Referanslı İşlemler</h2>
                    </div>

                    <div class="overflow-x-auto p-4">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-slate-300 text-[10px] font-bold uppercase tracking-widest">
                                    <th class="px-6 py-4">İşlem / Tarih</th>
                                    <th class="px-6 py-4">Operasyon Kaynağı</th>
                                    <th class="px-6 py-4 text-right">Tutar</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($transactions as $transaction)
                                <tr class="hover:bg-slate-50 transition-all group">
                                    <td class="px-6 py-6">
                                        <div class="font-bold text-slate-800 text-sm tracking-tight group-hover:text-purple-600 transition-colors">
                                            {{ $transaction->title }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-bold mt-1 uppercase tracking-tighter">
                                            {{ \Carbon\Carbon::parse($transaction->transaction_date)->translatedFormat('d F Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-6">
                                        @if($transaction->reference_type == 'App\Models\User')
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-[10px] font-bold">
                                                    {{ strtoupper(substr($transaction->referencable->name ?? 'P', 0, 2)) }}
                                                </div>
                                                <span class="text-xs font-bold text-slate-600">{{ $transaction->referencable->name ?? 'Personel' }}</span>
                                            </div>
                                        @else
                                            <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-3 py-1 rounded-lg uppercase tracking-widest">Genel Gider</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-6 text-right">
                                        <div class="font-black text-sm tracking-tighter {{ $transaction->type == 'income' ? 'text-emerald-500' : 'text-rose-500' }}">
                                            {{ $transaction->type == 'income' ? '+' : '-' }}{{ number_format($transaction->amount, 2, ',', '.') }} ₺
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="py-20 text-center">
                                        <p class="text-slate-400 font-bold text-xs uppercase tracking-widest italic">Henüz bu proje için bir operasyonel çıktı oluşmadı.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>