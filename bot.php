<?php
/*
Facebook : https://web.facebook.com/Arifin.ilham000/
IG : https://instagram.com/Arifin.ilham1231/

Hai Kaka '-'
Jangan Di-edit yah kalau ga ngerti,nanti takutnya error :'(
Masukkan Token BOT Telegram Anda kedalam variable TOKEN
how to run?
$ git clone https://github.com/Mreror404/Covid19-Telegram-bot
$ cd Covid19-Telegram-bot
$ php bot.php
Enjoy!
Note : this script need vps or rdp to run 24/7 ! 
*/
error_reporting(0);
$TOKEN = "xxxxxxxxx:xxxxxxxxxxx"; // enter your token here!

$tanggal= mktime(date("m"),date("d"),date("Y"));
date_default_timezone_set('Asia/Jakarta');
$jam=date("D-M-Y H:i:s");
function request_url($method)
{
	global $TOKEN;
	return "https://api.telegram.org/bot" . $TOKEN . "/". $method;
}

function get_updates($offset) 
{
	$url = request_url("getUpdates")."?offset=".$offset;
	$resp = file_get_contents($url);
	$result = json_decode($resp, true);
	if ($result["ok"]==1)
		return $result["result"];
	return array();
}
function send_reply($chatid, $msgid, $text)
{
	$data = array(
		'chat_id' => $chatid,
		'text'  => $text,
		'reply_to_message_id' => $msgid
	);
    // use key 'http' even if you send the request to https://...
	$options = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data),
		),
	);
	$context  = stream_context_create($options);
	$result = file_get_contents(request_url('sendMessage'), false, $context);
}
function create_response($text)
{
	return $text;
}
function process_message($message)
{
	$updateid = $message["update_id"];
	$message_data = $message["message"];
	$bot_command = $message["entities"];
	$type = $bot_command["bot_command"]["type"];
	$username = $message_data["chat"]["username"];
    // print_r($message_data);
	if (isset($message_data["text"])) {
		$chatid = $message_data["chat"]["id"];
		$message_id = $message_data["message_id"];
		$first_name = $message_data["chat"]["first_name"];
		$last_name = $message_data["chat"]["last_name"];
		$fullname = "$first_name ".$last_name;
		$text = $message_data["text"];
		$new_mem = $message_data["new_chat_member"];
		$first = $message_data["new_chat_member"]["first_name"];
		$pinger = strtolower("ping");

		if(empty($first_name)) {
			$a = file_get_contents("https://api.telegram.org/bot1327612370:AAHYYecQamyewl8ovQl18iOg5vcCtOlCQj4/getupdates");
			$as = json_decode($a, true);
			//for ($i=0; $i<count($as["result"]); $i++) { 
				$first_name1 = $as["result"]["0"]["message"]["from"]["first_name"];
				$last_name1 = $as["result"]["0"]["message"]["from"]["last_name"];
				$fullname = "$first_name1 ".$last_name1; }

		if (isset($new_mem)) {

			$text = "Hai $first!\n";
			$text .= "Selamat datang di Grup ".$message_data["chat"]["title"];
			$response = create_response($text);
			send_reply($chatid, $message_id, $response); }

			elseif ($text == $pinger) {
				$text = "Pong!";
				$response = create_response($text);
			send_reply($chatid, $message_id, $response); }
      elseif($text == "/menu" || preg_match('~/menu@(.*)~')) {
        $text = "\nList Menu :\n";
				$text .= "/indo <-- Cek Covid-19 Di indonesia ( Positif, Meninggal, Sembuh, Dirawat )\n";
				$text .= "/prov <-- Cek Covid-19 Di Seluruh Provinsi Indonesia ( Positif, Meninggal, Sembuh )\n";
				$text .= "/prov Sumatera Utara <-- Cek Covid-19 Di Suatu Provinsi";
				$response = create_response($text);
				send_reply($chatid, $message_id, $response);
      }
			elseif ($text == "/start") {

				$text = "Halo $fullname! Selamat Datang Di BOT Covid-19 Info!\n\n";
				$text .= "Ini Adalah BOT yang semata-mata hanya ingin memberikan informasi kepada rekan-rekan sekalian :)\n";
				$text .= "\nList Menu :\n";
				$text .= "/indo <-- Cek Covid-19 Di indonesia ( Positif, Meninggal, Sembuh, Dirawat )\n";
				$text .= "/prov <-- Cek Covid-19 Di Seluruh Provinsi Indonesia ( Positif, Meninggal, Sembuh )\n";
				$text .= "/prov Sumatera Utara <-- Cek Covid-19 Di Suatu Provinsi";
				$response = create_response($text);
				send_reply($chatid, $message_id, $response); }

				elseif(preg_match('~/prov (.*)~', $text, $a)) { 
					include 'covid.php';
					$a1 = strtolower($a[1]);
					if($a1 == "jawa timur") {
						$text = "Provinsi ".$dataprov[0]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[0]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[0]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[0]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }

					if($a1 == "dki jakarta") { 
						$text = "Provinsi ".$dataprov[1]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[1]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[1]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[1]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "jawa tengah") { 
						$text = "Provinsi ".$dataprov[2]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[2]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[2]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[2]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "sulawesi selatan") { 
						$text = "Provinsi ".$dataprov[3]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[3]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[3]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[3]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "jawa barat") { 
						$text = "Provinsi ".$dataprov[4]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[4]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[4]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[4]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "kalimantan selatan") { 
						$text = "Provinsi ".$dataprov[5]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[5]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[5]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[5]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "sumatera utara") { 
						$text = "Provinsi ".$dataprov[6]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[6]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[6]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[6]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "bali") { 
						$text = "Provinsi ".$dataprov[7]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[7]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[7]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[7]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "sumatera selatan") { 
						$text = "Provinsi ".$dataprov[8]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[8]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[8]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[8]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "papua") { 
						$text = "Provinsi ".$dataprov[9]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[9]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[9]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[9]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "sulawesi utara") { 
						$text = "Provinsi ".$dataprov[10]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[10]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[10]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[10]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "nusa tenggara barat") { 
						$text = "Provinsi ".$dataprov[11]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[11]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[11]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[11]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "banten") { 
						$text = "Provinsi ".$dataprov[12]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[12]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[12]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[12]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "kalimantan tengah") { 
						$text = "Provinsi ".$dataprov[13]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[13]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[13]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[13]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "maluku utara") { 
						$text = "Provinsi ".$dataprov[14]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[14]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[14]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[14]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "kalimantan timur") { 
						$text = "Provinsi ".$dataprov[15]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[15]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[15]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[15]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "gorontalo") { 
						$text = "Provinsi ".$dataprov[16]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[16]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[16]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[16]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "maluku") { 
						$text = "Provinsi ".$dataprov[17]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[17]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[17]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[17]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "sumatera barat") { 
						$text = "Provinsi ".$dataprov[18]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[18]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[18]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[18]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "sulawesi tenggara") { 
						$text = "Provinsi ".$dataprov[19]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[19]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[19]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[19]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "daerah istimewa yogyakarta") { 
						$text = "Provinsi ".$dataprov[20]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[20]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[20]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[20]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "kepulauan riau") { 
						$text = "Provinsi ".$dataprov[21]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[21]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[21]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[21]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "papua barat") { 
						$text = "Provinsi ".$dataprov[22]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[22]["attributes"]["Kasus_Posi"];
                        $text .= "\nSembuh : ".$dataprov[22]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[22]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "riau") { 
						$text = "Provinsi ".$dataprov[23]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[23]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[23]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[23]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "aceh") { 
						$text = "Provinsi ".$dataprov[24]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[24]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[24]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[24]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "kalimantan barat") { 
						$text = "Provinsi ".$dataprov[25]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[25]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[25]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[25]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
					    send_reply($chatid, $message_id, $response); }
					if($a1 == "kalimantan utara") { 
						$text = "Provinsi ".$dataprov[26]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[26]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[26]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[26]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "lampung") { 
						$text = "Provinsi ".$dataprov[27]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[27]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[27]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[27]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "sulawesi barat") { 
						$text = "Provinsi ".$dataprov[28]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[28]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[28]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[28]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "bengkulu") { 
						$text = "Provinsi ".$dataprov[29]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[29]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[29]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[29]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); 
					if($a1 == "sulawesi tengah") { 
						$text = "Provinsi ".$dataprov[30]["attributes"]["Provinsi"];
				        $text .= "\nPositif : ".$dataprov[30]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[30]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[30]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "kepulauan bangka belitung") { 
						$text = "Provinsi ".$dataprov[31]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[31]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[31]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[31]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a1 == "jambi") { 
						$text = "Provinsi ".$dataprov[32]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[32]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[32]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[32]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
					    send_reply($chatid, $message_id, $response); }
					if($a1 == "nusa tenggara timur") { 
						$text = "Provinsi ".$dataprov[33]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[33]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[33]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[33]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); } } }
					elseif($text == "/prov" || preg_match('~/prov@(.*)~', $text)) {
						include 'covid.php';
						for ($i=0; $i<count($dataprov); $i++) { 
						$text = "Provinsi ".$dataprov[$i]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[$i]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[$i]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[$i]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); } }
					elseif($text == "/indo" || preg_match('~/indo@(.*)~', $text)) {
						include 'covid.php';
							$pes = array("0" => "Selalu Jaga Kesehatan!", "1" => "Hindari Kerumunan Orang!", "2" => "Selalu Pakai Masker!", "3" => "Gunakan Hand Sanitizer!", "4" => "Jaga-Jarak Minimal 1 Meter!", "5" => "Jangan Berkumpul Jika Tidak Penting!");
							$pesa = rand(0, 6);
							$pesan = $pes[$pesa];
							$text = "Positif : $positif";
							$text .= "\nMeninggal : $meninggal";
							$text .= "\nSembuh : $sembuh";
							$text .= "\nDirawat : $dirawat";
							$text .= "\n\n$pesan\n\nAPI from kawalcorona.com";
							$response = create_response($text);
							send_reply($chatid, $message_id, $response);
						}

					/* $response = create_response($text);
					send_reply($chatid, $message_id, $response); */
				}
				return $updateid;
			}
			function process_one()
			{
				$update_id  = 0;
				if (file_exists("last_update_id")) {
					$update_id = (int)file_get_contents("last_update_id");
				}
				$updates = get_updates($update_id);
				foreach ($updates as $message)
				{
					$update_id = process_message($message);
				}
				file_put_contents("last_update_id", $update_id + 1);
			}
			while (true) {
				process_one();
				
			}

			?>
