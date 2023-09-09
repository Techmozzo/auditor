@extends('layouts.dashboard')
@section('css')
    <link rel="stylesheet" href="/dashboard/dist/assets/vendors/summernote/summernote-lite.min.css" />
@endsection
@section('custom_content')
    <!-- Start:: content (Your custom content)-->
    <div class="container my-lg d-flex flex-column">
        <div class="card-header d-flex jusitify-space-between">
            <h2 class="p-1 m-0 text-16 font-weight-semi">Create Confirmation Request</h2>
            <div class="flex-grow-1"></div>
            <div>
                <a type="button" class="btn btn-opacity btn-primary btn-sm my-sm mr-sm"
                    href="{{ (auth()->user()->hasRole('admin')) ? route('confirmation-requests.index') :  route('home') }}" title="Back">Back</a>
            </div>
        </div>
        <div class="doc-example d-flex justify-content-center">
            <div class="col-lg-8">
                @include('layouts.message')
                <div class="clearfix">&nbsp;</div>
                <form action="{{ route('confirmation-requests.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label class="control-label" for="name">Company Name</label>
                        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                            value="{{ old('name') }}" required />
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="bank">Bank</label>
                        <select class="form-control @error('bank') is-invalid @enderror" name="bank" id="bank"
                            required>
                            <option selected disabled>Select Bank *</option>
                            @foreach ($banks as $bank)
                                <option value="{{ $bank->id }}" {{ old('bank') == $bank->id ? 'selected' : '' }}>
                                    {{ $bank->name }}</option>
                            @endforeach
                        </select>
                        @error('bank')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="account">
                        @if ($errors->get('account_number') )
                            <div class="alert alert-danger" style="padding: 5px;">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <p style="margin-bottom:0; padding-left:10px;">{{ $errors->get('account_number')[0] }}</p>
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="control-label" for="account_name">Account Name</label>
                            <input type="text" class="form-control"
                                id="account_name" name="account_name[]" value="{{ old('account_name.0') }}" required />
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="account_number">Account Number</label>
                            <input type="text" class="form-control"
                                id="account_number" name="account_number[]" value="{{ old('account_number.0') }}" required />
                        </div>
                    </div>

                    <div class="mt-xxl mb-lg d-flex jusitify-space-between">
                        <div class="flex-grow-1"></div>
                        <div>
                            <button class="btn btn-opacity btn-primary btn-sm my-sm mr-sm add-account"
                                title="Add Account">Add Account</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-md form-group">
                            <label class="control-label" for="opening_period">Opening Period</label>
                            <input type="date" class="form-control @error('opening_period') is-invalid @enderror"
                                id="opening_period" name="opening_period" value="{{ old('opening_period') }}" required />
                            @error('opening_period')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-md form-group">
                            <label class="control-label" for="closing_period">Closing Period</label>
                            <input type="date" class="form-control @error('closing_period') is-invalid @enderror"
                                id="closing_period" name="closing_period" value="{{ old('closing_period') }}" required />
                            @error('closing_period')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-xxl mb-lg"></div>

                    <h2 class="p-1 m-0 text-16 font-weight-semi text-center">Signatory Information</h2>
                    @if ($errors->get('signatory_email'))
                        <div class="alert alert-danger" style="padding: 5px;">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <p style="margin-bottom:0; padding-left:10px;">{{ $errors->get('signatory_email')[0] }}</p>
                        </div>
                    @endif
                    <div class="signatory">
                        <div class="form-group">
                            <label class="control-label" for="signatory_name[]">Name</label>
                            <input type="text" class="form-control" id="signatory_name[]" name="signatory_name[]"
                                value="{{ old('signatory_name.0') }}" required />
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="signatory_email">Email</label>
                            <input type="email" class="form-control " id="signatory_email" name="signatory_email[]"
                                value="{{ old('signatory_email.0') }}" required />
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="signatory_phone[]">Phone</label>
                            <input type="text" class="form-control " id="signatory_phone[]" name="signatory_phone[]"
                                value="{{ old('signatory_phone.0') }}" required />
                        </div>

                        @include('confirmation_requests.signatory_validation_data')

                    </div>

                    <div class="mt-xxl mb-lg d-flex jusitify-space-between">
                        <div class="flex-grow-1"></div>
                        <div>
                            <button class="btn btn-opacity btn-primary btn-sm my-sm mr-sm add-signatory"
                                title="Add Signatory">Add Signatory</button>
                        </div>
                    </div>

                    <div class="form-group col-lg-6" style="margin:auto;">
                        <button type="submit" class="btn btn-opacity btn-primary btn-sm my-sm mr-sm">
                            <i class="fa fa-dot-circle-o"></i> Submit
                        </button>
                        <button type="reset" class="btn btn-opacity btn-danger btn-sm my-sm mr-sm">
                            <i class="fa fa-ban"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-footer">
            Company will be recorded and reflected on user dashboard.
        </div>
    </div>
@endsection

@section('script')
    <script src="/dashboard/dist/assets/vendors/summernote/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.add-signatory').on("click", function() {
                var newSignatoryFields = $("<div></div>").html(`
                    <div class="mt-xxl mb-lg d-flex jusitify-space-between"></div>
                    <h2 class="p-1 m-0 text-16 font-weight-semi text-center">Additional Signatory</h2>
                    <div class="form-group">
                        <label class="control-label" for="signatory_name">Name</label>
                        <input type="text" class="form-control"
                            id="signatory_name" name="signatory_name[]" value="{{ old('signatory_name') }}" required />
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="signatory_email">Email</label>
                        <input type="email" class="form-control"
                            id="signatory_email" name="signatory_email[]" value="{{ old('signatory_email') }}"
                            required />
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="signatory_phone[]">Phone</label>
                        <input type="text" class="form-control"
                            id="signatory_phone[]" name="signatory_phone[]" value="{{ old('signatory_phone') }}"
                            required />
                    </div>

                    <button type="button" class="btn btn-opacity btn-danger btn-sm my-sm mr-sm remove-signatory">Remove</button>
            `);
                $('.signatory').append(newSignatoryFields);
                newSignatoryFields.find("input").focus();
            });

            $("body").on("click", "button.remove-signatory", function() {
                $(this).parent().remove();
            });


            $('.add-account').on("click", function() {
                var newAccountFields = $("<div></div>").html(`
                    <div class="mt-xxl mb-lg d-flex jusitify-space-between"></div>
                    <h2 class="p-1 m-0 text-16 font-weight-semi text-center">Additional Account</h2>
                    <div class="form-group">
                            <label class="control-label" for="account_name">Account Name</label>
                            <input type="text" class="form-control"
                                id="account_name" name="account_name[]" value="{{ old('account_name.0') }}" required />
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="account_number">Account Number</label>
                            <input type="text" class="form-control"
                                id="account_number" name="account_number[]" value="{{ old('account_number.0') }}" required />
                        </div>
                    <button type="button" class="btn btn-opacity btn-danger btn-sm my-sm mr-sm remove-account">Remove</button>
            `);
                $('.account').append(newAccountFields);
                newAccountFields.find("input").focus();
            });

            $("body").on("click", "button.remove-account", function() {
                $(this).parent().remove();
            });
        });
    </script>
@endsection
