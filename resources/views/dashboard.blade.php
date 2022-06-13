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

        <div class="row">
            <div class="col">
                <div class="button-section">
                    <button class="btn btn-primary" id="btnGeo" type="button" onclick="getLocation()">Dapatkan Lokasi</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="target-geo">
                    <p class="lat"></p>
                    <p class="long"></p>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Card-->

@endsection

{{-- begin::scripts --}}
@push('scripts')
    <script>

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else { 
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        function showPosition(position) {
            let lat = position.coords.latitude;
            let long = position.coords.longitude;
            $('.lat').text(lat);
            $('.long').text(long);
        }
    </script>
@endpush
{{-- end::scripts --}}
