<?php
include "usefulFunctions.php";

echo "Hello SindMusi!
	  <br><br>
	  <a href='atualiza_tipos_origem.php'><b> Atualizar lista de Tipos/Origens dos Registros </b></a>";

// Heroku clearDB connection information
$cleardb_url = parse_url(getenv("CLEARDB_WHITE_URL"));
$cleardb_server = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"]; 
$cleardb_password = $cleardb_url["pass"];
$cleardb_db = substr($cleardb_url["path"],1);

$active_group = 'default';
$active_record = TRUE;

//Servidor local
//$servername = "localhost";
//$username = "root";
//$password = "";
//$dbname = "sindmusi";

$max_filename_size = 203; // Tamanho máximo de nome de arquivo no Windows

try{
	$conn = new PDO("mysql:host=$cleardb_server;dbname=$cleardb_db", $cleardb_username, $cleardb_password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$resultado = $conn->query( "select count(r.id) as numero_de_registros
								from registro r " );
	$numero_de_registros = ($resultado->fetch())["numero_de_registros"];
	
	$resultado = $conn->query( "select count(m.id) as numero_de_midias
								from midia m" );
	$numero_de_midias = ($resultado->fetch())["numero_de_midias"];
	
	echo "<br><br> Registros inclusos: ".$numero_de_registros;
	echo "<br>     Mídias inclusas:    ".$numero_de_midias;
	echo "<br>     Média de mídias/registro: ".number_format( $numero_de_midias/$numero_de_registros, 2 );
	
	
	// Acesso aos .json contendo os dados de opções dos <select>
	$tipos_json = file_get_contents_without_BOM( "tipos-de-registros.json" );
	$tipos = json_decode( $tipos_json, true );
	
	if( !is_dir( "midia" ) ){//Se ainda não existe a pasta de mídias, crio
		mkdir( "midia" );
	}
	
	$tipos_de_registros = "";
	foreach( $tipos as $t ){
		$tipos_de_registros .= "<option>".$t."</option>";
		
		if( !is_dir( "midia/$t" ) ){
			//Garanto que para cada tipo haverá uma pasta de arquivos de mídia
			mkdir( "midia/$t" );
		}
	}
	
	$origens = file_get_contents_without_BOM( "origem-dos-registros.json" );
	$origens = json_decode( $origens, true );
	
	$origem_dos_registros = "";
	foreach( $origens as $o ){
		$origem_dos_registros .= "<option>".$o."</option>";
	}	
	
	$conteudo = "<center>
					 <h3>Novo Registro</h3>	
					 <form action='salva-registro.php' enctype='multipart/form-data' method='post'>
						*(Campos Obrigatórios)
						<br><br>
						*Tipo:
							<select id='tipo' name='tipoReg' required>
								<option></option>
								$tipos_de_registros
							</select> <br><br>
						*Selecione as Mídias:
							<input id='midias' onchange='preview( this )' type='file' name='arquivos[]' multiple required>
						<br><br>
						<div id='lista_de_midias'></div>
						<button onclick='limparMidias()'>Limpar Mídias</button><br><br>
						*Título:
							<input id='titulo' type='text' name='tituloReg' required><br><br>
						*Origem:
							<select name='origemReg' required>
								<option></option>
								$origem_dos_registros
							</select> <br><br>
						Data:
							<input type='date' name='dataReg'>
							<input type='time' name='horaReg'>
							<br><br>
						*Relevância:
							<select name='relevanciaReg' required>
								<option></option>
								<option value='1'> 1 - Muito pequena </option>
								<option value='2'> 2 - Pequena </option>
								<option value='3'> 3 - Média/Normal </option>
								<option value='4'> 4 - Significativa </option>
								<option value='5'> 5 - Muito Significativa</option>
							</select>
						<br><br>
						*Descrição<br>
							<textarea rows='12' cols='100' name='descricaoReg' required></textarea><br>
						 <br>
						 <input type='submit' value='Salvar'>
					 </form>
				 </center>";
	
}
catch(PDOException $e){
	echo "<br> Error: " . $e->getMessage();
}
$conn = null;

?>
<html>
<head>
	<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">          
	
	<!-- jQuery 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>
	-->
	
	<!-- CSS -->
	<link  href="cria-registro.css" rel="stylesheet" type="text/css">
	
	<script>
	
		function append( tagId, conteudo ){
			document.getElementById(tagId).innerHTML += conteudo;
		}
		
		function close( element ){
			element.style.display = "none";
		}		
		function limparMidias(){
			document.getElementById("midias").value = "";
			document.getElementById("lista_de_midias").innerHTML = "";
		}
		
		function cria_preview( f, posicao, list ){// Teve que ser recursiva porque usando for() não deu certo
			if( posicao < f.length ){
				var mime = f[ posicao ].type;
				var tipoArquivo = mime.split('/')[0];
				
				var reader = new FileReader();
				reader.onload = function(){
					var src = reader.result;
					
					if( tipoArquivo == "image" ){
						var element = document.createElement("img");
						element.src = src;
						
						element.width = 350;
						element.height = 350;
						list.appendChild(element);
					}
					if( tipoArquivo == "video" ){
						list.innerHTML += "<video controls> <source src='"+src+"' type='"+mime+"'> </video>";
					}
					if( tipoArquivo == "audio" ){
						list.innerHTML += "<audio controls> <source src='"+src+"' type='"+mime+"'> </audio>";
					}
				};
				reader.readAsDataURL( f[ posicao ] );
				
				posicao+=1;
				cria_preview( f, posicao, list );
			}
		}
		function preview( input ){
			if( input ){
				document.getElementById("lista_de_midias").innerHTML = "";// Limpa a div de preview
				var previewList = document.getElementById("lista_de_midias");
				cria_preview( input.files, 0, previewList );
			}
		}
	</script>
</head>
<body>
	<?php echo $conteudo; ?>
 </body>
</html>