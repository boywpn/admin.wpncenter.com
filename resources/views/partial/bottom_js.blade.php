
<!-- Scripts -->
{!! Packer::js([
    '/bap/plugins/jquery/jquery.min.js',
    '/bap/plugins/jquery.i18n.js',
    '/bap/js/trans/'.app()->getLocale().'.js',

    '/bap/plugins/bootstrap/js/bootstrap.js',
    '/bap/plugins/bootstrap-select/js/bootstrap-select.js',
    '/bap/plugins/jquery-slimscroll/jquery.slimscroll.js',
    '/bap/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js',
    '/bap/plugins/node-waves/waves.js',
    '/bap/plugins/bootstrap-notify/bootstrap-notify.js',
    '/bap/plugins/jquery.number.min.js',
    '/bap/plugins/jquery-datatable/jquery.dataTables.js',
    '/bap/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js',
    '/bap/plugins/jquery-datatable/extensions/responsive/js/dataTables.responsive.js',
    '/bap/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js',
    '/bap/plugins/jquery-datatable/extensions/export/buttons.html5.min.js',
    '/bap/plugins/jquery-datatable/extensions/export/buttons.print.min.js',
    '/bap/plugins/jquery-datatable/extensions/export/jszip.min.js',
    '/bap/plugins/jquery-datatable/extensions/export/pdfmake.min.js',
    '/bap/plugins/jquery-datatable/extensions/export/vfs_fonts.js',
    '/bap/plugins/offlinejs/offline.min.js',
    '/bap/plugins/select2-4.0.3/dist/js/select2.full.min.js',
    '/bap/plugins/bootstrap-fileinput/js/fileinput.min.js',
    '/bap/plugins/momentjs/moment.js',
     '/bap/plugins/momentjs/locale/'.app()->getLocale().'.js',
    '/bap/plugins/bootstrap-daterangepicker/daterangepicker.js',
    '/bap/plugins/jquery-countto/jquery.countTo.js',
    '/bap/plugins/bootstrap-datetimepicker/dist/js/bootstrap-datetimepicker.min.js',
    '/bap/plugins/jquery-comments/js/jquery.textcomplete.min.js',
    '/bap/plugins/jquery-comments/js/jquery-comments.min.js',
    '/bap/plugins/jquert-query-builder/js/query-builder.standalone.js',
    '/bap/plugins/js.cookie.js',
    '/bap/js/BapConfig.js',
    '/bap/js/BapDatatable.js',
    '/bap/js/BapPlatform.js',
    '/bap/plugins/jquery-datatable/yadcf/jquery.dataTables.yadcf.js',
    '/bap/plugins/jquery-jscroll/jquery.jscroll.min.js',
    '/bap/plugins/select-list-action/jquery.selectlistactions.js',
    '/bap/plugins/bootstrap-tagsinput/typeahead.bundle.js',
    '/bap/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js',

    '/bap/js/admin.js',
    '/bap/js/Common.js',

    '/modules/notifications/js/BAP_Notifications.js'
    ],
    '/storage/cache/js/'
) !!}

<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

