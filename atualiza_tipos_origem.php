<?php
include "usefulFunctions.php";

$resultado = "";
if( isset( $_POST['tiposRegistros'], $_POST['origensRegistros'] ) ){
	$tiposRegistros = trim( $_POST['tiposRegistros'] );
	$tiposRegistros = explode( ",", $tiposRegistros );
	$tiposReg = [];
	foreach( $tiposRegistros as $t ){
		$t = trim($t);
		if( $t != "" ){
			$tiposReg[] = $t;
		}
	}
	$tiposReg = json_encode( $tiposReg );
	if( !file_put_contents( "tipos-de-registros.json", $tiposReg ) ){
		$resultado .= "Problemas ao salvar os Tipos de Registro";
	}
	else{
		$resultado .= "Tipos de Registro salvos com sucesso";
	}
	
	$origensRegistros = trim( $_POST['origensRegistros'] );
	$origensRegistros = explode( ",", $origensRegistros );
	$origensReg = [];
	foreach( $origensRegistros as $o ){
		$o = trim($o);
		if( $o != "" ){
			$origensReg[] = $o;
		}
	}
	$origensReg = json_encode( $origensReg );
	if( !file_put_contents( "origem-dos-registros.json", $origensReg ) ){
		$resultado .= "<br><br> Problemas ao salvar as Origens dos Registros";
	}
	else{
		$resultado .= "<br><br> Origens dos Registros salvas com sucesso";
	}
}

//------------------------------------------------------------------------------------

$tipo = file_get_contents_without_BOM( "tipos-de-registros.json" );
$tipo = json_decode( $tipo, true );

$tiposConteudo = "";
for( $i=0; $i < count( $tipo ); $i++ ){
	if( $i == 0 ){
		$tiposConteudo .= trim( $tipo[$i] );
	}
	else{
		$tiposConteudo .= ", ".trim( $tipo[$i] );
	}
}

$origem = file_get_contents_without_BOM( "origem-dos-registros.json" );
$origem = json_decode( $origem, true );

$origensConteudo = "";
for( $i=0; $i < count( $origem ); $i++ ){
	if( $i == 0 ){
		$origensConteudo .= trim( $origem[$i] );
	}
	else{
		$origensConteudo .= ", ".trim( $origem[$i] );
	}
}
	
$conteudo = 
	"<center>
	 <a href='index.php'><b> Voltar à página de cadastro de registros </b></a>
	 <br><br><br>
	 Para atualizar apenas edite os campos, deixando os Tipos/Origens separados por vírgulas
	 <br><br>
	 <form action='atualiza_tipos_origem.php' method='post'>
		 Tipos de Registro
		 <br>
		 <textarea rows='5' cols='100' name='tiposRegistros' required> $tiposConteudo </textarea>
		 <br><br>
		 Origens dos Registros
		 <br>
		 <textarea rows='5' cols='100' name='origensRegistros' required> $origensConteudo </textarea>
		 <br><br>
		 <input type='submit' value='Salvar'>
	 </form>
	 <br>
	 $resultado
	 <center>";

?>
<html>
<head>
	<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">          
	
	<!-- jQuery 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>
	-->
	
	<!-- CSS 
	<link  href="cria-registro.css" rel="stylesheet" type="text/css">
	-->
	
	<script>
	</script>
</head>
<body>
	<?php echo $conteudo; ?>
 </body>
</html>