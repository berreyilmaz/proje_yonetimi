<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <title>Yeni Kullanıcı - Şirket Yönetimi</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#F8F9FD]">
  <main class="max-w-3xl mx-auto p-10">
    <div class="mb-6">
      <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-3 px-4 py-2 bg-white rounded shadow">
        <i class="fas fa-arrow-left"></i> Geri
      </a>
    </div>

    <div class="bg-white p-8 rounded-[20px] shadow-sm border border-gray-50">
      <h1 class="text-2xl font-bold mb-4">Yeni Kullanıcı Oluştur</h1>

      @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 text-green-700 rounded">{{ session('success') }}</div>
      @endif
      @if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 text-red-600 rounded-xl border border-red-100 text-sm">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
      <form action="{{ route('yenikullanici.store') }}" method="POST" class="space-y-4">
        @csrf
        <input type="text" name="name" placeholder="Ad Soyad" required class="w-full p-3 border rounded" />
        <input type="email" name="email" placeholder="Email" required class="w-full p-3 border rounded" />
        <input type="password" name="password" placeholder="Şifre" required class="w-full p-3 border rounded" />
        <input type="password" name="password_confirmation" placeholder="Şifre (Tekrar)" required class="w-full p-3 border rounded" />
        <select name="role" class="w-full p-3 border rounded">
          <option value="">Rol seç (opsiyonel)</option>
          @foreach($roles as $r)
            <option value="{{ $r }}">{{ $r }}</option>
          @endforeach
        </select>

        <div class="flex gap-2">
          <button class="bg-green-600 text-white px-4 py-2 rounded">Oluştur</button>
          <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded bg-gray-100">İptal</a>
        </div>
      </form>
    </div>
  </main>
</body>
</html>