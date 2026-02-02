<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Düzenle - {{ $userToEdit->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F8F9FD] min-h-screen">

    <div class="p-10">
        <div class="max-w-[800px] mx-auto">
            
            <div class="mb-10">
                <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-gray-700 rounded-2xl shadow-sm border border-gray-100 hover:bg-gray-50 transition-all font-bold text-sm mb-8 group">
                    <i class="fas fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i>
                    Listeye Geri Dön
                </a>

                <h1 class="text-4xl font-extrabold text-[#1A1C21] tracking-tight">Personel Düzenle</h1>
                <p class="text-gray-400 mt-3 text-lg font-medium">
                    <span class="text-indigo-600 font-bold">{{ $userToEdit->name }}</span> kullanıcısının bilgilerini güncelliyorsunuz.
                </p>
            </div>

            <div class="bg-white rounded-[40px] shadow-sm border border-gray-50 p-10">
                <form action="{{ route('users.update', $userToEdit->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-8">
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-3 ml-1">Ad Soyad</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-6 flex items-center text-gray-400">
                                    <i class="far fa-user"></i>
                                </span>
                                <input type="text" name="name" value="{{ old('name', $userToEdit->name) }}" 
                                    class="w-full pl-14 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none transition-all font-medium text-gray-700"
                                    placeholder="Örn: Ahmet Yılmaz">
                            </div>
                            @error('name') <span class="text-red-500 text-xs mt-2 block ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-3 ml-1">E-Posta Adresi</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-6 flex items-center text-gray-400">
                                    <i class="far fa-envelope"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email', $userToEdit->email) }}" 
                                    class="w-full pl-14 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none transition-all font-medium text-gray-700"
                                    placeholder="ornek@yazilim.com">
                            </div>
                            @error('email') <span class="text-red-500 text-xs mt-2 block ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-3 ml-1">Yetki Grubu</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-6 flex items-center text-gray-400 z-10">
                                    <i class="fas fa-shield-alt"></i>
                                </span>
                                <select name="role" 
                                    class="w-full pl-14 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none transition-all font-medium text-gray-700 appearance-none cursor-pointer relative">
                                    <option value="">Rol Seçin</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role }}" {{ $userToEdit->hasRole($role) ? 'selected' : '' }}>
                                            {{ strtoupper($role) }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="absolute inset-y-0 right-6 flex items-center text-gray-400 pointer-events-none">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-50">
                            <label class="block text-sm font-bold text-gray-700 mb-3 ml-1">Şifreyi Güncelle (Opsiyonel)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-6 flex items-center text-gray-400">
                                    <i class="fas fa-key"></i>
                                </span>
                                <input type="password" name="password" 
                                    class="w-full pl-14 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none transition-all font-medium text-gray-700"
                                    placeholder="Değiştirmeyecekseniz boş bırakın">
                            </div>
                            @error('password') <span class="text-red-500 text-xs mt-2 block ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" 
                                class="w-full py-5 bg-[#6366f1] text-white rounded-3xl shadow-xl shadow-indigo-100 hover:bg-[#4f46e5] hover:-translate-y-1 transition-all font-bold text-lg flex items-center justify-center gap-3">
                                <i class="fas fa-check-circle"></i>
                                Değişiklikleri Kaydet
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>