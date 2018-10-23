<!-- Title of Post Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', trans('words.Name')) !!}
    {!! Form::text('name', null, ['class' => 'input-body']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<!-- URL Input -->
<div class="form-group @if ($errors->has('url')) has-error @endif">
    {!! Form::label('url[]', trans('words.Url')) !!}
    {!! Form::select('url', $url, isset($user) ? $user->url->pluck('id')->toArray() : null,  ['class' => 'input-body']) !!}
    @if ($errors->has('url')) <p class="help-block">{{ $errors->first('url') }}</p> @endif
</div>

<!-- Modules Input -->
<div class="form-group @if ($errors->has('modulo_id')) has-error @endif">
    {!! Form::label('modulo[]', trans('words.Modules')) !!}
    {!! Form::select('modulo_id', $modulos, isset($user) ? $user->modulos->pluck('id')->toArray() : null,  ['class' => 'input-body']) !!}
    @if ($errors->has('modulo_id')) <p class="help-block">{{ $errors->first('modulo_id') }}</p> @endif
</div>

<!-- Menu Input -->
<div class="form-group @if ($errors->has('menu')) has-error @endif">
    {!! Form::label('menu[]', trans('words.MenuPadre') ) !!}
    {!! Form::select('menu_id', $menu, isset($user) ? $user->menu->pluck('id')->toArray() : null,  ['class' => 'input-body']) !!}
    @if ($errors->has('menu_id')) <p class="help-block">{{ $errors->first('menu_id') }}</p> @endif
</div>