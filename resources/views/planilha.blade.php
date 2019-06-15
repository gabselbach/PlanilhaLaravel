<!DOCTYPE html>
<html>
<head>
	<title>Teste</title>	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div class="container">
		<P>Numero de cirurgias realizada</P>
			{{$array}}
		<br>
		<P>taxa de suspensao de cirurgias </P>
		{{(1-$array/$total) }}
		<br>
		<P>Numero de Partos Cesaria</P>
			{{$pc}}
		<br>
		<P>Numero de Partos Vaginal </P>
		{{$pv}}
	</div>

</body>
</html>



