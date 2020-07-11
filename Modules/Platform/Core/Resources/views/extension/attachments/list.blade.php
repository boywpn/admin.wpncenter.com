<!-- The file upload form used as target for the file upload widget -->
<form id="fileupload" action="{{ route('core.ext.attachments.upload-attachments') }}" method="POST" enctype="multipart/form-data">


    <input type="hidden" name="path" value="{{ route($routes['show'],$entityIdentifier) }}" />
    <input type="hidden" name="entityId" value="{{ $entity->id }}" />
    <input type="hidden" name="entityClass" value="{{ get_class($entity) }}" />
    <!-- Redirect browsers with JavaScript disabled to the origin page -->

@if($permissions['update'] == '' or Auth::user()->hasPermissionTo($permissions['update']))
    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
    <div class="row fileupload-buttonbar">
        <div class="col-lg-12">
            <p class="font-bold">
                @lang('core::core.allowed_file_types') <span class="col-red">{{ config('bap.allowe_file_types_message') }}</span>
            </p>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6">
            <!-- The fileinput-button span is used to style the file input field as button -->



                <span class="btn btn-success fileinput-button">
                        <i class="glyphicon glyphicon-plus"></i>
                        <span>@lang('core::core.add_files')</span>
                        <input type="file" name="files" multiple>
                    </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>@lang('core::core.start_upload')</span>
                </button>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>@lang('core::core.cancel_upload')</span>
                </button>

            <!-- The global file processing state -->
            <span class="fileupload-process"></span>
        </div>
        <!-- The global progress state -->
        <div class="col-lg-6 col-sm-6 col-md-6 fileupload-progress fade">
            <!-- The global progress bar -->
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
            </div>
            <!-- The extended global progress state -->
            <div class="progress-extended">&nbsp;</div>
        </div>
    </div>

@else
        <div class="col-lg-12">
            <p class="font-bold">
                @lang('core::core.entity.you_dont_have_permission_to_edit')
            </p>
        </div>

@endif
    <!-- The table listing the files available for upload/download -->
    <div class="table-responsive col-lg-12 col-md-12">
        <table role="presentation" class="table table-striped">
            <thead>
                <th>
                    @lang('core::core.file_preview')
                </th>
                <th>
                    @lang('core::core.file_name')
                </th>
                <th>
                    @lang('core::core.file_size')
                </th>
                <th>
                    @lang('core::core.file_options')
                </th>
            </thead>
            <tbody class="files"></tbody>
        </table>
    </div>
</form>


@push('css')

<link href="{{ asset('bap/plugins/jquery-file-upload/css/jquery.fileupload.css') }}" rel="stylesheet"/>
<link href="{{ asset('bap/plugins/jquery-file-upload/css/jquery.fileupload-ui.css') }}" rel="stylesheet"/>

@endpush


@push('scripts')

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">@lang('core::core.processing')</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>@lang('core::core.start')</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>@lang('core::core.cancel')</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}"><img width="50" src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}">{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            @if($permissions['update'] == '' or Auth::user()->hasPermissionTo($permissions['update']))
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>@lang('core::core.delete')</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>@lang('core::core.cancel')</span>
                </button>
            {% } %}
            @endif
        </td>
    </tr>
{% } %}
</script>

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="{{ asset('bap/plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js') }}"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->

<!-- The Templates plugin is included to render the upload/download listings -->
<script src="{{ asset('bap/plugins/jquery-file-upload/js/vendor/tmpl.min.js') }}"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="{{ asset('bap/plugins/jquery-file-upload/js/vendor/load-image.all.min.js') }}"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="{{ asset('bap/plugins/jquery-file-upload/js/vendor/canvas-to-blob.min.js') }}"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->

<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="{{ asset('bap/plugins/jquery-file-upload/js/jquery.iframe-transport.js') }}"></script>
<!-- The basic File Upload plugin -->
<script src="{{ asset('bap/plugins/jquery-file-upload/js/jquery.fileupload.js') }}"></script>

<!-- The File Upload processing plugin -->
<script src="{{ asset('bap/plugins/jquery-file-upload/js/jquery.fileupload-process.js') }}"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="{{ asset('bap/plugins/jquery-file-upload/js/jquery.fileupload-image.js') }}"></script>

<!-- The File Upload audio preview plugin -->
<script src="{{ asset('bap/plugins/jquery-file-upload/js/jquery.fileupload-audio.js') }}"></script>
<!-- The File Upload video preview plugin -->
<script src="{{ asset('bap/plugins/jquery-file-upload/js/jquery.fileupload-video.js') }}"></script>
<!-- The File Upload validation plugin -->
<script src="{{ asset('bap/plugins/jquery-file-upload/js/jquery.fileupload-validate.js') }}"></script>
<!-- The File Upload user interface plugin -->
<script src="{{ asset('bap/plugins/jquery-file-upload/js/jquery.fileupload-ui.js') }}"></script>

<script>
    jQuery(document).ready(function() {
        // Demo settings:
        $('#fileupload').fileupload({

            maxFileSize: 20000000,
            acceptFileTypes: /(\.|\/)({{ config('bap.file_upload_types')}})$/i,
        });

        // Load existing files:
        $('#fileupload').addClass('fileupload-processing');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '{{ route('core.ext.attachments.get-attachments') }}',
            'type': 'post',
            dataType: 'json',
            context: $('#fileupload')[0],
            data: {
                entityId: "{{ $entityIdentifier }}",
                entityClass: "{{ json_encode(get_class($entity)) }}",

            }
        }).always(function (e, data) {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), { result: result });
        });
    });
</script>
@endpush