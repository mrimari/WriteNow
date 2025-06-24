@extends('layouts.app')

@section('content')
<div class="banned-container" style="z-index: 9999; max-width: 500px; margin: 60px auto 100px auto; background: #fff; border: 2px solid #975a1c; border-radius: 18px; box-shadow: 0 2px 16px rgba(151,90,28,0.08); padding: 48px 32px; text-align: center;">
    <img src="{{ asset('images/banban.jpg') }}" alt="Забанен" style="border-radius: 50%; width: 200px; margin-bottom: 18px; opacity: 0.7;">
    <h1 style="color: #975a1c; font-size: 2em; margin-bottom: 18px; font-weight: bold; letter-spacing: 1px;">Вы забанены</h1>
    <p style="font-size: 1.1em; color: #975a1c; margin-bottom: 32px; line-height: 1.5;">Ваш аккаунт был заблокирован администрацией.<br>Если вы считаете, что это ошибка — обратитесь в поддержку.</p>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="header_btn" style="background: #975a1c; color: #fff; border: none; border-radius: 12px; padding: 12px 36px; font-size: 1em; font-weight: 600; cursor: pointer; transition: background 0.2s;">Выйти</button>
    </form>
</div>
@endsection 