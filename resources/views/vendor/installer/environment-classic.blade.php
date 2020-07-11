@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.environment.classic.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-code fa-fw" aria-hidden="true"></i> {{ trans('installer_messages.environment.classic.title') }}
@endsection

@section('container')

    <?php
      $installationValidator = new \Modules\Platform\Core\Helper\Installer\InstallerValidator();
      $installationValidator->validate();
    ?>

    <div class="msg-box">

        <div class="{{  $installationValidator->valid ? 'success' : 'error' }}">
            {{ $installationValidator->message }}
        </div>

    </div>

    <form method="post" action="{{ route('LaravelInstaller::environmentSaveClassic') }}">
        {!! csrf_field() !!}
        <textarea class="textarea" name="envConfig">{{ $envConfig }}</textarea>
        <div class="buttons buttons--right">
            <button class="button " type="submit">
            	<i class="fa fa-floppy-o fa-fw" aria-hidden="true"></i>
             	{!! trans('installer_messages.environment.classic.save') !!}
            </button>
            @if($installationValidator->valid)
                    <a style="font-size: 20px;" class="button float-right" href="{{ route('LaravelInstaller::database') }}">
                        <i class="fa fa-check fa-fw" aria-hidden="true"></i>
                        {!! trans('installer_messages.environment.classic.install') !!}
                        <i class="fa fa-angle-double-right fa-fw" aria-hidden="true"></i>
                    </a>
            @endif
        </div>
    </form>




@endsection