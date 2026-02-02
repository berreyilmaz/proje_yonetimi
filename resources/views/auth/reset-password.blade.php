<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Şifre Belirle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F8F9FD] min-h-screen flex items-center justify-center p-6">

    <div class="max-w-[450px] w-full bg-white rounded-[40px] shadow-sm border border-gray-50 p-10 text-center">
        
        <div class="mb-8">
            <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-3xl flex items-center justify-center mx-auto mb-6 text-3xl">
                <i class="fas fa-lock-open"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-[#1A1C21] tracking-tight">Yeni Şifre</h1>
            <p class="text-gray-400 mt-2 font-medium">Lütfen yeni ve güçlü bir şifre belirleyin.</p>
        </div>

        <form action="{{ route('password.update') }}" method="POST" class="text-left">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="grid grid-cols-1 gap-6">
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Yeni Şifre</label>
                    <input type="password" name="password" required
                        class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none transition-all font-medium text-gray-700">
                    @error('password') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Şifre Tekrar</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none transition-all font-medium text-gray-700">
                </div>

                <button type="submit" 
                    class="w-full py-5 bg-[#6366f1] text-white rounded-3xl shadow-xl shadow-indigo-100 hover:bg-[#4f46e5] hover:-translate-y-1 transition-all font-bold text-lg flex items-center justify-center gap-3 mt-4">
                    Şifreyi Güncelle
                </button>
            </div>
        </form>
    </div>

</body>
</html>