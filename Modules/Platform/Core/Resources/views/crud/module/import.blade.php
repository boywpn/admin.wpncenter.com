@extends('layouts.app')

@section('content')

    <div class="row">


        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        <div class="header-buttons">
                            <a href="{{ route($routes['index']) }}" title="@lang('core::core.crud.back')" class="btn btn-primary btn-back btn-crud">@lang('core::core.crud.back')</a>
                        </div>

                        <div class="header-text">
                            @lang($language_file.'.module') - @lang('core::core.crud.import')
                            <small>@lang($language_file.'.module_description')</small>
                        </div>

                    </h2>


                </div>
                <div class="body">

                    <form class="form-horizontal" method="POST" action="{{ route($routes['import_process'])  }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="file_id" value="{{ $csvDataFile->id }}" />

                        <div class="table-responsive">


                            <table class="table table-bordered table-striped">

                                @if(isset($csv_header))
                                    <tr>
                                        @foreach ($csv_header as $csv_header_field)
                                            <th>{{ $csv_header_field }}</th>
                                        @endforeach
                                    </tr>
                                @endif

                                @foreach ($csv_data as $row)
                                    <tr>
                                        @foreach ($row as $value)
                                            <td>{{ $value }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                <tr>
                                    @foreach ($csv_data[0] as $value)
                                        <td>
                                            <select style="min-width: 250px" class="select2" name="fields[{{ $value }}]">
                                                    <option value="">@lang('core::core.empty')</option>
                                                @foreach ($module_fields as $mkey => $db_field)
                                                    <option @if($value == $db_field) selected="selected" @endif value="{{ $db_field }}"> @lang($language_file.'.form.'.$db_field) ({{ $db_field }})</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    @endforeach
                                </tr>
                            </table>

                        </div>

                        <br />
                        <input type="submit" class="btn btn-primary" value="@lang('core::core.btn.process_import')" />


                    </form>

                </div>
            </div>
        </div>


        @foreach($includeViews as $v)
            @include($v)
        @endforeach


        @endsection

        @push('css')
            @foreach($cssFiles as $file)
                <link rel="stylesheet" href="{!! Module::asset($moduleName.':css/'.$file) !!}"></link>
            @endforeach
        @endpush

    @push('scripts')
        @foreach($jsFiles as $jsFile)
            <script src="{!! Module::asset($moduleName.':js/'.$jsFile) !!}"></script>
        @endforeach
    @endpush


