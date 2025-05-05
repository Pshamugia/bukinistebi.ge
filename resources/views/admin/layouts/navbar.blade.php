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
        <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin') }}">
                            <img
                    src="{{ asset('uploads/logo/bukinistebi.ge.png') }}" width="130px"
                    style="position:relative; top:8px;" loading="lazy">
                    <br> <br> <br>
                            Dashboard  
                            <br> <br>
                        </a>
                    </li>
                    <hr style="  border: 0;
                    border-bottom: 1px dashed #ccc;
                    background: #999;">
                    <li class="nav-item">
                        <a class="nav-link text-white dropdown-toggle" href="#uploadMaterials" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="uploadMaterials">
                            მასალების ატვირთვა
                        </a>
                        <div class="collapse" id="uploadMaterials">
                            <ul class="list-unstyled ps-5">
                                <li><a class="nav-link text-white" href="{{ route('admin.books.index') }}">წიგნები</a></li>
                                <li><a class="nav-link text-white" href="{{ route('admin.book-news.index') }}">ბუკ. ამბები</a></li>
                            </ul>
                        </div>
                    </li>

                    <li><a class="nav-link text-white" href="{{ route('admin.categories.index') }}">კატეგორიები</a></li>
                    
                    <li><a class="nav-link text-white" href="{{ route('admin.genres.index') }}">ჟანრები</a></li>
                    <li><a class="nav-link text-white" href="{{ route('admin.authors.index') }}">ავტორები</a></li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.users.list') }}">მოხმარებლების სია</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.publishers.activity') }}">ბუკინისტების სია</a>
                    </li>



                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.subscribers') }}">გამომწერების სია</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.users_transactions') }}">ტრანზაქციები</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.user.preferences.purchases') }}">ქუქი ჩანაწერები</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.user.keywords') }}">რას ეძებენ</a>
                    </li>



                    <hr style="  border: 0;
                    border-bottom: 1px dashed #ccc;
                    background: #999;"> 

                    <li class="nav-item">
                        @auth
                            <div class="dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-file-earmark-person"></i>  {{ Auth::user()->name }}
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
