<!-- Name of Contract Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('words.Name')) !!}
    {!! Form::text('name', null, ['class' => 'input-body']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<!-- Date of Contract Form Date -->
<div class="form-group @if ($errors->has('date')) has-error @endif">
    {!! Form::label('date', trans('words.Date')) !!}
    <div class="input-group date">
        {!! Form::text('date', null, ['class' => 'form-control input-date', 'autocomplete' => 'off']) !!}
        <span class="input-group-addon" style="background-color: #eee !important;cursor:pointer"><i class="glyphicon glyphicon-th"></i></span>
    </div>
    @if ($errors->has('date')) <p class="help-block">{{ $errors->first('date') }}</p> @endif
</div>

<!-- Client of Contract Form Select -->
<div class="form-group @if ($errors->has('client_id')) has-error @endif">
    {!! Form::label('client_id', trans('words.Client')) !!}
    {!!Form::select('client_id', $clients, isset($contract) ? $contract->client_id : null, ['class' => 'input-body', 'placeholder' => trans('words.ChooseOption')])!!}
    @if ($errors->has('client_id')) <p class="help-block">{{ $errors->first('client_id') }}</p> @endif
</div>

<!-- Company of Contract Form Select -->
<div class="form-group @if ($errors->has('company_id')) has-error @endif">
    {!! Form::label('company_id', trans_choice('words.Company', 1)) !!}
    {!!Form::select('company_id', $companies, isset($contract) ? $contract->company_id : null, ['class' => 'input-body', 'placeholder' => trans('words.ChooseOption')])!!}
    @if ($errors->has('company_id')) <p class="help-block">{{ $errors->first('company_id') }}</p> @endif
</div>

@push('scripts')
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    
@endpush
