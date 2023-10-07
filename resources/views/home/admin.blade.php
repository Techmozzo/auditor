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
                                                    {{ $auditor->name() }}</div>
                                            </div>

                                            @switch($auditor)
                                                @case($auditor->is_verified == 1)
                                                    <i class="material-icons text-success">account_circle</i>
                                                @break

                                                @case($auditor->is_verified == 0)
                                                    <i class="material-icons text-warning">account_circle</i>
                                                @break

                                                @case($auditor->is_verified == 2)
                                                    <i class="material-icons text-danger">account_circle</i>
                                                @break

                                                @default
                                                    <i class="material-icons">account_circle</i>
                                            @endswitch

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
                                                <p class="m-0">Num. of Auditors</p>
                                                <div class="card-title m-0">
                                                    {{ $number_of_auditors }}</div>
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
                                            <div>
                                                <p class="m-0">Pending request</p>
                                                <div class="card-title m-0">
                                                    {{ $pending_requests->count() }}</div>
                                            </div>
                                            <i class="material-icons text-warning">pending_actions</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- <div class="col-lg-8">
            </div>

            <div class="col-lg-4 mb-md">
            </div> --}}

            <div class="col-lg-12 mb-md">
                <div class="card-header d-flex jusitify-space-between">
                    <h2 class="p-1 m-0 text-16 font-weight-semi">Pending Confirmation Reqeuests</h2>
                    <div class="flex-grow-1"></div>
                    <div>
                        <a type="button" class="btn btn-opacity btn-primary btn-sm my-sm mr-sm"
                            href="{{ route('confirmation-requests.create') }}" title="Create Request">Create Request</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        @if ($pending_requests->count() > 0)
                            <table class="table nowrap" id="datatableScrollXY" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Period</th>
                                        <th>Initiator</th>
                                        <th>Authorization</th>
                                        <th>Confirmation</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pending_requests as $request)
                                        <tr>
                                            <td>{{ $request->name }}</td>
                                            <td>{{ getPeriodDayAndMonth($request->opening_period) . ' ' . getYearsRangeInStringFormat(getYearsInRange($request->opening_period, $request->closing_period)) }}
                                            </td>
                                            <td>{{ $request->auditor->name() }}</td>
                                            <td>
                                                @switch($request->authorization_status)
                                                    @case($request->authorization_status == 'APPROVED')
                                                        <span class="badge badge-success">APPROVED</span>
                                                    @break

                                                    @case($request->authorization_status == 'CANCELLED')
                                                        <span class="badge badge-danger">CANCELLED</span>
                                                    @break

                                                    @case($request->authorization_status == 'DECLINED')
                                                        <span class="badge badge-danger">DECLINED</span>
                                                    @break

                                                    @default
                                                        <span class="badge badge-warning">PENDING</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @switch($request->confirmation_status)
                                                    @case($request->confirmation_status == 'APPROVED')
                                                        <span class="badge badge-success">APPROVED</span>
                                                    @break

                                                    @case($request->confirmation_status == 'CANCELLED')
                                                        <span class="badge badge-danger">CANCELLED</span>
                                                    @break

                                                    @case($request->confirmation_status == 'DECLINED')
                                                        <span class="badge badge-danger">DECLINED</span>
                                                    @break

                                                    @default
                                                        <span class="badge badge-warning">PENDING</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="{{ url('/confirmation-requests/' . encrypt_helper($request->id) . '/edit') }}"
                                                    title="Edit request"><span class="material-icons">edit_note</span></a>
                                                &nbsp;
                                                <a class="text-primary"
                                                    href="{{ route('confirmation-requests.show', encrypt_helper($request->id)) }}"
                                                    title="View request"><span class="material-icons">visibility</span></a>
                                                &nbsp;
                                                <a class="delete-item text-danger"
                                                    data-id="{{ encrypt_helper($request->id) }}" href="#"
                                                    title="Delete request"><span class="material-icons">delete</span></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-danger"> There is no pending request.</div>
                        @endif
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
