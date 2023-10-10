@extends('layouts.dashboard')
@section('css')
@endsection
@section('custom_content')
    <!-- Start:: content (Your custom content)-->
    <div class="container mt-lg">
        @include('layouts.message')
        <div class="row">
            <div class="col-lg-12 mb-md">
                <div class="card-header d-flex jusitify-space-between">
                    <h2 class="p-1 m-0 text-16 font-weight-semi">{{ $confirmation_request->name }} Confirmation requests for
                        {{ $period }} </h2>
                    <div class="flex-grow-1"></div>
                    <div>
                        <a type="button" class="btn btn-opacity btn-primary btn-sm my-sm mr-sm"
                            href="{{ route('confirmation-requests.index') }}" title="Back To Requests">Back</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="mt-l mb-lg"></div>
                        {{-- <div class="d-flex justify-content-center"> --}}
                        <!-- Display the PDF -->
                        <embed src="/store/confirmation_requests/{{$confirmation_request->file}}" type="application/pdf" width="100%" height="600px" />
                        <!-- Action Buttons -->
                        <div>
                            <form action="{{route('nudge.signatory', ['id' => encrypt_helper($confirmation_request->id)])}}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-opacity btn-primary btn-sm my-sm mr-sm">Nudge Signatory</button>
                            </form>
                        </div>
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Start:: content (Your custom content)-->
@endsection
