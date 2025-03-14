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
            <h2 class="page-title mb-0">User Approval</h2>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="card p-3">
                    <h4 class="mb-3">Total Users: <span class="text-primary">{{ $users->count() }} users</span></h4>
    
                    <!-- User Management Table -->
                    <div class="table-responsive mt-3">
                        <table class="table table-no-border" id="usermanagement">
                            <thead>
                                <tr>
                                    <th scope="col">S/N</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Join Date & Referral</th>
                                    <th scope="col">Email Address</th>
                                    <th scope="col">Phone Number</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}<br>{{ $user->user_id ?? 'N/A' }}</td>
                                        <td>{{ $user->created_at ?? 'N/A' }}<br>{{ $user->referral_id ?? 'N/A' }}</td>
                                        <td>{{ $user->email ?? 'N/A' }}</td>
                                        <td>{{ $user->phone_number ?? 'N/A' }}</td>
                                        <td>
                                            <!-- Accept Button -->
                                            <button class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#statusModal{{ $user->id }}Accept">
                                                Accept
                                            </button>
                                            <!-- Reject Button -->
                                            <button class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#statusModal{{ $user->id }}Reject">
                                                Reject
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <!-- Status Confirmation Modals -->
                        @foreach ($users as $user)
                            <!-- Accept Modal -->
                            <div class="modal fade" id="statusModal{{ $user->id }}Accept" tabindex="-1"
                                aria-labelledby="statusModalLabel{{ $user->id }}Accept" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="statusModalLabel{{ $user->id }}Accept">
                                                Confirm Accept Action
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to accept <strong>{{ $user->name }}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('user_status_update', $user->id) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="accepted">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Confirm</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <!-- Reject Modal -->
                            <div class="modal fade" id="statusModal{{ $user->id }}Reject" tabindex="-1"
                                aria-labelledby="statusModalLabel{{ $user->id }}Reject" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="statusModalLabel{{ $user->id }}Reject">
                                                Confirm Reject Action
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to reject <strong>{{ $user->name }}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('user_status_update', $user->id) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Confirm</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var statusModals = document.querySelectorAll('[id^="statusModal"]');
        statusModals.forEach(function(modal) {
            modal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var status = button.getAttribute('data-status');
                var userId = modal.getAttribute('id').replace('statusModal', '');
                var actionText = status === 'accepted' ? 'accept' : 'reject';
                document.getElementById('actionText' + userId).textContent = actionText;
                document.getElementById('statusInput' + userId).value = status;
            });
        });
    });
</script>
