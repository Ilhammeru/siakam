@extends('email_template.layout')
@section('content')
    {!! $message !!}
    <p><strong>{{ $setting->where('name', 'app_name')->first()->value }}</strong></p>
@endsection