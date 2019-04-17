@extends('layouts.master')

@section('page-title')
    Global Custom Fields
@endsection

@section('content')
    <custom-fields :groups="{{ $customFieldGroups }}"
    ></custom-fields>
@endsection
