@can('edit_'.$entity)
    <a href="{{ route($entity.'.edit', [str_singular($entity) => ${$action}])  }}" class="btn btn-xs btn-info">
        <i class="fa fa-edit"></i>
    </a>
@endcan

@can('delete_'.$entity)

    @if(isset($status))
        {!! Form::open( ['method' => 'delete', 'url' => route($entity.'.destroy', ['user' => ${$action}]), 'style' => 'display: inline', 'id' => 'd'.${$action}, 'class' => 'formDelete']) !!}
            @if($status == 1)
                <button type="button" onclick="confirmModal('@php echo '#d'.${$action} @endphp', '{{trans('words.InactiveMessage')}}', 'warning')" class="btn  btn-xs btn-success btnDelete"><span class='glyphicon glyphicon-ok-sign'></span></button>
            @elseif($status == 2)
                <a href="{{action('FormatController@downloadPDF',${$action})}}" class="btn btn-primary btn-sm"><i class='glyphicon glyphicon-download-alt'></i></a>
            @else
                <button type="button" onclick="confirmModal('@php echo '#d'.${$action} @endphp', '{{trans('words.ActiveMessage')}}', 'warning')" class="btn  btn-xs btn-danger btnDelete"><span class='glyphicon glyphicon-remove-sign'></button>
            @endif
        {!! Form::close() !!}
    @else
        {!! Form::open( ['method' => 'delete', 'url' => route($entity.'.destroy', ['user' => ${$action}]), 'style' => 'display: inline', 'id' => 'd'.${$action}, 'class' => 'formDelete']) !!}
            <button type="button" class="btn-delete btn btn-xs btn-danger" onclick="confirmModal('@php echo '#d'.${$action} @endphp', '{{trans('words.DeleteMessage')}}', 'warning')">
                <i class="glyphicon glyphicon-trash"></i>
            </button>
        {!! Form::close() !!}
    @endif
@endcan
