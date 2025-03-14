<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | </title>
    <link rel="icon" href="{{ asset('/assets') }}/images/favicon.ico" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets') }}/css/bootstrap/5.3.2/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/assets') }}/css/icons/fontawesome/css/fontawesome.css">
    <link rel="stylesheet" href="{{ asset('/assets') }}/css/icons/fontawesome/css/brands.css">
    <link rel="stylesheet" href="{{ asset('/assets') }}/css/icons/fontawesome/css/regular.css">
    <link rel="stylesheet" href="{{ asset('/assets') }}/css/icons/fontawesome/css/solid.css">

    <!-- Page CSS -->
    <link rel = "stylesheet" type = "text/css" href = "{{ asset('/assets') }}/css/style.css">
    <link rel = "stylesheet" type = "text/css" href = "{{ asset('/assets') }}/css/authentication.css">

    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .logo-widget {
            background: #D4EFFF;
            background-size: cover !important;
        }

        #otp-actions {
            display: none;
            opacity: 0;
            position: relative;
            bottom: -20px;
            transition: opacity 0.5s ease-in-out, bottom 0.5s ease-in-out;
        }

        #otp-actions.show {
            opacity: 1;
            bottom: 0;
        }

        #otp-success-message {
            color: black;
            /* Default text color */
            font-weight: bold;
            /* Make text bold */
            margin-top: 10px;
            /* Space above the message */
        }

        #otp-error-message {
            color: black;
            /* Default text color */
            font-weight: bold;
            /* Make text bold */
            margin-top: 10px;
            /* Space above the message */
        }

        #otp-success-message.success {
            color: green;
            /* Success message color */
        }

        #otp-success-message.error {
            color: red;
            /* Error message color */
        }
    </style>

</head>

