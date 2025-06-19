<!DOCTYPE html>
<html>
<head>
    <title>ახალი წიგნი bukinistebi.ge-ზე</title>
</head>
<body>
    @if ($messageContent === 'ბუკინისტებზე დაემატა ახალი წიგნი. გვეწვიე საიტზე: bukinistebi.ge!')
    <h1>ახალი წიგნი დაემატა!</h1>
@endif
<p>{!! $messageContent !!}</p>



    
    <hr style="margin-top: 30px;">

    <p style="font-size: 13px; color: gray;">
        თუ არ გსურს მსგავსი შეტყობინებების მიღება,
        <a href="{{ $unsubscribeLink }}" style="color: #d9534f;" target="_blank">შეგიძლია გააუქმო გამოწერა</a>.
    </p>

Email: bukinistebishop@gmail.com <br>

Website: <a href="https://bukinistebi.ge/" target="_blank"> www.bukinistebi.ge </a> <br><br>

     <img src="{{ asset('uploads/logo/bukinistebi.ge.png') }}" width="100px"> 
</body>
</html>
