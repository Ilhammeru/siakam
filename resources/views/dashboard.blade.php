@extends('layouts.master')

@section('content')

<!--begin::Card-->
<div class="card">

    <div class="card-body">
        {{-- @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif --}}

        {{ __('You are Admin') }}!
        Dashboard Admin
    </div>
</div>
<!--end::Card-->

@endsection
