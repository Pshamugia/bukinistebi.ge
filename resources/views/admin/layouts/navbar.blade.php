<style>
    .sidebar {
    height: 100vh; /* Full viewport height */
    position: fixed; /* Fix it to the left */
    top: 0;
    left: 0;
    padding-top: 1rem; /* Add some padding */
}
.main-content {
    margin-left: 200px; /* Match sidebar width */
}



    </style>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar" style="    font-size: 14px;
        ">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin') }}">
                            <img src="{{ asset('uploads/logo/bukinistebi.ge.png') }}" width="130px" style="position:relative; top:8px;" loading="lazy">
                            <br><br><br>
                            <i class="bi bi-speedometer2"></i> Dashboard
                             
                        </a>
                    </li>
                    <hr style="border: 0; border-bottom: 1px dashed #ccc; background: #999;">
                
                    <li class="nav-item">
                        <a class="nav-link text-white dropdown-toggle" href="#uploadMaterials" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="uploadMaterials">
                             <i class="bi bi-upload"></i> მასალების ატვირთვა
                        </a>
                        <div class="collapse" id="uploadMaterials">
                            <ul class="list-unstyled ps-5">
                                <li><a class="nav-link text-white" href="{{ route('admin.books.index') }}"><i class="bi bi-book"></i> წიგნები</a></li>
                                <li><a class="nav-link text-white" href="{{ route('admin.book-news.index') }}"><i class="bi bi-megaphone"></i> ბუკ. ამბები</a></li>
                            </ul>
                        </div>
                    </li>
                
                    <li><a class="nav-link text-white" href="{{ route('admin.categories.index') }}"> <i class="bi bi-folder"></i> კატეგორიები</a></li>
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
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.users_transactions') }}"><i class="bi bi-receipt"></i> ტრანზაქციები</a>
                    </li>
                
                    <li><a class="nav-link text-white" href="{{ route('admin.book_orders') }}"><i class="bi bi-cart4"></i> შეკვეთები</a></li>
                
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.user.preferences.purchases') }}"><i class="bi bi-cookie"></i> ქუქი ჩანაწერები</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.user.keywords') }}"><i class="bi bi-search-heart"></i> რას ეძებენ</a>
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
