<?php
ini_set('error_reporting', E_ALL);

$botToken = "123456789:ABCDEFGHIJKLMNOPQRSTUVWYXZABCDEFGHI";
$website  = "https://api.telegram.org/bot".$botToken;

$update = file_get_contents("php://input");
$update = json_decode($update,true);

$chatId  = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];
$usuario = $update["message"]["from"]["first_name"];

$usuarioSaida = $update["message"]["left_chat_participant"]["first_name"]; 
$usuarioNovo  = $update["message"]["new_chat_participant"]["first_name"];


if($update["message"]["chat"]["type"] == "group"){
	$grupo = $update["message"]["chat"]["title"];
}else{
	$grupo = '';
}

if($usuarioSaida <> ""){
	sendmessage($chatId, "Adeus, ".$usuarioSaida);
	exit;
}else
if ($usuarioNovo <> ""){
	if($grupo <> ""){
		sendmessage($chatId, "Olá, ".$usuarioNovo.".. Bem vindo ao grupo ".$grupo);
		exit;
	}
}

if($message[0] <> '/'){
	exit;
}

$arrMessage =  explode(' ',$message);

switch ($arrMessage[0]) {
	case "/oi":	
		sendmessage($chatId, "Oi ".$usuario);			
		break;
	case "/previsao":
		$url = 'http://developers.agenciaideias.com.br/tempo/json/sao%20jose%20dos%20campos-sp'; 
		$retorno = grab_page($url);
		$previsao = json_decode($retorno,true);
		sendmessage($chatId,"Tempo em SJC: ".$previsao["agora"]["temperatura"]." - ".$previsao["agora"]["descricao"]);
		break;		
	default:
		sendmessage($chatId,"Comando não reconhecido!");
		break;
}


function sendmessage($s_chatId,$s_message){
	$url = $GLOBALS[website]."/sendmessage?chat_id=".$s_chatId."&text=".$s_message;
	file_get_contents($url);
} 

function grab_page($site){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 50);
    curl_setopt($ch, CURLOPT_URL, $site);
    ob_start();
    return curl_exec ($ch);
    ob_end_clean();
    curl_close ($ch);
}

?>

<h2>Bot em Funcionamento</h2>
