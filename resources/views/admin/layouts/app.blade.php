<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - @yield('title')</title>
    <!-- Compiled CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
     
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css"> 
 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    @include('admin.layouts.navbar')
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            /* Ensure the body takes up the full viewport height */
        }

        main {
            flex: 1;
            /* Allow the main content to take up available space */
        }

        footer {
            height: 60px;
            /* Set a fixed height for the footer */
            background-color: #212529;
            /* Dark background color for the footer */
            color: white;
            text-align: center;
            padding: 1rem;
            position: relative;
            width: 100%;
            /* Ensure the footer spans the full width */
            margin-top: auto;
            /* Push the footer to the bottom of the page */
        }
        .btn.btn-primary {
    height: 40px !important;
}
    </style>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
<form method="GET" action="{{ route('admin.search') }}">
        <div class="input-group" style="padding: 22px 0 22px 0">
            <input name="title"  value="{{ request()->get('title') }}" type="search" class="form-control rounded" placeholder="ძიება ადმინპანელში" aria-label="Search" aria-describedby="search-addon" />
            <button type="button" class="btn btn-outline-primary" data-mdb-ripple-init>ეძიე</button>
          </div>
</form>
        @yield('content')


    </main>

    <!-- Compiled JS -->
     <footer class="text-center text-white bg-dark" style="width: 100%; padding: 1rem;">
        <p>© bukinistebi.ge </p>
    </footer>
</body>

</html>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">


<!-- Include Bootstrap JS if needed -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS for Cart Count -->
 

<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

<!-- Initialize Chosen -->
<script>
    $(document).ready(function() {
        $('.chosen-select').chosen({
            no_results_text: "Oops, nothing found!"
        });
    });
</script>
