<?php

return [
    'required' => 'ველი :attribute აუცილებელია.',
    'email' => 'გთხოვთ, შეიყვანოთ სწორი ელფოსტა.',
    'password' => 'პაროლი არასწორია.',
    'confirmed' => 'პაროლის დადასტურება არ ემთხვევა.',
    'min' => [
        'string' => ':attribute უნდა იყოს მინიმუმ :min სიმბოლო.',
    ],
    'max' => [
        'string' => ':attribute არ უნდა შეიცავდეს :max სიმბოლოზე მეტს.',
        'file' => ':attribute არ უნდა აღემატებოდეს :max კილობაიტს.',
        'array' => ':attribute არ უნდა შეიცავდეს :max ელემენტზე მეტს.',
    ],
    
    'unique' => [
        'name' => 'ეს ავტორი ან ელფოსტა უკვე დარეგისტრირებულია', // Custom message for the unique name rule on authors
        'email' => 'ელფოსტა უკვე გამოყენებულია', // Custom message for unique email
    ],
        'exists' => 'არჩეული :attribute არასწორია.',
    'current_password' => 'პაროლი არასწორია.',
    'string' => ':attribute უნდა იყოს ტექსტი.',
    'same' => ':attribute და :other უნდა ემთხვეოდეს.',
    'min' => [
        'numeric' => ':attribute უნდა იყოს მინიმუმ :min.',
        'string' => ':attribute უნდა იყოს მინიმუმ :min სიმბოლო.',
        'array' => ':attribute უნდა შეიცავდეს მინიმუმ :min ელემენტს.',
    ],
];

