@extends('admin.layout')
<style>
    /* Dashboard Page CSS */

/* Page Title */
.page-title {
    font-family: 'Lato', sans-serif;
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 1rem;
}

h5.wallet.card-title {
    font-size: 16px;
    color: #B3B4BA;
}


.wallet.card {
    height: 150px;
    margin: 0px;
    background: #ffff;
    display: flex;
    justify-content: space-between;
}

p.wallet.card-text {
    font-size: 28px;
    color: #000;
    margin: 1px;
    bottom: 30px;
    position: absolute;
    font-weight: bold;
}
.icon-container {
    position: absolute;
    right: 20px;
    top: 20px;
}

</style>
@section('content')


<div class="container-fluid content-inner pb-0">
    <!-- Page Title -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <h2 class="page-title">Dashboard</h2>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Order Count Card -->
        <div class="row mb-4">
            <!-- Total Users Card -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <div class="wallet card">
                    <div class="card-body">
                        <div class="icon-container">
                            <img src="{{ asset('/assets/images/icons/contact.svg') }}" alt="User Icon" class="card-icon">
                        </div>
                        <h5 class="wallet card-title">Total Users</h5>
                        <p class="wallet card-text">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>
        
            <!-- Free Users (Non-Membership) Card -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <div class="wallet card">
                    <div class="card-body">
                        <div class="icon-container">
                            <img src="{{ asset('/assets/images/icons/contact.svg') }}" alt="Free Users Icon" class="card-icon">
                        </div>
                        <h5 class="wallet card-title">Free Users</h5>
                        <p class="wallet card-text">{{ $freeUsers }}</p>
                    </div>
                </div>
            </div>
        
            <!-- Membership Users Card -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                <div class="wallet card">
                    <div class="card-body">
                        <div class="icon-container">
                            <img src="{{ asset('/assets/images/icons/contact.svg') }}" alt="Membership Users Icon" class="card-icon">
                        </div>
                        <h5 class="wallet card-title">Membership Users</h5>
                        <p class="wallet card-text">{{ $membershipUsers }}</p>
                    </div>
                </div>
            </div>
        </div>
        
    



</div>





@endsection

