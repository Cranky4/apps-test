<?php
// Sets which PHP errors are reported
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
error_reporting(E_ERROR);

// Disable cache
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");	
// Set type to JSON
header('Content-type: application/json; charset=utf-8');
// Set access control
header('Access-Control-Allow-Origin: *');

$IP = $_SERVER["REMOTE_ADDR"];

if(isset($_POST['name']) && isset($_POST['phone'])){

    $to = "aquabalt11@yandex.ru ";
    $to = "iborshchov@appsstudio.ru";
    //$to = "vitalii.blagodir@gmail.com";
   
    $subject = "Заказ с мобильного приложения";
   
    $message .= "Имя: <b> " . $_POST['name'] . "</b>\r\n<br>";
    $message .= "Телефон: <b> " . $_POST['phone'] . "</b>\r\n<br>";
	
    // Version <= 1.1
    if($_GET['act'] == 'order' && isset($_POST['water'])) 
    {
		$message .= "Вода: <b> " . $_POST['water'] . "</b>\r\n<br>";
		$message .= "Количество бутылей: <b> " . $_POST['n'] . "</b>\r\n<br>";
	}
    
    // Version >= 1.2
    if($_GET['act'] == 'order' && isset($_POST['product'])) 
    {
        foreach ($_POST['product'] as $index => $value) {
            $message .= "\r\n<br>";
            $message .= "Товар № " . ($index+1) .": <b> " . $value . "</b>\r\n<br>";
            $message .= "Количество бутылей: <b> " . $_POST['amount'][ $index ] . "</b>\r\n<br>";
        }
	}
    
    // Version >= 2.0
    if($_GET['act'] == 'order' && isset($_POST['address'])) 
    {
		$message .= "\r\n<br>";
        $message .= "Район: <b> "       . $_POST['region'] . "</b>\r\n<br>";
        $message .= "Адрес: <b> "       . $_POST['address'] . "</b>\r\n<br>";
        $message .= "Доставка: <b> "    . $_POST['delivery'] . "</b>\r\n<br>";
        $message .= "Комментарий: <b> " . $_POST['comment'] . "</b>\r\n<br>";
    }
    
    // Callback
    if($_GET['act'] == 'callback') 
    {
        $message .= "Время звонка: <b> " . $_POST['time'] . "</b>\r\n<br>";
	}
    
    $message .= "\r\n<br>";
   
    $message .= "IP: <b> " . $IP . "</b>\r\n<br>";
	
    // Version >= 2.0
    if($_GET['act'] == 'order' && isset($_POST['address'])) 
    {    
		$message .= "Модель: <b> " . $_POST['deviceModel'] . "</b>\r\n<br>";
		$message .= "Платформа: <b> " . $_POST['devicePlatform'] . "</b>\r\n<br>";    
		$message .= "Версия: <b> " . $_POST['deviceVersion'] . "</b>\r\n<br>";
		$message .= "UUID: <b> " . $_POST['deviceUuid'] . "</b>\r\n<br>";
	} else {
		$message .= "Модель: <b> " . $_POST['device']['model'] . "</b>\r\n<br>";
		$message .= "Платформа: <b> " . $_POST['device']['platform'] . "</b>\r\n<br>";    
		$message .= "Версия: <b> " . $_POST['device']['version'] . "</b>\r\n<br>";
		$message .= "UUID: <b> " . $_POST['device']['uuid'] . "</b>\r\n<br>";
	}
   
    $header  = "Content-Type:text/html;charset=utf-8 \r\n"; 
    $header .= "From:app@appsstudio.ru \r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-type: text/html\r\n";
   
    $retval = mail ($to,$subject,$message,$header);
   
    if( $retval == true )
    {
        $result = array(
            "status" => "ok"
        );      
    }
    else
    {
        $result = array(
            "status" => "error",
            "error" => "Message could not be sent..",
        );
    }

} else
{
    $result = array(
        "status" => "error",
        "error" => "Bad input",
    );
    
}

echo json_encode($result);

?>