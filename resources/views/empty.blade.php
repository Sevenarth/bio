@extends('layouts.app')

@section('header') @endsection
@section('footer') @endsection

@section('content')
<div class="container">
@if(session('status'))
<div class="alert alert-success">
    <h3 class="m-0">{{ session('status') }}</h3>
</div>
@endif
</div>
@endsection