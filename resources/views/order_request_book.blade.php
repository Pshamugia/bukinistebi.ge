<!DOCTYPE html>
<html>
<head>
    <title>შეკვეთა წიგნზე</title>
</head>
<body>
    <h2>New Book Order Request</h2>
    <p><strong>Title:</strong> {{ $title }}</p>
    <p><strong>Author:</strong> {{ $author }}</p>
    @if (!empty($publishing_year))
        <p><strong>Publishing Year:</strong> {{ $publishing_year }}</p>
    @endif
    <p><strong>Comment:</strong> {{ $comment }}</p>
<p><strong>User Email:</strong> {{ $email }}</p>

</body>
</html>
