@if(isset($routes['import']))
    <div id="modal_records_import" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">@lang($language_file.'.module') - @lang('core::core.crud.import')</h4>

                </div>
                <div class="modal-body">

                    <div class="alert alert-info">
                        <p>
                            @lang('core::core.file_need_to_have_header')
                        </p>
                    </div>
                    <form class="form-horizontal" action="{{ route($routes['import']) }}" method="POST" enctype="multipart/form-data" >
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('csv_file') ? ' has-error' : '' }}">
                            <label for="csv_file" class="col-md-4 control-label">CSV file to import</label>

                            <div class="col-md-6">
                                <input  type="file" class="form-control file_input" name="csv_file" required>

                                @if ($errors->has('csv_file'))
                                    <span class="help-block">
                                                                <strong>{{ $errors->first('csv_file') }}</strong>
                                                            </span>
                                @endif
                            </div>
                        </div>


                        <div class="form group"  >

                            <label for="csv_file" class="col-md-4 control-label">@lang('core::core.column_delimiter')</label>

                            <div class="col-md-6">
                                <select class="select2" name="delimiter">
                                    <option value=";">; - @lang('core::core.semicolon')</option>
                                    <option value=",">, - @lang('core::core.comma')</option>
                                </select>
                            </div>

                        </div>



                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    @lang('core::core.parse_csv')
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endif
