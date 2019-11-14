@extends('layouts.admin')

@section('title')
{{ ucwords($page) }}
@endsection

@section('content')
    <router-view></router-view>
@endsection
