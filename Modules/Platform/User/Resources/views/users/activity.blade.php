@extends('layouts.app')

@section('content')

    <div class="block-header">
        <h2>@lang('settings::settings.module')</h2>
    </div>

    <div class="row">

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 no-vert-padding" >

            @include('settings::partial.menu')

        </div>

        <div  class="col-lg-9 col-md-9 col-sm-9 col-xs-12 no-vert-padding">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>

                            <a href="{{ route('settings.users.show',$entity->id) }}" class="btn btn-primary btn-create btn-crud">@lang('user::users.back')</a>

                            @lang('user::users.module') - {{$entity->name }}
                            <small>@lang('user::users.activity_log')</small>

                        </h2>


                    </div>
                    <div class="body">

                        <div class="table-responsive  col-lg-12 col-md-12 col-sm-12">
                            {!! $dataTable->table(['width' => '100%']) !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush


