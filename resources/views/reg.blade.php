<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reg.css') }}">
    <title>Document</title>
</head>

<body>
    <div class="background">
        <div class="content">
            <form method="POST" class="reg" action="{{ route('reg') }}">
                @csrf
                    <a class="back_home" href="{{ route('home') }}">
                    🠔 На главную
                    </a>
                <h1 class="title_reg">Регистрация</h1>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <label for="email">Эл. почта:</label>
                <input type="text" id="email" name="email" required autofocus value="{{ old('email') }}"
                    class="@error('email') is-invalid @enderror">
                <label for="name">Ваше имя(будет видно другим):</label>
                <input type="text" id="name" name="name" required value="{{ old('name') }}"
                    class="@error('name') is-invalid @enderror">
                <label for="password">Придумайте пароль:</label>
                <input type="password" id="password" name="password" required
                    class="@error('password') is-invalid @enderror">
                <label for="password_confirmation">Повторите пароль:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="@error('password_confirmation') is-invalid @enderror">

                <label for="captcha">Введите код с картинки:</label>
                <div class="captcha-wrapper">
                    <div id="captcha-text" class="captcha-text"></div>
                    <button type="button" id="refresh-captcha" class="refresh-captcha">↻</button>
                </div>
                <input type="text" id="captcha" name="captcha" required value="{{ old('captcha') }}"
                    class="@error('captcha') is-invalid @enderror">
                <div class="checkbox_reg">
                    <input type="checkbox" required {{ old('checkbox') ? 'checked' : '' }}>
                    <p>Согласие на обработку перс. данных</p>
                </div>
                <button class="btn_reg">Регистрация</button>
                <p class="auth">Есть аккаунт? <a href="{{ route('auth') }}" class="link_auth">Войти</a></p>
            </form>
        </div>
    </div>
    <script>
        function generateCaptcha() {
            fetch('{{ route("captcha.generate") }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('captcha-text').textContent = data.captcha;
                });
        }

        document.getElementById('refresh-captcha').addEventListener('click', generateCaptcha);
        generateCaptcha();
    </script>
</body>

</html>