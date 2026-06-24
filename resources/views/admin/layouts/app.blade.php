<!DOCTYPE html>
<html lang="en">

<head>
    @stack('styles')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - @yield('title')</title>
    <!-- Compiled CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/2.0.0/trix.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css"> 
 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    @include('admin.layouts.navbar')
    <style>
        :root {
            --admin-sidebar-width: 240px;
            --admin-mobile-header-height: 64px;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        main.admin-main {
            flex: 1;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            padding-top: 1rem;
        }

        @media (min-width: 768px) {
            main.admin-main {
                margin-left: var(--admin-sidebar-width);
                width: calc(100% - var(--admin-sidebar-width));
            }

            footer.admin-footer {
                margin-left: var(--admin-sidebar-width);
                width: calc(100% - var(--admin-sidebar-width)) !important;
            }
        }

        @media (max-width: 767.98px) {
            body {
                padding-top: var(--admin-mobile-header-height);
            }

            main.admin-main {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }

            h1, .h1 {
                font-size: 1.65rem;
            }

            h2, .h2 {
                font-size: 1.4rem;
            }

            .input-group.admin-search {
                padding: 12px 0 !important;
            }

            .input-group.admin-search .form-control,
            .input-group.admin-search .btn {
                min-height: 44px;
            }

            .btn, .form-control, .form-select {
                font-size: 1rem;
            }

            main.admin-main > .container,
            main.admin-main > .container-fluid,
            main.admin-main .card,
            main.admin-main form,
            main.admin-main .row {
                max-width: 100%;
                min-width: 0;
            }

            main.admin-main .row {
                --bs-gutter-x: 0.75rem;
            }

            main.admin-main .d-flex:not(.admin-preserve-flex) {
                flex-wrap: wrap;
                gap: .5rem;
            }

            main.admin-main .btn,
            main.admin-main .input-group-text {
                white-space: normal;
            }
        }

        .admin-table-responsive {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .admin-table-responsive > .table {
            margin-bottom: 0;
            min-width: 720px;
        }

        .table {
            vertical-align: middle;
        }

        .card, .table, .form-control, .form-select, textarea, trix-editor, .chosen-container {
            max-width: 100%;
        }

        .chosen-container {
            width: 100% !important;
        }

        trix-toolbar .trix-button-row {
            flex-wrap: wrap;
        }

        img, video {
            max-width: 100%;
            height: auto;
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
    <main class="admin-main px-md-4">
<form method="GET" action="{{ route('admin.search') }}">
        <div class="input-group admin-search" style="padding: 22px 0 22px 0">
            <input name="title"  value="{{ request()->get('title') }}" type="search" class="form-control rounded" placeholder="ძიება ადმინპანელში" aria-label="Search" aria-describedby="search-addon" />
            <button type="submit" class="btn btn-outline-primary" data-mdb-ripple-init>ეძიე</button>
          </div>
</form>
        @yield('content')


    </main>

    <!-- Compiled JS -->
     <footer class="admin-footer text-center text-white bg-dark" style="width: 100%; padding: 1rem;">
        <p>© bukinistebi.ge </p>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/2.0.0/trix.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('main.admin-main table.table').forEach(function(table) {
                if (!table.parentElement.classList.contains('admin-table-responsive')) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'admin-table-responsive mb-3';
                    table.parentNode.insertBefore(wrapper, table);
                    wrapper.appendChild(table);
                }
            });
        });

        $(document).ready(function() {
            $('.chosen-select').chosen({
                width: '100%',
                no_results_text: "Oops, nothing found!"
            });
        });
    </script>
    @stack('scripts')

</body>

</html>

