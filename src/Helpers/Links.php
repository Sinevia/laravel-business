<?php

namespace Sinevia\Business\Helpers;

class Links {
    public static function adminHome($queryData = []) {
        return action('\Sinevia\Business\Http\Controllers\BusinessController@anyIndex') . self::buildQueryString($queryData);
    }

    public static function customerInvoiceView($queryData = []) {
        return action('\Sinevia\Business\Http\Controllers\BusinessController@getCustomerInvoiceView') . self::buildQueryString($queryData);
    }

    public static function adminInvoiceManager($queryData = []) {
        return action('\Sinevia\Business\Http\Controllers\BusinessController@getInvoiceManager') . self::buildQueryString($queryData);
    }

    public static function adminInvoiceCreate($queryData = []) {
        return action('\Sinevia\Business\Http\Controllers\BusinessController@postInvoiceCreateAjax') . self::buildQueryString($queryData);
    }

    public static function adminInvoiceDelete($queryData = []) {
        return action('\Sinevia\Business\Http\Controllers\BusinessController@postInvoiceDelete') . self::buildQueryString($queryData);
    }

    public static function adminInvoiceMoveToTrash($queryData = []) {
        return action('\Sinevia\Business\Http\Controllers\BusinessController@postInvoiceMoveToTrash') . self::buildQueryString($queryData);
    }

    public static function adminInvoiceUpdate($queryData = []) {
        return action('\Sinevia\Business\Http\Controllers\BusinessController@getInvoiceUpdate') . self::buildQueryString($queryData);
    }

    public static function adminMediaManager($queryData = []){
        return config('cms.urls.media-manager') . self::buildQueryString($queryData);
    }

    public static function adminTransactionManager($queryData = []) {
        return action('\Sinevia\Business\Http\Controllers\BusinessController@getTransactionManager') . self::buildQueryString($queryData);
    }

    public static function adminTransactionCreate($queryData = []) {
        return action('\Sinevia\Business\Http\Controllers\BusinessController@postTransactionCreateAjax') . self::buildQueryString($queryData);
    }

    public static function adminTransactionDelete($queryData = []) {
        return action('\Sinevia\Business\Http\Controllers\BusinessController@postTransactionDelete') . self::buildQueryString($queryData);
    }

    public static function adminTransactionMoveToTrash($queryData = []) {
        return action('\Sinevia\Business\Http\Controllers\BusinessController@postTransactionMoveToTrash') . self::buildQueryString($queryData);
    }

    public static function adminTransactionUpdate($queryData = []) {
        return action('\Sinevia\Business\Http\Controllers\BusinessController@postTransactionUpdate') . self::buildQueryString($queryData);
    }

    private static function buildQueryString($queryData = []) {
        $queryString = '';
        if (count($queryData)) {
            $queryString = '?' . http_build_query($queryData);
        }
        return $queryString;
    }

}
