<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proje Paneli | Giriş</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100">
        <div class="p-8">
            <div class="text-center mb-10">
                <div class="bg-blue-600 w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-200">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">Tekrar Hoş Geldiniz</h2>
                <p class="text-slate-500 mt-2 text-sm">Lütfen hesap bilgilerinizle giriş yapın</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">E-posta Adresi</label>
                    <input type="email" name="email" required 
                        class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all placeholder:text-slate-400"
                        placeholder="ornek@sirket.com">
                </div>

                <div>
                    <div class="flex justify-between mb-2">
                        <label class="text-sm font-semibold text-slate-700">Şifre</label>
                    </div>
                    <input type="password" name="password" required 
                    class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all placeholder:text-slate-400"
                    placeholder="••••••••"> 
                    
                    <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:underline">Şifremi Unuttum</a>
                </div>

                @if($errors->any())
                <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm border border-red-100">
                    {{ $errors->first() }}
                </div>
                @endif

                <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-lg shadow-blue-200 transition-all transform active:scale-[0.98]">
                    Giriş Yap
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-100 text-center text-sm text-slate-500">
                Sistemle ilgili sorun mu yaşıyorsunuz? <a href="#" class="text-blue-600 font-medium">Destek Alın</a>
            </div>
        </div>
    </div>

</body>
</html>