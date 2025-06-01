@extends('layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('css/edit_profile.css') }}">
@endsection
@section('content')
    <form method="POST" action="{{ route('edit_profile') }}" class="content" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <h1 class="title">Редактирование профиля</h1>
        <div class="image-upload">
            <img id="preview" src="{{ asset('storage/avatars/' . auth()->user()->avatar) }}" alt="Фото">
            <input type="file" id="avatar" name="avatar">
        </div>
        @error('avatar')
        {{ $message }}
        @enderror
        <div class="input">
            <div class="group">
                <label for="name">Имя</label>
                <input type="text" placeholder="" value="{{ old('name', auth()->user()->name) }}" id="name" name="name">
            </div>
            <div class="group">
                <label for="current_password">Старый пароль</label>
                <input type="password" id="current_password" name="current_password">
            </div>
            <div class="group">
                <label for="vk_link">Соц. сети</label>
                <div class="social">
                    <label for="vk_link">Вк-</label>
                    <input type="text" placeholder="" id="vk_link" name="vk_link"
                        value="{{ old('vk_link', auth()->user()->vk_link) }}">
                    <label for="tg_link">Тг-</label>
                    <input type="text" placeholder="" id="tg_link" name="tg_link"
                        value="{{ old('tg_link', auth()->user()->tg_link) }}">
                </div>
            </div>
            <div class="group">
                <label for="new_password">Новый пароль</label>
                <input type="password" placeholder="" id="new_password" name="new_password">
            </div>
            <div class="group about">
                <label for="about">О себе</label>
                <textarea name="about" id="about">{{ old('about', auth()->user()->about) }}</textarea>
            </div>
            <button type="submit" class="form_btn">
                Сохранить изменения
            </button>
        </div>


    </form>

    <script>
        const fileInput = document.getElementById('avatar');
        const preview = document.getElementById('preview');

        fileInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    preview.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection