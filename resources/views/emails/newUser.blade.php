<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>	
		
		<h2>Bienvenido {{$datos['nombre_persona']}}</h2>
		<div>
			@if($datos['usuario_nuevo'] == 0)
				
				Ha sido asignado como <b>{{$datos['perfil']}}</b>
				
				Recuerde que para acceder en http://190.85.28.74:8901/inspecciones/public/login ,
				con su usuario ya asignado.

			@else
				
				Ha sido asignado como <b>{{$datos['perfil']}}</b>.
				
				
				<br>
				Se ha asignado un usuario para acceder a http://190.85.28.74:8901/inspecciones/public/login , 
				con las siguientes llaves de acceso : 
				<br>
				<br>
				<b> Usuario : </b> {{$datos['usuario']}}
				<br>
				<b> Contrase&ntilde;a : </b> {{$datos['contrasena']}}
				<br>
				Al ingresar el aplicativo le solicitar&aacute; cambio de contrase&ntilde;a.

				
			@endif

			<br>
			Las compa&ntilde;ia en las que se encuentra asociado son las siguientes :
			<ul>
				@foreach($datos['companies'] as $companies)
					<li> {{$companies}} </li>
				@endforeach
			</ul>
			

			<i>Este correo es generado autom&aacute;ticamente, por favor no responder.</i>
		</div>


		
	</body>
</html>
