<link href="{{ asset('css/app.css') }}" rel="stylesheet">

@extends('app')

@section('timeline')
  <x-timeline :processes="$processes"></x-timeline>
@endsection

@section('report')
  <x-report :processes="$processes"></x-report>
@endsection
