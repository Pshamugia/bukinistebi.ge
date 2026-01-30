<?php

return [
   'required' => 'Поле :attribute обязательно.',
'email' => 'Пожалуйста, введите корректный адрес электронной почты.',
'password' => 'Неверный пароль.',
'confirmed' => 'Подтверждение пароля не совпадает.',
'min' => [
    'string' => ':attribute должно содержать минимум :min символов.',
],
'max' => [
    'string' => ':attribute не должно содержать более :max символов.',
    'file' => ':attribute не должно превышать :max килобайт.',
    'array' => ':attribute не должно содержать более :max элементов.',
],

'unique' => [
    'name' => 'Этот автор или адрес электронной почты уже зарегистрирован', // Custom message for the unique name rule on authors
    'email' => 'Адрес электронной почты уже используется', // Custom message for unique email
],
'exists' => 'Выбранное значение :attribute недействительно.',
'current_password' => 'Неверный пароль.',
'string' => ':attribute должно быть строкой.',
'same' => ':attribute и :other должны совпадать.',
'min' => [
    'numeric' => ':attribute должно быть не меньше :min.',
    'string' => ':attribute должно содержать минимум :min символов.',
    'array' => ':attribute должно содержать минимум :min элементов.',
],

];

