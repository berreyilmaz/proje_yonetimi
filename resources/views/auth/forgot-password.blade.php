<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifremi Unuttum - Proje Yönetimi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F8F9FD] min-h-screen flex items-center justify-center p-6">

    <div class="max-w-[480px] w-full">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-[#6366f1] transition-colors font-bold text-sm mb-8 group">
            <i class="fas fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i>
            Giriş Ekranına Dön
        </a>

        <div class="bg-white rounded-[40px] shadow-sm border border-gray-50 p-10">
            
            <div class="mb-10 text-center">
                <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-3xl flex items-center justify-center mx-auto mb-6 text-3xl">
                    <i class="fas fa-key"></i>
                </div>
                <h1 class="text-3xl font-extrabold text-[#1A1C21] tracking-tight">Şifrenizi mi Unuttunuz?</h1>
                <p class="text-gray-400 mt-3 text-lg font-medium leading-relaxed">
                    E-posta adresinizi girin, size şifre sıfırlama linkini gönderelim.
                </p>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-600 rounded-2xl text-sm font-bold flex items-center gap-3">
                    <i class="fas fa-check-circle"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-6">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3 ml-1">Kayıtlı E-Posta</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-6 flex items-center text-gray-400">
                                <i class="far fa-envelope"></i>
                            </span>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full pl-14 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none transition-all font-medium text-gray-700"
                                placeholder="ornek@yazilim.com">
                        </div>
                        @error('email') 
                            <span class="text-red-500 text-xs mt-2 block ml-1 font-semibold">{{ $message }}</span> 
                        @enderror
                    </div>

                    <button type="submit" 
                        class="w-full py-5 bg-[#6366f1] text-white rounded-3xl shadow-xl shadow-indigo-100 hover:bg-[#4f46e5] hover:-translate-y-1 transition-all font-bold text-lg flex items-center justify-center gap-3 mt-4 group">
                        <span>Sıfırlama Linki Gönder</span>
                        <i class="fas fa-paper-plane text-sm transition-transform group-hover:translate-x-1 group-hover:-translate-y-1"></i>
                    </button>
                </div>
            </form>
        </div>
        
        <p class="text-center mt-10 text-gray-400 font-medium text-sm">
            Yardıma mı ihtiyacınız var? <a href="#" class="text-indigo-600 hover:underline">Destek ekibiyle iletişime geçin.</a>
        </p>
    </div>

</body>
</html>