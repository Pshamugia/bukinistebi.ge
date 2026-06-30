<style>
    .admin-mobile-navbar {
        min-height: var(--admin-mobile-header-height, 64px);
        z-index: 1080;
    }

    .admin-mobile-navbar .container-fluid {
        align-items: center;
        display: flex;
        flex-wrap: nowrap;
        gap: .75rem;
        justify-content: space-between;
        min-width: 0;
    }

    .admin-mobile-navbar .navbar-brand img {
        max-height: 38px;
        max-width: min(190px, calc(100vw - 96px));
        object-fit: contain;
        width: auto;
    }

    .admin-mobile-navbar .navbar-brand {
        flex: 1 1 auto;
        min-width: 0;
        overflow: hidden;
    }

    .admin-mobile-navbar .navbar-toggler {
        display: inline-flex;
        flex: 0 0 auto;
        margin-left: auto;
        position: relative;
        z-index: 1090;
    }

    .sidebar {
        width: var(--admin-sidebar-width, 240px);
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        padding-top: 1rem;
        overflow-y: auto;
        z-index: 1030;
        font-size: 14px;
    }

    .sidebar .nav-link {
        align-items: center;
        border-radius: .375rem;
        display: flex;
        gap: .45rem;
        line-height: 1.3;
        margin: 0 .5rem .15rem;
        min-height: 42px;
        overflow-wrap: anywhere;
        padding: .55rem .75rem;
        white-space: normal;
    }

    .sidebar .nav-link.dropdown-toggle {
        justify-content: space-between;
    }

    .sidebar .nav-link i {
        flex: 0 0 auto;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link:focus {
        background-color: rgba(255, 255, 255, .12);
    }

    .sidebar-logo {
        width: 130px;
        max-width: calc(100% - 1rem);
        height: auto;
    }

    .sidebar-logo-link {
        display: block !important;
        text-align: center;
    }

    @media (max-width: 767.98px) {
        .sidebar {
            top: var(--admin-mobile-header-height, 64px);
            height: calc(100vh - var(--admin-mobile-header-height, 64px));
            width: min(86vw, 320px);
            padding-top: .75rem;
            transform: translateX(-100%);
            transition: transform .25s ease-in-out;
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .35);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .sidebar .nav-link {
            font-size: 15px;
            margin-left: .75rem;
            margin-right: .75rem;
            min-height: 46px;
        }

        .sidebar .list-unstyled {
            padding-left: 1rem !important;
        }
    }
</style>
<nav class="navbar navbar-dark bg-dark fixed-top d-md-none admin-mobile-navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('admin') }}">
            <img src="{{ asset('uploads/logo/bukinistebi.ge.png') }}" alt="Bukinistebi admin" loading="lazy">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminSidebar" aria-controls="adminSidebar" aria-expanded="false" aria-label="Toggle admin navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Sidebar -->
        <nav id="adminSidebar" class="collapse d-md-block bg-dark sidebar">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white sidebar-logo-link" href="{{ route('admin') }}">
                            <img src="{{ asset('uploads/logo/bukinistebi.ge.png') }}" class="sidebar-logo" loading="lazy" alt="Bukinistebi admin">
                            <br><br>
                            <i class="bi bi-speedometer2"></i> Dashboard

                        </a>
                    </li>

                    @if(auth()->user()->isAdmin())
                    <li>
                        <a class="nav-link text-white" href="{{ route('admin.subadmins') }}">
                            <i class="bi bi-shield-lock"></i> Sub-admins
                        </a>
                    </li>
                    @endif

                    <hr style="border: 0; border-bottom: 1px dashed #ccc; background: #999;">

                    <li class="nav-item">
                        <a class="nav-link text-white dropdown-toggle" href="#uploadMaterials" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="uploadMaterials">
                            <i class="bi bi-upload"></i> მასალების ატვირთვა
                        </a>

                        <div class="collapse" id="uploadMaterials">
                            <ul class="list-unstyled ps-5">

                                @if(auth()->user()->hasAdminPermission(permission: 'books.manage'))

                                <li><a class="nav-link text-white" href="{{ route('admin.books.index') }}"><i class="bi bi-book"></i> წიგნები</a></li>

                                @endif

                                <li><a class="nav-link text-white" href="{{ route('admin.book-news.index') }}"><i class="bi bi-megaphone"></i> ბუკ. ამბები</a></li>
                            </ul>
                        </div>
                    </li>


                    <li><a class="nav-link text-white" href="{{ route('admin.auctions.index') }}"> <i class="bi bi-hammer"></i> აუქციონი !! </a></li>
                    <li><a class="nav-link text-white" href="{{ route('admin.auction-categories.index') }}"> <i class="bi bi-hammer"></i> აუქციონის კატეგორიები </a></li>

                    <li><a class="nav-link text-white" href="{{ route('admin.genres.index') }}"><i class="bi bi-tags"></i> ჟანრები</a></li>
                    <li><a class="nav-link text-white" href="{{ route('admin.authors.index') }}"><i class="bi bi-person"></i> ავტორები</a></li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.users.list') }}"><i class="bi bi-people"></i> მომხმარებლების სია</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.publishers.activity') }}"><i class="bi bi-person-workspace"></i> ბუკინისტების სია</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.subscribers') }}"><i class="bi bi-envelope-paper"></i> გამომწერების სია</a>
                    </li>

                    @if(auth()->user()->hasAdminPermission(permission: 'transactions.manage'))

                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.users_transactions') }}"><i class="bi bi-receipt"></i> ტრანზაქციები</a>
                    </li>

                    @endif


                                      <li class="nav-item">

    <a class="nav-link text-white" href="{{ route('admin.publishing.index') }}">
        <i class="fa fa-book"></i>
        გამოცემა (Publishing)
    </a>
</li>

                    <li><a class="nav-link text-white" href="{{ route('admin.book_orders') }}"><i class="bi bi-cart4"></i> შეკვეთები</a></li>

                    @if(auth()->user()->hasAdminPermission(permission: 'qookies.manage'))

                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.user.preferences.purchases') }}"><i class="bi bi-cookie"></i> ქუქი ჩანაწერები</a>
                    </li>

                    @endif

                    @if(auth()->user()->hasAdminPermission('announcement.manage'))
                    <li class="nav-item">
                        <a class="nav-link text-white"
                            href="{{ route('announcements.index') }}">
                            <i class="bi bi-megaphone"></i> ანონსი
                        </a>
                    </li>
                    @endif



                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.user.keywords') }}"><i class="bi bi-search-heart"></i> რას ეძებენ</a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.bundles.index') }}">
                            <i class="bi bi-search-heart"></i> საბითუმო
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link text-white" href="https://bukinistebi.ge/roundcube/" target="blank"><i class="bi bi-envelope"></i> EMAIL</a>
                    </li>

                    <hr style="border: 0; border-bottom: 1px dashed #ccc; background: #999;">

                    <li class="nav-item">
                        @auth
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-file-earmark-person"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="userDropdown">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item" type="submit">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        @endauth
                    </li>
                </ul>

            </div>
        </nav>

        <!-- Main Content Area -->

    </div>
</div>
