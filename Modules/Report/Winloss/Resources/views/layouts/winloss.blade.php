@extends('report/winloss::index')

@section('custom_body')

    <div class="row">
        {{--<div class="col-xs-6 col-md-3">--}}
            {{--<a href="{{ route($gameRoute, 'sa') }}" class="thumbnail">--}}
                {{--<img src="/images/sa.jpg" class="img-responsive">--}}
            {{--</a>--}}
        {{--</div>--}}
        {{--<div class="col-xs-6 col-md-3">--}}
            {{--<a href="{{ route($gameRoute, 'dg') }}" class="thumbnail">--}}
                {{--<img src="/images/dg.jpg" class="img-responsive">--}}
            {{--</a>--}}
        {{--</div>--}}
        <div class="col-xs-6 col-md-3">
            <a href="{{ route($gameRoute, 'aec') }}" class="thumbnail">
                <img src="/images/aec.jpg" class="img-responsive">
            </a>
        </div>
        {{--<div class="col-xs-6 col-md-3">--}}
            {{--<a href="{{ route($gameRoute, 'sexy') }}" class="thumbnail">--}}
                {{--<img src="/images/sexy.jpg" class="img-responsive">--}}
            {{--</a>--}}
        {{--</div>--}}
        <div class="col-xs-6 col-md-3">
            <a href="{{ route($gameRoute, 'csh') }}" class="thumbnail">
                <img src="/images/csh.jpg" class="img-responsive">
            </a>
        </div>
        <div class="col-xs-6 col-md-3">
            <a href="{{ route($gameRoute, 'sboapi') }}" class="thumbnail">
                <img src="/images/sbo.jpg" class="img-responsive">
            </a>
        </div>
        <div class="col-xs-6 col-md-3">
            <a href="{{ route($gameRoute, 'ibc') }}" class="thumbnail">
                <img src="/images/ibc.jpg" class="img-responsive">
            </a>
        </div>
        <div class="col-xs-6 col-md-3">
            <a href="{{ route($gameRoute, 'tfg') }}" class="thumbnail">
                <img src="/images/tfg.jpg" class="img-responsive">
            </a>
        </div>
        <div class="col-xs-6 col-md-3">
            <a href="{{ route($gameRoute, 'lottosh') }}" class="thumbnail">
                <img src="/images/lottosh.jpg" class="img-responsive">
            </a>
        </div>
    </div>

@endsection

@push('css')

@endpush

@push('scripts')

@endpush