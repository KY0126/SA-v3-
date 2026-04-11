@php
$activePage = 'dashboard';
$_roles = ['admin'=>'課指組/處室','officer'=>'社團幹部','professor'=>'指導教授','student'=>'一般學生','it'=>'資訊中心'];
$_title = 'Dashboard - ' . ($_roles[$role] ?? '一般學生');
@endphp
@extends('layouts.shell')
@section('title', $_title)
@section('content')
@include('pages.partials.dashboard_content', ['role' => $role])
@endsection
