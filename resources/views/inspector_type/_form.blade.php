<!-- Name Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.Name')</label>
    {!! Form::text('name', null, ['class' => 'input-body', 'placeholder' => trans('words.Name')]) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<!-- InspectionSubType Form Select -->
<div class="form-group @if ($errors->has('inspection_subtypes_id')) has-error @endif">
    {!! Form::label('inspection_subtypes_id', trans_choice('words.InspectionSubtype', 2)) !!}
    {!!Form::select('inspection_subtypes_id', $inspectionSubtype, isset($inspector_type) ? $inspector_type->inspection_subtypes_id : null, ['class' => 'input-body chosen-select form-control', 'placeholder' => trans('words.ChooseOption')])!!}
    @if ($errors->has('inspection_subtypes_id')) <p class="help-block">{{ $errors->first('inspection_subtypes_id') }}</p> @endif
</div>