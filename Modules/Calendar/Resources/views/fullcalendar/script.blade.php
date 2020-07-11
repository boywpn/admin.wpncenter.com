@if($include_scripts)
    @include('calendar::fullcalendar.files')
@endif

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#{{ $id }}').fullCalendar({!! $options !!});
    });
</script>
