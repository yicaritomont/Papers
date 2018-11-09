{{-- {{$entity.'-'.${$action} }} --}}

@can('edit_'.$entity)
    <a href="{{ route($entity.'.edit', [str_singular($entity) => ${$action}])  }}" class="btn btn-xs btn-info">
        <i class="fa fa-edit"></i></a>
@endcan

@can('delete_'.$entity)
    {!! Form::open( ['method' => 'delete', 'url' => route($entity.'.destroy', ['user' => ${$action}]), 'style' => 'display: inline', 'onSubmit' => 'return confirm("'.trans("words.MsgDel").'")']) !!}
        <button type="submit" class="btn-delete btn btn-xs btn-danger">
            <i class="glyphicon glyphicon-trash"></i>
        </button>
    {!! Form::close() !!}
@endcan