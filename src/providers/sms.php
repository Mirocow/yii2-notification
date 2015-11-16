<?php

namespace mirocow\notification\providers;

/**
 * Created by PhpStorm.
 * User: mirocow
 * Date: 24.08.14
 * Time: 20:44
 */
class sms
{
    public $config = [
        'gate' => '',
        'port' => 80,
        'login' => '',
        'password' => '',
        'signature' => '',
    ];

    public function send($args = [])
    {
        return self::sms_send($this->config['gate'], $this->config['port'],
            $this->config['login'], $this->config['password'], $args[0],
            $args[1], $this->config['signature']);
    }

    /*
    * функция передачи сообщения
    */
    private function sms_send(
        $host,
        $port,
        $login,
        $password,
        $phone,
        $text,
        $sender = false,
        $wapurl = false
    ) {
        $fp = fsockopen($host, $port, $errno, $errstr);
        if (!$fp) {
            return "errno: $errno \nerrstr: $errstr\n";
        }
        fwrite($fp,
            "GET /send/" . "?phone=" . rawurlencode($phone) . "&text=" . rawurlencode($text) . ($sender ? "&sender=" . rawurlencode($sender) : "") . ($wapurl ? "&wapurl=" . rawurlencode($wapurl) : "") . "  HTTP/1.0\n");
        fwrite($fp, "Host: " . $host . "\r\n");
        if ($login != "") {
            fwrite($fp,
                "Authorization: Basic " . base64_encode($login . ":" . $password) . "\n");
        }
        fwrite($fp, "\n");
        $response = "";
        while (!feof($fp)) {
            $response .= fread($fp, 1);
        }
        fclose($fp);
        list($other, $responseBody) = explode("\r\n\r\n", $response, 2);

        return $responseBody;
    }

    /*
    * использование функции передачи сообщения
    */
    /*
    echo send("gate.prostor-sms.ru", 80, "api_login", "api_password",
                        "71234567890", "text here", "prostor-sms", "wap.yousite.ru");
    */
    /*
    * функция проверки состояния отправленного сообщения
    */
    private static function sms_status($host, $port, $login, $password, $sms_id)
    {
        $fp = fsockopen($host, $port, $errno, $errstr);
        if (!$fp) {
            return "errno: $errno \nerrstr: $errstr\n";
        }
        fwrite($fp, "GET /status/" . "?id=" . $sms_id . "  HTTP/1.0\n");
        fwrite($fp, "Host: " . $host . "\r\n");
        if ($login != "") {
            fwrite($fp,
                "Authorization: Basic " . base64_encode($login . ":" . $password) . "\n");
        }
        fwrite($fp, "\n");
        $response = "";
        while (!feof($fp)) {
            $response .= fread($fp, 1);
        }
        fclose($fp);
        list($other, $responseBody) = explode("\r\n\r\n", $response, 2);

        return $responseBody;
    }

    /*
    * использование функции проверки состояния отправленного сообщения
    */
    /*
    echo status("gate.prostor-sms.ru", 80, "api_login", "api_password", "12345");
    */

}