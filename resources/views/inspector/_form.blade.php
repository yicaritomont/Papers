<div class="form-group @if ($errors->has('identification')) has-error @endif">
    <label for="name">@lang('words.Identification')</label>
    {!! Form::text('identification', null, ['class' => 'input-body', 'placeholder' => 'Identification','id' =>'identificacion_inspector']) !!}
    @if ($errors->has('identification')) <p class="help-block">{{ $errors->first('identification') }}</p> @endif
    {!! Form::hidden('id_inspector',null,['id' => 'id_inspector']) !!}
</div>

<div class="form-group @if ($errors->has('name')) has-error @endif">
    <label for="name">@lang('words.Name')</label>
    {!! Form::text('name', isset($user) ? $user->name : null, ['class' => 'input-body', 'placeholder' => 'Name' , 'id' => 'nombre_inspector']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('profession_id')) has-error @endif">
    <label for="name">@lang('words.Profession')</label>
    {!! Form::select('profession_id',$professions,null, array('class' => 'input-body', 'required', 'placeholder' => trans('words.ChooseOption'))) !!}
    @if ($errors->has('profession_id')) <p class="help-block">{{ $errors->first('profession_id') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('inspector_type_id')) has-error @endif">
    <label for="name">@lang('words.InspectorType')</label>
    {!! Form::select('inspector_type_id',$inspector_types,null, array('class' => 'input-body','required', 'placeholder' => trans('words.ChooseOption'))) !!}
    @if ($errors->has('inspector_type_id')) <p class="help-block">{{ $errors->first('inspector_type_id') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('phone')) has-error @endif">
    <label for="name">@lang('words.Phone')</label>
    {!! Form::text('phone', null, ['class' => 'input-body', 'placeholder' => 'Phone','id' => 'telefono_inspector']) !!}
    @if ($errors->has('phone')) <p class="help-block">{{ $errors->first('phone') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('addres')) has-error @endif">
    <label for="name">@lang('words.Addres')</label>
    {!! Form::text('addres', null, ['class' => 'input-body', 'placeholder' => 'Addres','id' => 'direccion_inspector']) !!}
    @if ($errors->has('addres')) <p class="help-block">{{ $errors->first('addres') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('email')) has-error @endif">
    <label for="name">@lang('words.Email')</label>
    {!! Form::text('email', isset($user) ? $user->email : null, ['class' => 'input-body', 'placeholder' => 'Email','id' => 'correo_inspector']) !!}
    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
</div>

@if(Auth::user()->roles->pluck('id')[0] != 1)
    @if(session()->get('Session_Company') != "")
        <b>{{ App\Company::find(session()->get('Session_Company'))->name }}</b>
        {!! Form::hidden('companies', session()->get('Session_Company')) !!}
    @endif
@else
    <!-- Companies Form Input -->
    <div class="form-group @if ($errors->has('companies')) has-error @endif">
        {!! Form::label('companies[]', trans_choice('words.Company', 2)) !!}
        {!! Form::select('companies[]', $companies, isset($user) ? $user->companies->pluck('id')->toArray() : null,  ['class' => 'input-body', 'multiple']) !!}
        @if ($errors->has('companies')) <p class="help-block">{{ $errors->first('companies') }}</p> @endif
    </div>
@endif


<!-- Modal Notificacion-->
<div class="modal fade" id="modal_notificacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-custom">
                <div class="modal-content">                                          
                    <div class="modal-body modal-body-custom">                                                        
                        <div class="panel panel-success pan">
                            <div class="panel-body">                                                                  
                                <div class="text-center">
                                    <div id="cont-notificacion-modal" class="title-modal"></div>
                                </div>
                                <div class="col-xs-12 text-center">
                                    <label for="usuario" style="color:black"> Derechos Reservados 2018.</label>
                                </div>
                            </div>
                        </div>
                    </div>                                          
                </div>
            </div>
        </div>