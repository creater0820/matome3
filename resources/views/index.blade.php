@extends('layouts.layout')

@section('addSASS')
<link href="{{ asset('css/sign_up.css') }}" rel="stylesheet">
@endsection

@section('addCSS')


@endsection

@section('content')
@parent
ここは子の要素です
@endsection