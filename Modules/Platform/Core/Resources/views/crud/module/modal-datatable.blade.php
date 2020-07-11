@extends('layouts.simple')

@section('content')

    {!! $dataTable->table(['width' => '100%']) !!}

@endsection

@push('scripts')

{!! $dataTable->scripts() !!}

@endpush
