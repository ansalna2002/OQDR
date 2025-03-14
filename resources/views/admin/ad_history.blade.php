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

    </div>


    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card p-3">
                <h2>Add History</h2>
                <div class="table-responsive mt-3">

                    <table class="table " id="usermanagement">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Video</th>
                                <th>District</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ads as $ad)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($ad->image)
                                            <img src="{{ asset($ad->image) }}" width="100">
                                        @else
                                            No Image
                                        @endif
                                    </td>
                                    <td>
                                        @if ($ad->video)
                                            <video width="100" controls>
                                                <source src="{{ asset($ad->video) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @else
                                            No Video
                                        @endif
                                    </td>
                                    <td>{{ $ad->district }}</td>
                                    <td>{{ $ad->date }}</td>
                                    <td>{{ $ad->time }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm delete-btn"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal"
                                            data-ad-id="{{ $ad->id }}">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <!-- Delete Confirmation Modal (Single Modal) -->
                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this ad?
                                </div>
                                <div class="modal-footer">
                                    <form id="deleteForm" method="POST">
                                        @csrf
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <!-- DataTable JS -->
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
                    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
                    
                    <script>
                        $(document).ready(function () {
                            $('#usermanagement').DataTable(); // Initialize DataTable
                    
                            // Handle delete button click
                            $('.delete-btn').click(function () {
                                let adId = $(this).data('ad-id');
                                let deleteUrl = "{{ route('delete_ad', ':id') }}".replace(':id', adId);
                                $('#deleteForm').attr('action', deleteUrl);
                            });
                        });
                    </script>
                    
                    @endsection