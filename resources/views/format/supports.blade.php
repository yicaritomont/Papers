@extends('layouts.app')

@section('title', Lang::get('words.manage').' '.Lang::get('words.supports') )

@section('content')

<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1">
    <div id="buttonsPanel">
        <a href="{{ route('formats.index') }}" class="btn btn-default"> <i class="fa fa-arrow-left"></i> @lang('words.Back')</a>
    </div>
    <div class="panel panel-default">
        <div class="panel-header-form">
            <h3 class="panel-titles">@lang('words.manage') @lang('words.supports')</h3>
        </div>
        <div class="panel-body black-letter content-over">
            <form method="POST">
                @csrf
                <input type="hidden" name="formato" value="{{ $formato->id }}" />
                <label for="input-supports">@lang('words.supports')</label>
                <div class="file-loading">
                    <input id="input-supports" name="input-supports[]" type="file" multiple>
                </div>
                <div id="kartik-file-errors"></div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{asset('js/upload.js')}}"></script>
@endsection
