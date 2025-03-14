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
        {{-- <div class="page-header d-flex justify-content-between">
            <h2 class="page-title mb-0">Membership Approval</h2>
        </div> --}}
        <div class="page-header d-flex justify-content-between align-items-center">
            <h2 class="page-title mb-0">Membership Approval</h2>
            <a href="{{ route('membership_history') }}" class="btn btn-primary">History</a>
        </div>
        

        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="card p-3">
                    <h4 class="mb-3">New Membership Users: <span class="text-primary">{{ $users->count() }} users</span></h4>
    
                    <div class="table-responsive mt-3">


                        <table class="table table-no-border" id="usermanagement">
                            <thead>
                                <tr>
                                    <th scope="col">S/no</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Applied Date</th>
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
                                        <td>{{ \Carbon\Carbon::parse($user->applied_date)->format('d M Y') }}</td>
                                        <td>{{ $user->transaction_id ?? 'N/A' }}</td>
                                        <td><a href="{{ asset($user->payment_image) }}"
                                                target="_blank">View Screenshot</a>
                                                {{-- <img src="{{ asset($user->payment_image) }}" alt="Payment Screenshot" width="100"> --}}
                                            </td>
                                            <td>
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#AcceptModal"
                                                    class="btn transparent-btn text-success text-decoration-underline"
                                                    data-user-id="{{ $user->id }}"
                                                    onclick="setAcceptId(this)">Accept</button>
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#DeclineModal"
                                                    class="btn transparent-btn text-danger text-decoration-underline"
                                                    data-user-id="{{ $user->id }}"
                                                    onclick="setRejectId(this)">Decline</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
          <!-- Accept Modal -->
          <div class="modal fade zoom-in" id="AcceptModal" tabindex="-1" aria-labelledby="AcceptModalLabel"
          aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-body">
                      <div class="text-center">
                          <img class="mb-3" style="height: 75px;" src="{{ asset('/assets/images/gif/accept.png') }}">
                          <p class="my-4 are-you-sure">Are You Sure</p>
                          <p class="text-muted my-2 are-you-sure-subtext">Are you sure you want to accept this order?</p>
                      </div>
                      <form action="{{ route('approve_membership') }}" method="POST" id="AcceptForm">
                          @csrf
                          <input type="hidden" id="accept_id" name="accept_id">
                          <div class="d-flex align-items-center mt-5 mb-3">
                              <button type="button" data-bs-dismiss="modal"
                                  class="btn btn-light cancel-btn me-3">Cancel</button>

                              <button type="button" class="btn btn-primary yes-btn" id="submitButton"
                              onclick="AcceptButton(this)"><i
                                      class="fa-regular fa-circle-check me-2 "></i>Yes, Accept It!</button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>

      <!-- Decline Modal -->
      <div class="modal fade zoom-in" id="DeclineModal" tabindex="-1" aria-labelledby="DeclineModalLabel"
          aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-body">
                      <div class="text-center">
                          <img class="mb-3" style="height:75px;" src="{{ asset('/assets/images/gif/reject.png') }}">
                          <p class="my-4 are-you-sure">Are You Sure</p>
                          <p class="text-muted my-2 are-you-sure-subtext">Are you sure you want to decline this order?</p>
                      </div>
                      <form action="{{ route('reject_membership') }}" method="POST" id="RejectForm">
                          @csrf
                          <input type="hidden" id="reject_id" name="reject_id">
                          <div class="d-flex align-items-center mt-5 mb-3">
                              <button type="button" data-bs-dismiss="modal"
                                  class="btn btn-light cancel-btn me-3">Cancel</button>
                              <button type="button" class="btn btn-primary yes-btn" id="submitButton"
                              onclick="RejectButton(this)"><i
                                      class="fa-regular fa-circle-check me-2" ></i>Yes, Decline It!</button>
                          </div>
                      </form>
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

<script>
    function setAcceptId(button) {
        var acceptId = button.getAttribute('data-user-id');
        document.getElementById('accept_id').value = acceptId;
    }
</script>
<script>
    function setRejectId(button) {
        var rejectId = button.getAttribute('data-user-id');
        document.getElementById('reject_id').value = rejectId;
    }
</script>

<script>
    function RejectButton(button) {
        button.disabled = true;
        button.innerText = 'Processing...';
        document.getElementById('RejectForm').submit();
    }
</script>
<script>
    function AcceptButton(button) {
        button.disabled = true;
        button.innerText = 'Processing...';
        document.getElementById('AcceptForm').submit();
    }
</script>


