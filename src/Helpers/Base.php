<?php

namespace Sinevia\Business\Helpers;

class Base {

    public static $base10 = '0123456789';
    public static $base16 = '0123456789ABCDEF';
    public static $base32 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567';
    public static $crockford32 = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';
    public static $base36 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public static $base58 = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
    public static $base62 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    public static $base64 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz+/';
    public static $base75 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_.,!=-*(){}[]';

    public static function to10($uid) {
        $uid = str_replace('-', '', $uid);
        return $uid;
    }

    public static function to16($uid) {
        $uid = str_replace('-', '', $uid);
        //$uid = base_convert($uid, 10, 16);
        $uid = self::convBase($uid, self::$base10, self::$base16);
        return $uid;
    }

    public static function to32($uid) {
        $uid = str_replace('-', '', $uid);
        $uid = self::convBase($uid, self::$base10, self::$base32);
        return $uid;
    }

    public static function toCrockford32($uid) {
        $uid = str_replace('-', '', $uid);
        $uid = self::convBase($uid, self::$base10, self::$crockford32);
        return $uid;
    }

    public static function to36($uid) {
        $uid = str_replace('-', '', $uid);
        $uid = self::convBase($uid, self::$base10, self::$base36);
        return $uid;
    }

    public static function to62($uid) {
        $uid = str_replace('-', '', $uid);
        $uid = self::convBase($uid, self::$base10, self::$base62);
        return $uid;
    }

    public static function to64($uid) {
        $uid = str_replace('-', '', $uid);
        $uid = self::convBase($uid, self::$base10, self::$base64);
        return $uid;
    }

    public static function to75($uid) {
        $uid = str_replace('-', '', $uid);
        $uid = self::convBase($uid, self::$base10, self::$base75);
        return $uid;
    }

    public static function convBase($numberInput, $fromBaseInput, $toBaseInput) {
        if ($fromBaseInput == $toBaseInput) {
            return $numberInput;
        }
        $fromBase = str_split($fromBaseInput, 1);
        $toBase = str_split($toBaseInput, 1);
        $number = str_split($numberInput, 1);
        $fromLen = strlen($fromBaseInput);
        $toLen = strlen($toBaseInput);
        $numberLen = strlen($numberInput);
        $retval = '';
        if ($toBaseInput == '0123456789') {
            $retval = 0;
            for ($i = 1; $i <= $numberLen; $i++) {
                $retval = bcadd($retval, bcmul(array_search($number[$i - 1], $fromBase), bcpow($fromLen, $numberLen - $i)));
            }
            return $retval;
        }
        if ($fromBaseInput != '0123456789') {
            $base10 = self::convBase($numberInput, $fromBaseInput, '0123456789');
        } else {
            $base10 = $numberInput;
        }
        if ($base10 < strlen($toBaseInput)) {
            return $toBase[$base10];
        }
        while ($base10 != '0') {
            $retval = $toBase[bcmod($base10, $toLen)] . $retval;
            $base10 = bcdiv($base10, $toLen, 0);
        }
        return $retval;
    }

}
