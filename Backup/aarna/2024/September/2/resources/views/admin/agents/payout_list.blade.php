@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="card">
                <div class="card-header justify-content-between d-flex card-no-border">
                    <h4>Payout List</h4>
                    @if (Auth::user()->role == 1)
                        <a href="{{ route('admin.create.payout') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add
                            Payout</a>
                    @else
                        @foreach ($permissions as $permission)
                            @if ($permission->capability == 'payout-create' && $permission->value == 1)
                                <a href="{{ route('admin.create.payout') }}" class="btn btn-primary"><i class="fa fa-plus"></i>
                                    Add Payout</a>
                            @endif
                        @endforeach
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive custom-scrollbar">
                        <table class="display border" id="basic-1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sourcing Agent</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Disbursement Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!blank($payouts))
                                    @foreach ($payouts as $payout)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $payout->agents->first_name . ' ' . $payout->agents->last_name }}</td>
                                            <td>{{ date('d-m-Y', strtotime($payout->start_date)) }}</td>
                                            <td>{{ date('d-m-Y', strtotime($payout->end_date)) }}</td>
                                            <td>
                                                @if (!blank($payout->disbursement_date))
                                                    {{ date('d-m-Y', strtotime($payout->disbursement_date)) }}
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex align-items-center">
                                                    <form action="{{ route('admin.download.policy.payout') }}"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="payout_id" value="{{ $payout->id }}">
                                                        <input type="hidden" name="type" value="pdf">
                                                        <button type="submit" class="btn btn-danger me-2"
                                                            title="PDF Report"><i class="fa fa-file-pdf-o"></i></button>
                                                    </form>
                                                    <form action="{{ route('admin.download.policy.payout') }}"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="payout_id" value="{{ $payout->id }}">
                                                        <input type="hidden" name="type" value="csv">
                                                        <button type="submit" class="btn btn-success me-2"
                                                            title="Excel Report"><i class="fa fa-file-excel-o"></i></button>
                                                    </form>
                                                    <div class="">
                                                        <a href="{{ route('admin.view.payout', $payout->id) }}"
                                                            title="View"><i class="fa fa-eye"></i></a>
                                                        @if ($payout->disbursement_date == '')
                                                            <a href="{{ route('admin.edit_payout_list', $payout->id) }}"
                                                                title="Edit"><i class="fa fa-pencil"></i></a>
                                                            <a href="javascript:void(0);" class="payoutDisburse"
                                                                data-bs-toggle="modal" data-id="{{ $payout->id }}"
                                                                data-bs-target="#disburseModal">
                                                                <i class="fa fa-clock-o"></i>
                                                                <!--<img src="{{ url('/') }}/assets/Images/disburse.png" alt="" class="ed_btn me-2">-->
                                                            </a>
                                                        @endif
                                                        <a href="javascript:void(0);" data-id="{{ $payout->id }}"
                                                            title="Delete" class="delete-btn"><i
                                                                class="fa fa-trash-o"></i></a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Disburse Modal -->
    <div class="modal fade" id="disburseModal" tabindex="-1" aria-labelledby="disburseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="disburseModalLabel">DISBURSE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.disburse.payout') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="col-md-12">
                            <label for="Amount" class="form-label">Disbursement Amount *</label>
                            <input type="number" class="form-control" name="amount" id="Amount"
                                value="{{ old('amount') }}" placeholder="" required />
                            <span class="text-danger"></span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="PaymentType" class="form-label">Payment Type</label>
                            <select name="payment_type" class="form-control p-2" id="PaymentType">
                                <option value="cash">Cash</option>
                                <option value="online">Online</option>
                                <option value="cheque">Cheque</option>
                            </select>
                        </div>
                        <div class="col-md-12 mt-2 mb-2">
                            <label for="Comment" class="form-label">Comment</label>
                            <input type="text" class="form-control" name="comment" id="Comment"
                                value="{{ old('comment') }}" placeholder="" autofocus />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" class="payoutId" value="">
                        {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete-btn', function() {
                var user_id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('delete.payout', '') }}" + "/" + user_id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: "Payout has been deleted.",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            }
                        });
                    }
                });
            });
            $(document).on('click', '.payoutDisburse', function() {
                var id = $(this).data('id');
                $('.payoutId').val(id);
                $.ajax({
                    url: "{{ route('get.payout.amount', '') }}" + "/" + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#Amount').val(data);
                    }
                });
            });
        });
    </script>
@endsection
