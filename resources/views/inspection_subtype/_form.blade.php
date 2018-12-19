<!-- Name Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">{!! trans_choice('words.InspectionType',1)!!} </label>
    {!! Form::select('inspection_type_id',$inspection_types,null, array('class' => 'input-body select2 form-control','require')) !!}
    @if ($errors->has('inspection_type')) <p class="help-block">{{ $errors->first('inspection_type') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.Name')</label>
    {!! Form::text('name', null, ['class' => 'input-body', 'placeholder' => 'Name']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>
