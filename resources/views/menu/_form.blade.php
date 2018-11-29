<!-- Name of Menu Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('words.Name')) !!}
    {!! Form::text('name', null, ['class' => 'input-body']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<!-- URL Input -->
<div class="form-group @if ($errors->has('url')) has-error @endif">
    {!! Form::label('url', trans('words.Url')) !!}
    {!! Form::select('url', $url, isset($user) ? $user->url->pluck('id')->toArray() : null,  ['class' => 'input-body', 'placeholder' => trans('words.DropdownMenu')]) !!}
    @if ($errors->has('url')) <p class="help-block">{{ $errors->first('url') }}</p> @endif
</div>

{{-- <!-- Modules Input -->
<div class="form-group @if ($errors->has('modulo_id')) has-error @endif">
    {!! Form::label('modulo[]', trans('words.Modules')) !!}
    {!! Form::select('modulo_id', $modulos, isset($user) ? $user->modulos->pluck('id')->toArray() : null,  ['class' => 'input-body']) !!}
    @if ($errors->has('modulo_id')) <p class="help-block">{{ $errors->first('modulo_id') }}</p> @endif
</div> --}}

<!-- Menu Input -->
<div class="form-group @if ($errors->has('menu_id')) has-error @endif">
    {!! Form::label('menu_id', trans('words.MenuPadre') ) !!}
    {!! Form::select('menu_id', $menu, isset($user) ? $user->menu->pluck('id')->toArray() : null,  ['class' => 'input-body', 'placeholder' => trans('words.ChooseOption')]) !!}
    @if ($errors->has('menu_id')) <p class="help-block">{{ $errors->first('menu_id') }}</p> @endif
</div>

<!-- Icon Input -->
<div class="form-group picker @if ($errors->has('icon')) has-error @endif">
    {{-- <label for="icon">@lang('words.Icon')</label> --}}
    {!! Form::label('icon', trans('words.Icon') ) !!}
    
    <div class="input-group">
        <span class="input-group-addon"><i class="fa {{ isset($menus->icon) ? $menus->icon : 'fa-fonticons' }}"></i></span>
        {!! Form::text('icon', null, ['class' => 'input-body inputpicker', 'autocomplete' => 'off']) !!}
        {{-- <input type="text" id="icon" class="input-body inputpicker" autocomplete="off"> --}}
    </div>
    @if ($errors->has('icon')) <p class="help-block">{{ $errors->first('icon') }}</p> @endif
</div>

{{-- {{ $menus }} --}}

<!-- Order Input -->
<div class="form-group @if ($errors->has('order')) has-error @endif">
    {!! Form::label('order', trans('words.order')) !!}
    {!! Form::number('order', null, ['class' => 'input-body', 'autocomplete' => 'off', 'min' => 1]) !!}
    @if ($errors->has('order')) <p class="help-block">{{ $errors->first('order') }}</p> @endif
</div>

<input type="hidden" name="icon" id="icon-hidden" value="{{ isset($menus) ? $menus->icon : null }}">