@extends('layouts.master')

@section('page-title')
    Upload Leads
@endsection

@section('content')
    <upload-leads
            :files="{{ $files }}"
            :custom_fields="{{ $customFields }}"
    ></upload-leads>
@endsection
