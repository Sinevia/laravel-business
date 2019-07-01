<?php

namespace Sinevia\Business\Helpers;

class Helper {

    public static function auth($message = '', $data = []) {
        return json_encode(['status' => 'authenticate', 'message' => $message]);
    }

    public static function error($message = '', $data = []) {
        return json_encode(['status' => 'error', 'message' => $message, 'data' => $data]);
    }

    public static function success($message = '', $data = []) {
        return json_encode(['status' => 'success', 'message' => $message, 'data' => $data]);
    }

    public static function htmlFormatAmountWithCurrencySymbol($amount, $currency) {
        $symbol = "";
        if ($currency == "GBP") {
            $symbol = "&pound;";
        }
        if ($currency == "EUR") {
            $symbol = "&euro;";
        }
        if ($currency == "USD") {
            $symbol = "$";
        }
        if ($symbol != "") {
            return $symbol . $amount;
        }
        return $amount . " " . $currency;
    }

}
