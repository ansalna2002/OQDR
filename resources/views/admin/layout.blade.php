<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADZBAZZAR - @yield('title')</title>
    <link rel="icon" href="{{ asset('/assets') }}/images/favicon.icon" type="image/x-icon">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets') }}/css/bootstrap/5.3.2/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets') }}/css/style.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets') }}/css/custom.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/assets') }}/css/icons/fontawesome/css/fontawesome.css">
    <link rel="stylesheet" href="{{ asset('/assets') }}/css/icons/fontawesome/css/brands.css">
    <link rel="stylesheet" href="{{ asset('/assets') }}/css/icons/fontawesome/css/regular.css">
    <link rel="stylesheet" href="{{ asset('/assets') }}/css/icons/fontawesome/css/solid.css">
    <!-- Font Awesome -->

    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">

    @yield('header')

</head>

<body>

    <div class="wrapper" id="oMIGAiPL-App">
        <aside id="sidebar" class="js-sidebar">
            <div class="h-100" id="sidebar-contents">
                <div class="sidebar-logo">
                    <a href="{{ route('dashboard') }}">
                    </a>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="{{ route('dashboard') }}" class="sidebar-link">
                            <img src="{{ asset('/assets') }}/images/icons/sidebar/dashboard-light.svg"
                                class="pe-2 sidebar-icon light-icon">
                            <img src="{{ asset('/assets') }}/images/icons/sidebar/dashboard-dark.svg"
                                class="pe-2 sidebar-icon dark-icon">
                            Dashboard
                        </a>
                    </li>
                   
                    <li class="sidebar-item">
                        <a href="{{ route('user_management') }}" class="sidebar-link">
                            <img src="{{ asset('/assets') }}/images/icons/sidebar/users-light.svg"
                                class="pe-2 sidebar-icon light-icon">
                            <img src="{{ asset('/assets') }}/images/icons/sidebar/users-dark.svg"
                                class="pe-2 sidebar-icon dark-icon">
                            User Management
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('user_approval') }}" class="sidebar-link">
                            <img src="{{ asset('/assets') }}/images/icons/sidebar/users-light.svg"
                                class="pe-2 sidebar-icon light-icon">
                            <img src="{{ asset('/assets') }}/images/icons/sidebar/users-dark.svg"
                                class="pe-2 sidebar-icon dark-icon">
                            User Approval
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('membership_approval') }}" class="sidebar-link">
                            <img src="{{ asset('/assets') }}/images/icons/sidebar/users-light.svg"
                                class="pe-2 sidebar-icon light-icon">
                            <img src="{{ asset('/assets') }}/images/icons/sidebar/users-dark.svg"
                                class="pe-2 sidebar-icon dark-icon">
                            Membership Approval
                        </a>
                    </li>
                    
                    <li class="sidebar-item">
                        <a href="{{ route('add_ad') }}" class="sidebar-link">
                            <img src="{{ asset('/assets') }}/images/icons/sidebar/users-light.svg"
                                class="pe-2 sidebar-icon light-icon">
                            <img src="{{ asset('/assets') }}/images/icons/sidebar/users-dark.svg"
                                class="pe-2 sidebar-icon dark-icon">
                            Ad
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="{{ route('reset_password') }}" class="sidebar-link">
                            <img src="{{ asset('/assets') }}/images/icons/sidebar/reset-password-light.svg"
                                class="pe-2 sidebar-icon light-icon">
                            <img src="{{ asset('/assets') }}/images/icons/sidebar/reset-password-dark.svg"
                                class="pe-2 sidebar-icon dark-icon">
                            Reset Password
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('support') }}" class="sidebar-link">
                            <img src="{{ asset('/assets') }}/images/icons/sidebar/reset-password-light.svg"
                                class="pe-2 sidebar-icon light-icon">
                            <img src="{{ asset('/assets') }}/images/icons/sidebar/reset-password-dark.svg"
                                class="pe-2 sidebar-icon dark-icon">
                           Support
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a type="button" data-bs-toggle="modal" data-bs-target="#LogoutModal" class="sidebar-link">
                            <img src="{{ asset('/assets') }}/images/icons/sidebar/logout-light.svg"
                                class="pe-2 light-icon">
                            <img src="{{ asset('/assets') }}/images/icons/sidebar/logout-dark.svg"
                                class="pe-2 dark-icon">
                            Logout
                        </a>
                    </li>
                 

                </ul>
            </div>
        </aside>
        <div class="main">
           
            <nav class="navbar navbar-expand px-3">
                <button class="btn" id="sidebar-toggle" type="button">
                   <span class="navbar-toggler-icon"></span>
                </button>  
                <div id="profile-dropdown" class="navbar-collapse navbar pe-4">
                    <ul class="navbar-nav profile-nav-dropdown">
                        <li class="profile-li">
                            <div class="text-dark">Hello, <span>Admin</span></div>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                <img src="{{asset('/assets')}}/images/avatar/avatar.svg" class="avatar img-fluid rounded" alt="">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                                <div class="text-center profile-greets-holder">
                                    <p class="mb-0 greet-text text-muted">Welcome Back!</p>
                                    <p class="mb-0 dropdown-profile-name">Admin</p>
                                    <hr class="dropdown-divider">
                                </div>    
                                {{-- <a href="{{ route('profile') }}" class="dropdown-item">
                                   <i class="fa-regular fa-user me-2"></i>profile
                                </a> --}}
                                <a href="{{ route('reset_password') }}" class="dropdown-item">
                                    <i class="fa-solid fa-key me-2"></i>Reset Password
                                </a>
                                <a data-bs-toggle="modal" data-bs-target="#LogoutModal" href="" class="dropdown-item">
                                    <i class="fa-solid fa-power-off me-2"></i>Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Offcanvas Slider -->
            <div style="max-width: 280px;background: #FFF;" class="offcanvas offcanvas-start" data-bs-scroll="true"
                data-bs-backdrop="true" tabindex="-1" id="offcanvasScrolling"
                aria-labelledby="offcanvasScrollingLabel">
                <div class="pb-0 offcanvas-header justify-content-end">
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body pt-0 ps-0">

                </div>
            </div>
            <!-- Offcanvas Slider -->

            <main class="main-page-content">
               @if (session('success'))
                <div id="success-toast" class="toast bg-success align-items-center border-0 show" role="alert"
                    aria-live="assertive" aria-atomic="true"
                    style="position: fixed; top: 150px; right: 20px; z-index: 1050; border-radius: 8px;">
                    <div class="toast-body d-flex text-white align-items-center">
                        <i class="fa-regular fa-circle-check" style="font-size: 20px; margin-right: 10px;"></i>
                        <div>
                            {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            @endif
            
            @if (session('error'))
                <div id="error-toast" class="toast bg-danger align-items-center border-0 show" role="alert"
                    aria-live="assertive" aria-atomic="true"
                    style="position: fixed; top: 80px; right: 20px; z-index: 1050; border-radius: 8px;">
                    <div class="toast-body d-flex text-white align-items-center">
                        <i class="fa-solid fa-circle-exclamation" style="font-size: 20px; margin-right: 10px;"></i>
                        <div>
                            {{ session('error') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            @endif
            
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    var successToastEl = document.getElementById("success-toast");
                    var errorToastEl = document.getElementById("error-toast");
            
                    if (successToastEl) {
                        var successToast = new bootstrap.Toast(successToastEl);
                        successToast.show();
                    }
            
                    if (errorToastEl) {
                        var errorToast = new bootstrap.Toast(errorToastEl);
                        errorToast.show();
                    }
                });
            </script>
            

                <!--Common Sample Modal  Starts-->
                <div class="modal fade zoom-in" id="SampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="p-3">
                                            <div class="text-center">
                                                <p class="my-4 are-you-sure">Are You Sure</p>
                                                <p class="text-muted my-2 are-you-sure-subtext">Are you sure want to
                                                    __________?</p>
                                            </div>
                                            <div class="d-flex align-items-center mt-5 mb-3">
                                                <button data-bs-dismiss="modal"
                                                    class="btn btn-light cancel-btn me-3">Cancel</button>
                                                <button class="btn btn-primary yes-btn"><i
                                                        class="fa-regular fa-circle-check me-2"></i>Yes,
                                                    ________</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Common Modal Ends-->

                <!--Remark Modal  Starts-->
                <div class="modal fade zoom-in" id="RemarkModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header border-bottom-0 pb-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-3 px-lg-4">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form action="#" method="POST">
                                            @csrf
                                            <div class="text-center">
                                                <h6 class="modal-heading">Remark</h6>
                                            </div>
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Title</label>
                                                <input type="text" class="form-control" id="title"
                                                    placeholder="Title">
                                            </div>
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Remark</label>
                                                <textarea class="form-control" placeholder="Enter Remark here" rows="3"></textarea>
                                            </div>
                                            <div class="d-flex justify-content-center align-items-center mt-5 mb-3">
                                                <button data-bs-dismiss="modal"
                                                    class="btn btn-light cancel-btn me-3">Cancel</button>
                                                <button type="submit" class="btn btn-primary btn-red"><i
                                                        class="fa-regular fa-circle-check me-2"></i>Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Remark Modal Ends-->

                <!--Delete Modal  Starts-->
                <div class="modal fade zoom-in" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="p-3">
                                            <div class="text-center">
                                                <img class="mb-3"
                                                    src="{{ asset('/assets') }}/images/icons/delete-icon.svg">
                                                <p class="my-4 are-you-sure">Are You Sure</p>
                                                <p class="text-muted my-2 are-you-sure-subtext">Are you sure want to
                                                    Delete this _______________?</p>
                                            </div>
                                            <div class="d-flex align-items-center mt-5 mb-3">
                                                <button data-bs-dismiss="modal"
                                                    class="btn btn-light cancel-btn me-3">Cancel</button>
                                                <button class="btn btn-primary yes-btn"><i
                                                        class="fa-regular fa-circle-check me-2"></i>Yes, Delete
                                                    It!</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Delete Modal Ends-->



                <!-- Log Out Modal Starts -->
                <div class="modal fade zoom-in" id="LogoutModal" tabindex="-1" aria-labelledby="LogoutModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="p-3">
                                            <div class="text-center">
                                                <img class="mb-3"
                                                    src="{{ asset('/assets') }}/images/icons/logout.svg">
                                                <p class="my-4 are-you-sure">Are You Sure</p>
                                                <p class="text-muted my-2 are-you-sure-subtext">Are you sure you want
                                                    to logout?</p>
                                            </div>
                                            <form action="{{ route('admin_logout') }}" method="POST">
                                                @csrf
                                                <div class="d-flex align-items-center mt-5 mb-3">
                                                    <button type="button" data-bs-dismiss="modal"
                                                        class="btn btn-light cancel-btn me-3">Cancel</button>
                                                    <button type="submit" class="btn btn-primary yes-btn">
                                                        <i class="fa-regular fa-circle-check me-2"></i>Yes, Logout
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Log Out Modal Ends -->




                @yield('content')



            </main>



            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid px-lg-5">
                    <div class="row">
                        <div class="col-lg-6 col-6">
                            <span class="footer-text">
                                <script>
                                    document.write(new Date().getFullYear())
                                </script><span class="px-1">&#169;</span><span
                                    class="brand-text">AdZBAZZAR<span style="color:#04F7FF !important;"></span></span>
                            </span>
                        </div>
                        <div class="col-lg-6 col-6">
                            <div class="text-end">
                                <span class="footer-text">Developed by <a href="https://novelxtechnologies.com/"
                                        target="_blank" class="designed-by">Novel X</a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>


   

    <!-- JQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+0XaPoufisyC/A5A4P3p6D5an6Zlvi4yU5a8rhP" crossorigin="anonymous">
    </script>

    <script type="text/javascript" src="{{ asset('/assets') }}/js/script.js"></script>
    <script type="text/javascript" src="{{ asset('/assets') }}/js/bootstrap/5.3.2/bootstrap.min.js"></script>

    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @yield('footer')
</body>

</html>
