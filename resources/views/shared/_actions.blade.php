@can('edit_'.$entity)
    <a href="{{ route($entity.'.edit', [str_singular($entity) => ${$action}])  }}" class="btn btn-xs btn-info">
        <i class="fa fa-edit"></i></a>
@endcan

@can('delete_'.$entity)
    @if(isset($status))

        {!! Form::open( ['method' => 'delete', 'url' => route($entity.'.destroy', ['user' => ${$action}]), 'style' => 'display: inline']) !!}                            
            @if($status == 1)
                <button class="btn  btn-xs btn-success"><span class='glyphicon glyphicon-ok-sign'></span></button>
            @else
                <button class="btn  btn-xs btn-danger"><span class='glyphicon glyphicon-remove-sign'></button>
            @endif    
        {!! Form::close() !!}

    @else
        {!! Form::open( ['method' => 'delete', 'url' => route($entity.'.destroy', ['user' => ${$action}]), 'style' => 'display: inline', 'onSubmit' => 'return confirm("'.trans("words.MsgDel").'")']) !!}
            <button type="submit" class="btn-delete btn btn-xs btn-danger">
                <i class="glyphicon glyphicon-trash"></i>
            </button>
        {!! Form::close() !!}
    @endif
@endcan