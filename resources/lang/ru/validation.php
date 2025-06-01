<?php

return [
    'required' => 'Поле :attribute обязательно для заполнения.',
    'email' => 'Поле :attribute должно быть действительным email адресом.',
    'string' => 'Поле :attribute должно быть строкой.',
    'max' => [
        'string' => 'Поле :attribute не должно превышать :max символов.',
    ],
    'min' => [
        'string' => 'Поле :attribute должно содержать не менее :min символов.',
    ],
    'unique' => 'Такой :attribute уже существует.',
    'confirmed' => 'Пароли не совпадают.',
    'size' => 'Поле :attribute должно содержать :size символов.',

    'attributes' => [
        'name' => 'имя',
        'email' => 'электронная почта',
        'password' => 'пароль',
        'password_confirmation' => 'повтор пароля',
        'captcha' => 'код с картинки',
    ],
]; 