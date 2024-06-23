<?php
/* @RiyanCoday 24/05/2024 */
/* auto claim */
error_reporting(0);
date_default_timezone_set('Asia/Jakarta');
function coday($url,$headers){
$ch = curl_init($url);
if($url == "https://elb.seeddao.org/api/v1/seed/claim" || $url == "https://elb.seeddao.org/api/v1/tasks/7fdc46b3-6612-453a-9ef7-05471800f0ad"){
curl_setopt($ch, CURLOPT_POST, true);
}
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec($ch);
curl_close ($ch);
return $response;
}
while(true){
	$tokens = file('data.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($tokens as $index => $token) {
        $acc = $index + 1;
		$headers = array(
		"accept: application/json, text/plain, */*",
		"origin: https://elb.seeddao.org",
		"referer: https://elb.seeddao.org/",
		'telegram-data: '.$token,
		"origin: https://elb.seeddao.org"
		);
		$date = date('d-m-Y H:i:s');
		$claim = coday("https://elb.seeddao.org/api/v1/seed/claim",$headers);
		$jsC = json_decode($claim, true);
		$balance = coday("https://elb.seeddao.org/api/v1/profile/balance",$headers);
		$jsB = json_decode($balance, true);
					$completeTask = coday("https://elb.seeddao.org/api/v1/tasks/7fdc46b3-6612-453a-9ef7-05471800f0ad",$headers);
					$jsT = json_decode($completeTask, true);
					$completeTaskk = coday("https://elb.seeddao.org/api/v1/tasks/notification/".$jsT['data']."",$headers);
		if($jsC['data']['amount'] > 1){
		echo "\033[32m[$date] Account $acc: success claim ".number_format($jsC['data']['amount'] / 1000000000, 6, '.', '')." [SEED Balance: ".number_format($jsB['data'] / 1000000000, 6, '.', '')."] \033[0m\n";
		}else{
		echo "\033[31m[$date] Account $acc: ".$jsC['message']." [SEED Balance: ".number_format($jsB['data'] / 1000000000, 6, '.', '')."] \033[0m\n";			
		}
	}
	echo "\033[34m====[Wait 5 minute]====\033[0m\n";
	sleep(300);
}

?>
