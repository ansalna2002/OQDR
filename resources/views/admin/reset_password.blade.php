@extends('admin.layout')
@section('content')
    <style>
        .resetbtn {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    <div class="container-fluid content-inner pb-0">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-title mb-0">Reset Password</h2>
                <div class="card p-2">

                    <form action="{{ route('reset_password_handle') }}" method="POST">
                        @csrf
                        <div class="row justify-content-center mt-3 mb-3">
                            <div class="form-group col-md-8 mb-3  position-relative">
                                <label class="form-label" for="oldPassword">Current Password <span
                                        class="text"></span></label>
                                <input type="password" class="form-control" id="oldPassword" placeholder="Old Password"
                                    name="password">
                                <span class="toggle-password position-absolute"
                                    style="right: 20px; top: 45px; cursor: pointer;">
                                    <i class="fa-solid fa-eye" id="toggleOldPassword"
                                        onclick="togglePassword('oldPassword', 'toggleOldPassword')"></i>
                                </span>
                            </div>
                            <div class="form-group col-md-8 mb-3  position-relative">
                                <label class="form-label" for="newPassword">New Password <span
                                        class="text"></span></label>
                                <input type="password" class="form-control" id="newPassword" placeholder="New Password"
                                    name="new_pwd">
                                <span class="toggle-password position-absolute"
                                    style="right: 20px; top: 45px; cursor: pointer;">
                                    <i class="fa-solid fa-eye" id="toggleNewPassword"
                                        onclick="togglePassword('newPassword', 'toggleNewPassword')"></i>
                                </span>
                            </div>
                            <div class="form-group col-md-8 mb-3  position-relative">
                                <label class="form-label" for="confirmPassword">Confirm Password <span
                                        class="text"></span></label>
                                <input type="password" class="form-control" id="confirmPassword"
                                    placeholder="Confirm Password" name="new_pwd_confirmation">
                                <span class="toggle-password position-absolute"
                                    style="right: 20px; top: 45px; cursor: pointer;">
                                    <i class="fa-solid fa-eye" id="toggleConfirmPassword"
                                        onclick="togglePassword('confirmPassword', 'toggleConfirmPassword')"></i>
                                </span>
                            </div>
                        </div>
                        <div class="resetbtn mb-5">
                            <button type="submit" class="btn btn-primary">Reset Password</button>
                        </div>
                    </form>



                </div>
            </div>
        </div>
    </div>
@endsection



<script>
    function togglePassword(fieldId, toggleId) {
        const passwordField = document.getElementById(fieldId);
        const toggleIcon = document.getElementById(toggleId);

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>
