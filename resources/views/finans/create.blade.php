<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Finans Kaydı</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#F8F9FD] flex">
<main class="flex-1 p-10">

  <div class="mb-6">
    <a href="{{ route('finans.index') }}" class="inline-flex items-center gap-3 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-2xl font-bold shadow-lg">
      <div class="bg-white/20 rounded-lg p-1"><i class="fas fa-arrow-left text-sm"></i></div>
      <span class="text-sm tracking-wide">Finans Listesine Dön</span>
    </a>
  </div>

  <div class="bg-white rounded-[20px] p-8 shadow-sm border border-gray-50 max-w-3xl">
    <h2 class="text-2xl font-extrabold mb-4">Yeni Finans Kaydı Oluştur</h2>

    {{-- Başarı / Hata Mesajları --}}
    @if(session('success'))
      <div class="mb-4 text-sm text-green-700 bg-green-50 p-3 rounded">
        {{ session('success') }}
      </div>
    @endif

    <form action="{{ route('finans.store') }}" method="POST" class="space-y-4">
      @csrf

      <label class="block">
        <span class="text-sm text-gray-600">Kullanıcı</span>
        <select name="user_id" class="w-full mt-2 p-3 border rounded" required>
          <option value="">Seçiniz</option>
          @foreach($users as $u)
            <option value="{{ $u->id }}" @if(optional($selectedUser)->id == $u->id) selected @endif>
              {{ $u->name }} — {{ $u->email }}
            </option>
          @endforeach
        </select>
        @error('user_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
      </label>

      <label class="block">
        <span class="text-sm text-gray-600">Tutar (₺)</span>
        <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" class="w-full mt-2 p-3 border rounded" required>
        @error('amount') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
      </label>

      <label class="block">
        <span class="text-sm text-gray-600">Açıklama</span>
        <textarea name="description" rows="3" class="w-full mt-2 p-3 border rounded">{{ old('description') }}</textarea>
        @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
      </label>

      <label class="block">
        <span class="text-sm text-gray-600">Tarih</span>
        <input type="date" name="date" value="{{ old('date') }}" class="w-full mt-2 p-3 border rounded">
        @error('date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
      </label>

      <div class="flex items-center gap-3 mt-4">
        <button type="submit" class="bg-purple-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg hover:bg-purple-700">Kaydet</button>
        <a href="{{ route('finans.index') }}" class="text-gray-500 hover:underline">İptal</a>
      </div>
    </form>

    
</body>
</html>