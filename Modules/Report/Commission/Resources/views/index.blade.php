@extends('report/commission::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('report/commission.name') !!}
    </p>
@stop
