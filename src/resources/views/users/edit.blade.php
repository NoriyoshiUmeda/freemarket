<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>プロフィール設定</title>
  <link rel="stylesheet" href="{{ asset('css/users/edit.css') }}">
</head>
<body>
    @include('layouts.newapp')
  <!-- メインコンテナ -->
  <div class="container">

    @if (session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif


    <h2>プロフィール設定</h2>
    
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      
     <div class="profile-image-wrapper">
  
  <!-- 画像を丸く表示する枠 -->
  <div class="profile-image-container">
    @php
      // ユーザーのprofile_imageがあるかチェック
      $hasProfileImage = optional(auth()->user()->profile)->profile_image;
    @endphp

    @if ($hasProfileImage)
      <!-- 画像がある場合はその画像を表示 -->
      <img src="{{ asset('storage/' . $hasProfileImage) }}" class="profile-image">
    @else
      <!-- 画像がない場合は灰色の丸を表示 -->
      <div class="profile-image no-image"></div>
    @endif
  </div>
  <!-- 画像アップロードボタン(ラベル) -->
  <label class="file-label">
    <input type="file" name="profile_image" id="profile_image" class="hidden">
    画像を選択する
  </label>
     @error('profile_image')
    <div class="error">{{ $message }}</div>
     @enderror
</div>


      <!-- ユーザー名 -->
      <div class="form-group">
        <label for="name">ユーザー名</label>
        <input type="text" name="name" id="name"
               value="{{ old('name', auth()->user()->name) }}">
        @error('name')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>
      
      <!-- 郵便番号 -->
      <div class="form-group">
        <label for="postal_code">郵便番号</label>
        <input type="text" name="postal_code" id="postal_code"
               value="{{ old('postal_code', optional(auth()->user()->profile)->postal_code) }}">
        @error('postal_code')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>
      
      <!-- 住所 -->
      <div class="form-group">
        <label for="address">住所</label>
        <input type="text" name="address" id="address"
               value="{{ old('address', optional(auth()->user()->profile)->address) }}">
        @error('address')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>
      
      <!-- 建物名 (任意ならこのままでもOK) -->
      <div class="form-group">
        <label for="building">建物名</label>
        <input type="text" name="building" id="building"
               value="{{ old('building', optional(auth()->user()->profile)->building) }}">
        @error('building')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>
      
      <!-- 更新ボタン -->
      <button type="submit" class="submit-btn">更新する</button>
    </form>
  </div>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('profile_image');
    const preview = document.getElementById('preview-image');

    input.addEventListener('change', function () {
      const file = input.files[0];
      if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (e) {
          if (preview) {
            preview.src = e.target.result;
          } else {
            // 画像タグがまだない場合は作って差し込む
            const img = document.createElement('img');
            img.src = e.target.result;
            img.id = 'preview-image';
            img.classList.add('profile-image');
            document.querySelector('.profile-image-container').innerHTML = '';
            document.querySelector('.profile-image-container').appendChild(img);
          }
        };
        reader.readAsDataURL(file);
      }
    });
  });
</script>

</body>
</html>
