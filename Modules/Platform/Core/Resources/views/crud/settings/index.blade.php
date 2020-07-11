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

                            <div class="header-buttons">
                             <a href="{{ route($routes['create']) }}" class="btn btn-primary btn-create btn-crud">@lang('core::core.crud.create')</a>
                            </div>

                            <div class="header-text">
                                @lang($language_file.'.module')
                                <small>@lang($language_file.'.module_description')</small>
                            </div>

                        </h2>


                    </div>
                    <div class="body">

                        <div class="table-responsive  col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            {!! $dataTable->table(['width' => '100%']) !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection

@push('scripts')
    @foreach($jsFiles as $jsFile)
        <script src="{!! Module::asset($moduleName.':js/'.$jsFile) !!}"></script>
    @endforeach
@endpush

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush


