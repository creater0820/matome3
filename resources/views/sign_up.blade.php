@extends('layouts.sign_up_layout')
@section('addSASS')
<link href="{{ asset('css/sign_up.scss') }}" rel="stylesheet">
@endsection

@foreach($data as $datas)
{{$datas}}
@endforeach