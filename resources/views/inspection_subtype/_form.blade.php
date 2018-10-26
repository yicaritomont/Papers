<!-- Name Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.InspectionType')</label>
    {!! Form::select('inspection_type_id',$inspection_types,null, array('class' => 'chosen-select form-control','require')) !!}
    @if ($errors->has('inspection_type')) <p class="help-block">{{ $errors->first('inspection_type') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.Name')</label>
    {!! Form::text('name', null, ['class' => 'input-body', 'placeholder' => 'Name']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>
