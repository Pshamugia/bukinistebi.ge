@extends('layouts.app')

@section('title', 'აუქციონის წესები')

@section('content')

<style>
    body {
        background: #f9f4e8 !important;
    }
    .vintage-card {
        background: #fffdf7;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 5px 25px rgba(50, 35, 20, 0.15);
        border: 2px solid #e6ddc6;
        font-family: "Georgia", serif;
    }
    h1, h4 {
        font-family: "Georgia", serif;
        color: #5a3e2b;
             margin-top:80px;
        
    }
    h1 {
        font-size: 38px;
     }
    .section-title {
        margin-top: 40px;
        display: flex;
        align-items: center;
        font-size: 22px;
        color: #5a3e2b;
    }
    .section-title i {
        margin-right: 10px;
        color: #8b5e3c;
        font-size: 24px;
    }
    ul li {
        color: #4d3b2f;
        font-size: 17px;
        line-height: 1.75;
    }
    .divider {
        height: 2px;
        background: linear-gradient(to right, #c7b79b, #f9f4e8, #c7b79b);
        margin: 35px 0;
        border-radius: 3px;
    }
   
</style>

<div class="container mt-5" style="position: relative; top:40px;">

    <h1 class="mb-4 fw-bold text-center">
        📜 აუქციონის წესები
    </h1>

    <div class="vintage-card">

        <div class="section-title">
            <i class="bi bi-person-check"></i> მონაწილეობის უფლება
        </div>
        <p>
            აუქციონში მონაწილეობა შეუძლიათ რეგისტრირებულ სრულწლოვან მომხმარებლებს, 
            რომლებიც გადაიხდიან მონაწილეობის სიმბოლურ საფასურს - ეს უზრუნველყოფს დამატებით უსაფრთხოებას. <br>
            ადმინისტრაცია უფლებას იტოვებს, გაუფრთხილებლად დაბლოკოს მომხმარებელი თუ ის შემჩნეული იქნება აუქციონისთვის არასათანადო ქცევაში.
        </p>

        <div class="divider"></div>

        <div class="section-title">
            <i class="bi bi-box-seam"></i> აუქციონზე ნივთის გამოტანა
        </div>
        <p>ბუკინისტურ აუქციონზე პროდუქციის ატვირთვა ხდება მარტივად:</p>

        <ul>
            <li>დარეგისტრირდით როგორც ბუკინისტი (Google-ით შესვლა შესაძლებელია).</li>
            <li>ატვირთეთ პროდუქცია იმავე პრინციპით, რაც წიგნების ატვირთვისას.</li>
            <li>პროდუქცია მოხვდება ბაზაში და მოდერაციის შემდეგ მივანიჭებთ აუქციონის კატეგორიას და გამოჩნდება საიტზე აუქციონების სექციაში.</li>
            <li>კითხვებისთვის მოგვწერეთ: <b>info@bukinistebi.ge</b> ან გამოიყენეთ საიტის ქვედა მხარეს მითითებული სოციალური ქსელები.</li>
        </ul>

        <div class="divider"></div>

        <div class="section-title">
            <i class="bi bi-cash-coin"></i> ბიჯების გაკეთება
        </div>
        <ul>
            <li>ბიჯი ყოველთვის უნდა აღემატებოდეს მიმდინარე ფასს.</li>
            <li>მინ/მაქს ბიჯის არსებობის შემთხვევაში, თითოეული ბიჯი უნდა დაემთხვეს მითითებულ ფარგლებს.</li>
            <li>
                ბიჯის გაკეთება შესაძლებელია ღიად ან ანონიმურად.  
                ანონიმური ბიჯის დროს თქვენი მონაცემები სხვა მომხმარებლებს არ გამოუჩნდება, 
                თუმცა საიტი ინახავს თქვენს ინფორმაციას (სახელი, ელფოსტა, ტელეფონი, მისამართი).
            </li>
        </ul>

        <div class="divider"></div>

        <div class="section-title">
            <i class="bi bi-shield-check"></i> მომხმარებლის გარანტია
        </div>
        <p>
            ბიჯის შესრულებით მომხმარებელი ადასტურებს, რომ მზადაა შეიძინოს პროდუქცია ამ ფასად.
        </p>

        <div class="divider"></div>

        <div class="section-title">
            <i class="bi bi-trophy"></i> დასრულება და გამარჯვება
        </div>
        <p>
            აუქციონის დასრულების შემდეგ, გამარჯვებულ მომხმარებელს უკავშირდება საიტის ადმინისტრაცია. 
            თანხის გადახდისას მას გადაეცემა აუქციონზე შეძენილი პროდუქცია. 
        </p>

    </div>
</div>

@endsection
