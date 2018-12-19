@can('edit_'.$entity)
  @if($status == 2)
    <a href="{{ route($entity.'.edit', [str_singular($entity) => ${$action}])  }}" title="@lang('words.Whatch')" class="btn btn-xs btn-default">
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
  @else
    <a href="{{ route($entity.'.edit', [str_singular($entity) => ${$action}])  }}" title="@lang('words.Edit')" class="btn btn-xs btn-info">
        <i class="fa fa-edit"></i>
    </a>
  @endif

    @php
        $parameters = Route::current()->parameters();
    @endphp

    @if( isset($parameters['entity']) && $parameters['entity'] == 'formats' )
        <a href="{{ route($entity.'.supports', [str_singular($entity) => ${$action}])  }}" title="@lang('words.supports')" class="btn btn-xs btn-warning">
            <i class="glyphicon glyphicon-folder-open"></i></a>
        <a href="{{ route($entity.'.supports', [str_singular($entity) => ${$action}])  }}" class="btn btn-xs btn-warning">
            <i class="glyphicon glyphicon-folder-open"></i>
        </a>
        @if($status == 2)
            @php
                $num_firmas = App\Http\Helpers\Equivalencia::numeroFirmasPorFormato(${$action});
            @endphp
            @if($num_firmas > 0)
                <a href="{{ route($entity.'.signedFormats', [str_singular($entity) => ${$action}])  }}" target="_blank" class="btn btn-xs btn-success">
                    <i class="glyphicon glyphicon-pencil"></i>
                </a>
            @endif
        @endif
    @endif
@endcan

@can('delete_'.$entity)

    @if(isset($status))
        {!! Form::open( ['method' => 'delete', 'url' => route($entity.'.destroy', ['user' => ${$action}]), 'style' => 'display: inline-table', 'id' => 'd'.${$action}, 'class' => 'formDelete']) !!}
            @if($status == 1)
                <button type="button" onclick="confirmModal('@php echo '#d'.${$action} @endphp', '{{trans('words.InactiveMessage')}}', 'warning')" title="@lang('words.inactivate')" class="btn  btn-xs btn-success btnDelete"><span class='glyphicon glyphicon-ok-sign'></span></button>
            @elseif($status == 2)
                <a href="{{action('FormatController@downloadPDF',${$action})}}" title="@lang('words.download')" class="btn  btn-xs btn-primary"><i class='glyphicon glyphicon-download-alt'></i></a>
            @else
                <button type="button" onclick="confirmModal('@php echo '#d'.${$action} @endphp', '{{trans('words.ActiveMessage')}}', 'warning')" title="@lang('words.activate')" class="btn  btn-xs btn-danger btnDelete"><span class='glyphicon glyphicon-remove-sign'></button>
            @endif
        {!! Form::close() !!}
    @else
        {!! Form::open( ['method' => 'delete', 'url' => route($entity.'.destroy', ['user' => ${$action}]), 'style' => 'display: inline-table', 'id' => 'd'.${$action}, 'class' => 'formDelete']) !!}
            <button type="button" class="btn-delete btn btn-xs btn-danger" title="@lang('words.Delete')" onclick="confirmModal('@php echo '#d'.${$action} @endphp', '{{trans('words.DeleteMessage')}}', 'warning')">
                <i class="glyphicon glyphicon-trash"></i>
            </button>
        {!! Form::close() !!}
    @endif
@endcan
