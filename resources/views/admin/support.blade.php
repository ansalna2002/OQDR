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
    <div class="page-header d-flex justify-content-between">
        <h2 class="page-title mb-0">User Management</h2>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card p-3">
                <div class="table-responsive mt-3">
                    <table class="table table-no-border" id="usermanagement">
                        <thead>
                            <tr>
                                <th scope="col">S/N</th>
                                <th scope="col">User Id</th>
                                <th scope="col">Queries</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($supports as $support)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $support->user_id }}</td>
                                <td>{{ $support->query }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3">No queries found</td>
                            </tr>
                            @endforelse
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
    $(document).ready(function () {
        $('#usermanagement').DataTable();
    });
</script>
