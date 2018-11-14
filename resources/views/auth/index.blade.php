@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2 ">
    	<h1 class="text-center login-title">{{trans('words.ChangeCompany')}} <span class="glyphicon glyphicon-wrench"></span></h1>
    	<hr>
        <div class="well contenedor-anclas">
            <fieldset>
			
            	<ul class="list-group">				
            		@foreach($companias as $compania)    											  		
					  	<li class="list-group-item" >
		                    <a href="{{URL::to('enviaCompania',array('compania'=>$compania->company_id))}}">
								<button style="width:100%" class="text-center btn-login departamento ladda-button" role="alert" data-style="expand-left" data-spinner-color="#000000">
									<span class="glyphicon glyphicon-circle-arrow-right"></span>
									<span class="ladda-label "> <b>{{ App\Company::find($compania->company_id)->name }}</b></span>
			                    </button>
		                    </a>				  		
						</li>
				  	@endforeach
				</ul>
            </fieldset>
        </div>
    </div>
</div>
@endsection
