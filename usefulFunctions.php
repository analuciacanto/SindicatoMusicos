<?php

// Esta função se livra do BOM(Byte-order mark). Já que json não possui isso, ocorrem 
// erros ao se codificar/decodificar arquivos com BOM. 

// Como o Bloco de Notas salva os arquivos com BOM, esta função permite que possamos 
// usar o Bloco de Notas sem problema.
function file_get_contents_without_BOM( $src ){
	$content = file_get_contents( $src );
	// ----remove the utf-8 BOM ----
	$content = str_replace("\xEF\xBB\xBF",'',$content);// (Peguei da Internet)
	return $content;
}

// Identifica erros ao usar funções que trabalham 
// com json ( json_encode()/json_decode() por exemplo )
function show_json_error(){
	echo "<br>";
	switch (json_last_error()) {
		case JSON_ERROR_NONE:
			echo ' - No errors';
		break;
		case JSON_ERROR_DEPTH:
			echo ' - Maximum stack depth exceeded';
		break;
		case JSON_ERROR_STATE_MISMATCH:
			echo ' - Underflow or the modes mismatch';
		break;
		case JSON_ERROR_CTRL_CHAR:
			echo ' - Unexpected control character found';
		break;
		case JSON_ERROR_SYNTAX:
			echo ' - Syntax error, malformed JSON';
		break;
		case JSON_ERROR_UTF8:
			echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
		break;
		default:
			echo ' - Unknown error';
		break;
	}
}

//Usar quando quiser saber se todos os campos selecionados estão setados e não vazios
function set_and_nonEmpty( $fields ){
	$size = count($fields);
	$i = 0;
	$ok = true;
	while( $i<$size && $ok ){
		$ok = ( isset( $fields[$i] ) && trim($fields[$i])!="" );
		$i++;
	}
	return $ok;
}
function nonEmpty( $fields ){
	$size = count($fields);
	$i = 0;
	$ok = true;
	while( $i<$size && $ok ){
		$ok = ( trim($fields[$i])!="" );
		$i++;
	}
	return $ok;
}

?>