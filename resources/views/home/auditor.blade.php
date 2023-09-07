@extends('layouts.dashboard')
@section('css')
    <link rel="stylesheet" href="/dashboard/dist/assets/vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css" />
@endsection
@section('custom_content')
    <!-- Start:: content (Your custom content)-->
    <div class="container mt-lg">
        @include('layouts.message')
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12 mb-md">
                        <div class="row">
                            <div class="col-lg-4 mb-md">
                                <div class="card bg-default h-100">
                                    <div class="card-body d-flex align-items-center">
                                        <div
                                            class="ul-cryptocurrency-card d-flex flex-grow-1 justify-content-between align-items-center">
                                            <div>
                                                <p class="m-0">Welcome,</p>
                                                <div class="card-title m-0">
                                                    {{ $user->name() }}</div>
                                            </div>

                                            @if ($auditor->is_verified == 1)
                                                <i class="material-icons text-success">account_circle</i>
                                            @elseif($auditor->is_verified == 0)
                                                <i class="material-icons text-warning">account_circle</i>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 mb-md">
                                <div class="card bg-default h-100">
                                    <div class="card-body d-flex align-items-center">
                                        <div
                                            class="ul-cryptocurrency-card d-flex flex-grow-1 justify-content-between align-items-center">
                                            <div>
                                                <p class="m-0">Num. of Accounts</p>
                                                <div class="card-title m-0">
                                                    45</div>
                                            </div>
                                            <i class="material-icons">manage_accounts</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 mb-md">
                                <div class="card bg-default h-100">
                                    <div class="card-body d-flex align-items-center">
                                        <div
                                            class="ul-cryptocurrency-card d-flex flex-grow-1 justify-content-between align-items-center">
                                            <a href="#">
                                                <p class="m-0">View</p>
                                                <div class="card-title m-0">
                                                    Transaction History
                                                </div>
                                            </a>
                                            <i class="material-icons">history</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- @if ($user->accounts->count() > 0)
                @foreach ($user->accounts as $account)
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12 mb-md">
                                <div class="row">
                                    <div class="col-lg-12 mb-md">
                                        <div class="card bg-default h-100">
                                            <div class="card-body d-flex align-items-center">
                                                <div
                                                    class="ul-cryptocurrency-card d-flex flex-grow-1 justify-content-between align-items-center">
                                                    <div>
                                                        <p class="m-0">{{$account->number}} <span
                                                            class="badge {{ $account->is_active ? 'badge-success' : 'badge-danger' }}">{{ $account->is_active ? 'Active' : 'Inactive' }}</span></p>
                                                        <div class="card-title m-0">
                                                            {{ $account->currency->name .' '. $account->type->name .' Account'}}</div>
                                                    </div>
                                                    <div> Bal. <strong>{{$account->currency->symbol}}@format_amount($account->balance)</strong> <br > Book Bal. <strong>{{$account->currency->symbol}}@format_amount($account->book_balance)</strong> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-danger"> You don't have an account.</div>
            @endif --}}

            {{-- <div class="col-lg-8">
            </div>

            <div class="col-lg-4 mb-md">
            </div> --}}

            <div class="col-lg-12 mb-md">
                <div class="card-header">
                    <h2 class="p-1 m-0 text-16 font-weight-semi">Mini Account Statement</h2>
                </div>
                <div class="card">
                    <div class="card-body">
                        {{-- @if ($user->transactions->count() > 0)
                            <table class="table nowrap" id="datatableScrollXY" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Transaction Date</th>
                                        <th>Account</th>
                                        <th>Narrative</th>
                                        <th>Credit</th>
                                        <th>Debit</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $index = 1; @endphp
                                    @foreach ($transactions as $transaction)
                                        <tr>
                                            <td data-sort="YYYYMMDD">{{ $transaction->created_at->format('m-d-Y') }}</td>
                                            <td>{{ $transaction->account->number }}</td>
                                            <td>{!! $transaction->narrative !!}</td>
                                            <td class="text-success">{{ $transaction->entry == 'credit' ?  number_format($transaction->amount, 2) : '' }}</td>
                                            <td class="text-danger">{{ $transaction->entry == 'debit' ?  number_format($transaction->amount, 2) : '' }}</td>
                                            <td>
                                                @if ($transaction->status == 0)
                                                    <span class="badge badge-warning">Pending</span>
                                                @elseif ($transaction->status == 1)
                                                    <span class="badge badge-success">Completed</span>
                                                @else
                                                    <span class="badge badge-danger">Failed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-danger"> You don't have any Transaction.</div>
                        @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Start:: content (Your custom content)-->

    {{-- Secutity modal --}}

    <div class="modal fade" id="securityModal" tabindex="-1" role="dialog" aria-labelledby="loginModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="loginModal">Security Hint</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-center">
                        <div class="row">
                            <div class="col-md-10 offset-md-1 mb-sm d-flex justify-content-center">
                                <i class="material-icons nav-icon text-64">lock</i>
                            </div>
                            <div class="col-md-10 offset-md-1 mb-sm mt-lg">
                                <strong>Please do not share the following with anyone : </strong>
                                <ul>
                                    <li class="pt-sm">Application login details, password, or token.</li>
                                    <li class="pt-sm">One time OTP or secured passcode.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="/dashboard/dist/assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/dashboard/dist/assets/vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/dashboard/dist/assets/js/pages/datatables/scrollDatatable.min.js"></script>
    <script type="text/javascript">
        $(window).on('load', function() {
            if (!sessionStorage.getItem('shown-modal')) {
                $('#securityModal').modal('show');
                sessionStorage.setItem('shown-modal', 'true');
            }
        });
    </script>
@endsection
