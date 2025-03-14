@extends('admin.layout')

@section('content')
    <!-- DataTable CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <style>
        .table-no-border {
            border: none;
        }

        .table-no-border th,
        .table-no-border td {
            border: none;
        }

        .table-responsive .dataTables_wrapper .dataTables_length select {
            padding: 8px 48px 8px 16px !important;
        }
    </style>

    <div class="container-fluid content-inner pb-0">
        
        <div class="page-header d-flex justify-content-between align-items-center">
            <h2 class="page-title mb-0">Membership History</h2>
        </div>
        

        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="card p-3">
                    <h4 class="mb-3">Membership History: <span class="text-primary">{{ $users->count() }} users</span></h4>
    
                    <div class="table-responsive mt-3">


                        <table class="table table-no-border" id="usermanagement">
                            <thead>
                                <tr>
                                    <th scope="col">S/no</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Approved Date</th>
                                    <th scope="col">Transaction ID</th>
                                    <th scope="col">Screenshot</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}<br>{{ $user->user_id ?? 'N/A' }}</td>
                                        <td>
                                            {{ $user->amount }} <br>
                                            @if ($user->coin_redeem)
                                                <span class="text-success">{{ $user->coin_redeem }} Coin redeem</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($user->admin_approved_date)->format('d M Y ') }}</td>
                                        <td>{{ $user->transaction_id ?? 'N/A' }}</td>
                                        <td><a href="{{ asset($user->payment_image) }}"
                                                target="_blank">View Screenshot</a>
                                                {{-- <img src="{{ asset($user->payment_image) }}" alt="Payment Screenshot" width="100"> --}}
                                            </td>
                                            <td>
                                                @if ($user->status == 'Accepted')
                                                    <span class="badge bg-success">Accepted</span>
                                                @elseif ($user->status == 'Rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
       

    </div>
@endsection

<!-- DataTable JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#usermanagement').DataTable();
    });
</script>
<!-- Pagination -->
<div class="d-flex justify-content-between mt-3">
    {{ $users->links() }}
</div>
