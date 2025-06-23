<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ time() }}">
    <title>Document</title>
</head>

<body>
    <div class="background">
        <div class="content">
            <form method="POST" action="{{ route('auth') }}" class="auth">
                @csrf
                <a class="back_home" href="{{ route('home') }}">
                    🠔 На главную
                    </a>
                <h1 class="title_auth">Авторизация</h1>
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
                <input type="text" id="email" name="email" value="{{ old('email') }}"
                    class="@error('email') is-invalid @enderror">
                @error('email')
                    <span class="invalid" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" class="@error('password') is-invalid @enderror">

                <label for="captcha">Введите код с картинки:</label>
                <div class="captcha-wrapper">
                    <div id="captcha-text" class="captcha-text"></div>
                    <button type="button" id="refresh-captcha" class="refresh-captcha">↻</button>
                </div>
                <input type="text" id="captcha" name="captcha" required value="{{ old('captcha') }}"
                    class="@error('captcha') is-invalid @enderror">
                @error('captcha')
                    <span class="invalid" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

                <button class="btn_auth">Войти</button>
                <p class="reg">Есть аккаунт? <a href="{{ route('reg') }}" class="link_reg">Регистрация</a></p>
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