<?php

    /**
     * Created by PhpStorm.
     * User: cranky4
     * Date: 25/04/2017
     * Time: 10:47
     */

    namespace App\Service;

    /**
     * Class Mailer
     * @package App\Service
     */
    class OrderMailer
    {
        /**
         * @var string
         */
        public $to;

        /**
         * @var string
         */
        public $subject;

        /**
         * Mailer constructor.
         *
         * @param $to
         */
        public function __construct($to)
        {
            $this->to = $to;
        }

        /**
         * @param $requestParams
         *
         * @return string
         */
        public static function composeOrderMessage($requestParams)
        {
            $message = '';

            // Version <= 1.1
            if (!empty($requestParams['water'])) {
                $message .= "Вода: <b> " . $requestParams['water'] . "</b>\r\n<br>";
            }
            if (!empty($requestParams['n'])) {
                $message .= "Количество бутылей: <b> " . $requestParams['n'] . "</b>\r\n<br>";
            }

            // Version >= 1.2
            if (!empty($requestParams['product'])) {
                foreach ($requestParams['product'] as $index => $value) {
                    $message .= "\r\n<br>";
                    $message .= "Товар № " . ($index + 1) . ": <b> " . $value . "</b>\r\n<br>";
                    $message .= "Количество бутылей: <b> " . $requestParams['amount'][$index] . "</b>\r\n<br>";
                }
            }

            // Version >= 2.0
            if (!empty($requestParams['address'])) {
                $message .= "\r\n<br>";
                if (!empty($requestParams['region'])) {
                    $message .= "Район: <b> " . $requestParams['region'] . "</b>\r\n<br>";
                }
                $message .= "Адрес: <b> " . $requestParams['address'] . "</b>\r\n<br>";
                if (!empty($requestParams['delivery'])) {
                    $message .= "Доставка: <b> " . $requestParams['delivery'] . "</b>\r\n<br>";
                }
                if (!empty($requestParams['comment'])) {
                    $message .= "Комментарий: <b> " . $requestParams['comment'] . "</b>\r\n<br>";
                }
            }
            $message .= self::composeIpInfo();
            $message .= self::composeDeviceInfo($requestParams);

            return $message;
        }

        /**
         * @return string
         */
        private static function composeIpInfo()
        {
            $IP = $_SERVER["REMOTE_ADDR"];;

            return "IP: <b> " . $IP . "</b>\r\n<br>";
        }

        /**
         * @param array $device
         *
         * @return string
         */
        private static function composeDeviceInfo(array $device)
        {
            $message = "Модель: <b> " . $device['deviceModel'] . "</b>\r\n<br>";
            $message .= "Платформа: <b> " . $device['devicePlatform'] . "</b>\r\n<br>";
            $message .= "Версия: <b> " . $device['deviceVersion'] . "</b>\r\n<br>";
            $message .= "UUID: <b> " . $device['deviceUuid'] . "</b>\r\n<br>";

            return $message;
        }

        /**
         * @param $requestParams
         *
         * @return string
         */
        public static function composeCallbackMessage($requestParams)
        {
            $message = "Время звонка: <b> " . $requestParams['time'] . "</b>\r\n<br>";

            $message .= self::composeIpInfo();
            $message .= self::composeDeviceInfo($requestParams['device']);

            return $message;
        }

        /**
         * @param $message
         *
         * @return bool
         */
        public function send($message)
        {
            $header = "Content-Type:text/html;charset=utf-8 \r\n";
            $header .= "From:app@appsstudio.ru \r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";

            return mail($this->to, $this->subject, $message, $header);
        }
    }