<?php
error_reporting(0);
$TOKEN = "1307933776:AAHTnqJ8vaLdpb-A8X7poYkl4Ig-ii3aiDE";

$tanggal= mktime(date("m"),date("d"),date("Y"));
date_default_timezone_set('Asia/Jakarta');
$jam=date("H:i");
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
	$indo = file_get_contents("https://api.kawalcorona.com/indonesia");
	$prov = file_get_contents("https://api.kawalcorona.com/indonesia/provinsi/");
	$dataindo = json_decode($indo, true);
	$dataprov = json_decode($prov, true);
	//echo count($dataprov);
	$datain = $dataindo["0"]["name"];
	$datapro = $dataprov["attributes"];
	//print_r($dataprov);
	$positif = $dataindo["0"]["positif"];
	$sembuh = $dataindo["0"]["sembuh"];
	$meninggal = $dataindo["0"]["meninggal"];
	$dirawat = $dataindo["0"]["dirawat"];

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

		if (isset($new_mem)) {

			$text = "Hai $first!\n";
			$text .= "Selamat datang di Grup ".$message_data["chat"]["title"];
			$response = create_response($text);
			send_reply($chatid, $message_id, $response); }

			if($text == "!ping") {
				$text = "Pong!";
				$response = create_response($text);
			send_reply($chatid, $message_id, $response); }
			elseif (empty($username) && preg_match('~/start~i', $text)) {

				$text = "Halo $fullname! Selamat Datang Di BOT Covid-19 Info!\n\n";
				$text .= "Ini Adalah BOT yang semata-mata hanya ingin memberikan informasi kepada rekan-rekan sekalian :)\n";
				$text .= "\nList Menu :\n";
				$text .= "/indo <-- command buat ngecek Covid-19 Di indonesia ( Positif, Meninggal, Sembuh, Dirawat )\n";
				$text .= "/prov <-- command buat ngecek Covid-19 Di Seluruh Provinsi Indonesia ( Positif, Meninggal, Sembuh )\n";
				$text .= "/prov Sumatera Utara <-- buat ngecek Covid-19 Di Suatu Provinsi";
				$response = create_response($text);
				send_reply($chatid, $message_id, $response); }

				elseif(preg_match('~/prov (.*)~i', $text, $a)) { 
					//print_r($a[1]);
					if($a[1] == "Jawa Timur") {
						$text = "Provinsi ".$dataprov[0]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[0]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[0]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[0]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }

					if($a[1] == "DKI Jakarta") { 
						$text = "Provinsi ".$dataprov[1]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[1]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[1]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[1]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Jawa Tengah") { 
						$text = "Provinsi ".$dataprov[2]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[2]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[2]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[2]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Sulawesi Selatan") { 
						$text = "Provinsi ".$dataprov[3]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[3]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[3]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[3]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Jawa Barat") { 
						$text = "Provinsi ".$dataprov[4]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[4]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[4]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[4]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Kalimantan Selatan") { 
						$text = "Provinsi ".$dataprov[5]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[5]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[5]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[5]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Sumatera Utara") { 
						$text = "Provinsi ".$dataprov[6]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[6]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[6]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[6]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Bali") { 
						$text = "Provinsi ".$dataprov[7]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[7]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[7]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[7]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Sumatera Selatan") { 
						$text = "Provinsi ".$dataprov[8]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[8]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[8]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[8]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Papua") { 
						$text = "Provinsi ".$dataprov[9]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[9]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[9]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[9]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Sulawesi Utara") { 
						$text = "Provinsi ".$dataprov[10]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[10]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[10]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[10]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Nusa Tenggara Barat") { 
						$text = "Provinsi ".$dataprov[11]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[11]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[11]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[11]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Banten") { 
						$text = "Provinsi ".$dataprov[12]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[12]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[12]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[12]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Kalimantan Tengah") { 
						$text = "Provinsi ".$dataprov[13]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[13]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[13]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[13]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Maluku Utara") { 
						$text = "Provinsi ".$dataprov[14]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[14]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[14]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[14]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Kalimantan Timur") { 
						$text = "Provinsi ".$dataprov[15]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[15]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[15]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[15]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Gorontalo") { 
						$text = "Provinsi ".$dataprov[16]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[16]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[16]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[16]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Maluku") { 
						$text = "Provinsi ".$dataprov[17]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[17]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[17]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[17]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Sumatera Barat") { 
						$text = "Provinsi ".$dataprov[18]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[18]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[18]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[18]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Sulawesi Tenggara") { 
						$text = "Provinsi ".$dataprov[19]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[19]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[19]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[19]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Daerah Istimewa Yogyakarta") { 
						$text = "Provinsi ".$dataprov[20]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[20]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[20]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[20]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Kepulauan Riau") { 
						$text = "Provinsi ".$dataprov[21]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[21]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[21]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[21]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Papua Barat") { 
						$text = "Provinsi ".$dataprov[22]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[22]["attributes"]["Kasus_Posi"];
                        $text .= "\nSembuh : ".$dataprov[22]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[22]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Riau") { 
						$text = "Provinsi ".$dataprov[23]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[23]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[23]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[23]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Aceh") { 
						$text = "Provinsi ".$dataprov[24]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[24]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[24]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[24]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Kalimantan Barat") { 
						$text = "Provinsi ".$dataprov[25]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[25]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[25]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[25]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
					    send_reply($chatid, $message_id, $response); }
					if($a[1] == "Kalimantan Utara") { 
						$text = "Provinsi ".$dataprov[26]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[26]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[26]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[26]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Lampung") { 
						$text = "Provinsi ".$dataprov[27]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[27]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[27]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[27]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Sulawesi Barat") { 
						$text = "Provinsi ".$dataprov[28]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[28]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[28]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[28]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Bengkulu") { 
						$text = "Provinsi ".$dataprov[29]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[29]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[29]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[29]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); 
					if($a[1] == "Sulawesi Tengah") { 
						$text = "Provinsi ".$dataprov[30]["attributes"]["Provinsi"];
				        $text .= "\nPositif : ".$dataprov[30]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[30]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[30]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Kepulauan Bangka Belitung") { 
						$text = "Provinsi ".$dataprov[31]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[31]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[31]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[31]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); }
					if($a[1] == "Jambi") { 
						$text = "Provinsi ".$dataprov[32]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[32]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[32]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[32]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
					    send_reply($chatid, $message_id, $response); }
					if($a[1] == "Nusa Tenggara Timur") { 
						$text = "Provinsi ".$dataprov[33]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[33]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[33]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[33]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); } } }
					elseif(preg_match('~/prov~', $text)) {
						for ($i=0; $i<count($dataprov); $i++) { 
						$text = "Provinsi ".$dataprov[$i]["attributes"]["Provinsi"];
						$text .= "\nPositif : ".$dataprov[$i]["attributes"]["Kasus_Posi"];
						$text .= "\nSembuh : ".$dataprov[$i]["attributes"]["Kasus_Semb"];
						$text .= "\nMeninggal : ".$dataprov[$i]["attributes"]["Kasus_Meni"];
						$text .= "\n\n";
						$response = create_response($text);
						send_reply($chatid, $message_id, $response); } }
					elseif(preg_match('~/indo~', $text)) {
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