<body>
    <div class="page-content overflow-hidden min-vh-100 login-container">
        <div class="row g-0 vh-100">
            <div class="col-xl-5 col-lg-5 col-md-6 col-sm-6 rightside-widget-col">
                {{-- <div class="p-lg-5 p-4 h-100 logo-widget"> --}}
                    {{-- <div class="d-flex justify-content-center align-items-center h-100"> --}}
                        <div class="">
                            {{-- <img src="{{ asset('/assets') }}/images/bg-left.svg" style="height:900px;"
                                class="inframe-logo" alt="logo frame"> --}}
                            <img src="{{ asset('/assets') }}/images/bg-left.svg"
                                style="height:800px; width:800px; object-fit: cover;" class="inframe-logo"
                                alt="logo frame">

                        </div>
                    {{-- </div> --}}
                {{-- </div> --}}
            </div>
            <div class="col-xl-7 col-lg-7 col-md-6 col-sm-6 d-flex justify-content-center align-items-center">
                <div class="py-3 px-lg-5 px-md-4 px-sm-4 px-4 form-container row">
                    <div class="d-flex justify-content-center mb-1">
                        <img src="{{ asset('/assets') }}/images/logo-horizontal.svg" class=" d-none widget-logo"
                            alt="logo-horizontal">
                    </div>
                    <div class="text-start">
                        <h3 class="login-heading">Login</h3>
                        <p class="py-2 login-slogan">Please fill your information below</p>
                    </div>



                    <form action="{{ route('login_post') }}" method="POST" class="row g-3 needs-validation"
                        autocomplete="off">
                        @csrf
                        <!-- Email Input -->
                        <div class="input-group mb-4">
                            <span class="input-group-text">
                                <img src="{{ asset('/assets') }}/images/icons/envelope.svg" alt="Email Icon">
                            </span>
                            <input type="email" name="email" class="form-control" value="admin@gmail.com"
                                placeholder="E-mail" aria-label="Email" aria-describedby="Email" required>
                        </div>

                        <!-- Password Input -->
                        <div class="input-group mb-3 position-relative">
                            <span class="input-group-text">
                                <img src="{{ asset('/assets') }}/images/icons/lock.svg" alt="Lock Icon">
                            </span>
                            <input type="password" name="password" value="admin" id="password" class="form-control"
                                placeholder="Password" aria-label="Password" required>
                            <span class="toggle-password position-absolute"
                                style="right: 20px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                <i class="fa-solid fa-eye-slash" id="togglepassword"
                                    onclick="togglePassword('password', 'togglepassword')"></i>
                            </span>
                        </div>

                        <div class="d-flex justify-content-end align-items-center">
                            <a href="{{ route('forgot_password') }}" target="_self" class="forgot-pwd">Forgot
                                Password?</a>
                        </div>

                        <!-- Login Button -->
                        <div class="login-btn-container mt-2">
                            <button type="submit" class="btn btn-success login-btn w-100">Login</button>
                        </div>
                    </form>


                    <!-- Form Ends -->

                </div>
            </div>
        </div>
    </div>

    {{-- toast --}}
    <!-- Success Toast -->
    @if (session('successmessage'))
        <div id="success-toast" class="toast bg-success align-items-center border-0 show" role="alert"
            aria-live="assertive" aria-atomic="true"
            style="position: fixed; top: 150px; right: 20px; z-index: 1050; border-radius: 8px;">
            <div class="toast-body d-flex text-white align-items-center">
                <i class="fa-regular fa-circle-check" style="font-size: 20px; margin-right: 10px;"></i>
                <div>
                    {{ session('successmessage') }}
                </div>
                <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Error Toast -->
    @if ($errors->any())
        <div id="error-toast" class="toast bg-danger align-items-center border-0 show" role="alert"
            aria-live="assertive" aria-atomic="true"
            style="position: fixed; top: 80px; right: 20px; z-index: 1050; border-radius: 8px;">
            <div class="toast-body d-flex text-white align-items-center">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 20px; margin-right: 10px;"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
                <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const successMessage = @json(session('successmessage'));
            const errorMessage = @json($errors->any() ? $errors->first() : null);

            function showAlertAndToast(type, message) {

                const isSuccess = type === 'success';
                const alertTitle = isSuccess ? 'Success' : 'Error';
                const alertText = message || (isSuccess ? 'Operation was successful!' : 'Something went wrong!');


                Swal.fire({
                    icon: type,
                    title: alertTitle,
                    text: alertText,
                    willClose: () => {

                        const toastElement = document.getElementById(`${type}-toast`);
                        if (toastElement) {
                            const toast = new bootstrap.Toast(toastElement);
                            toast.show();
                        }
                    }
                });
            }


            if (successMessage) {
                showAlertAndToast('success', successMessage);
            } else if (errorMessage) {
                showAlertAndToast('error', errorMessage);
            }


            document.getElementById('trigger-success').addEventListener('click', function() {
                showAlertAndToast('success', successMessage);
            });

            document.getElementById('trigger-error').addEventListener('click', function() {
                showAlertAndToast('error', errorMessage);
            });
        });
    </script>



    <script type="text/javascript" src="{{ asset('/assets') }}/js/authentication.js"></script>
    <script type="text/javascript" src="{{ asset('/assets') }}/js/bootstrap/5.3.2/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            function togglePassword(passwordFieldId, toggleIconId) {
                const passwordField = document.getElementById(passwordFieldId);
                const toggleIcon = document.getElementById(toggleIconId);

                if (passwordField.type === "password") {
                    passwordField.type = "text"; // Show password
                    toggleIcon.classList.remove("fa-eye-slash");
                    toggleIcon.classList.add("fa-eye");
                } else {
                    passwordField.type = "password";
                    toggleIcon.classList.remove("fa-eye");
                    toggleIcon.classList.add("fa-eye-slash");
                }
            }
            document.getElementById('togglepassword').addEventListener('click', function() {
                togglePassword('password', 'togglepassword');
            });
        });
    </script>
</body>

</html>
