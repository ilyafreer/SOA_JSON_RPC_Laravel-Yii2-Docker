<?php


namespace App\Http\Response;


class JsonRpcResponse
{
    const JSON_RPC_VERSION = '2.0';

    public static function success(array $result, string $id = null)
    {
        return [
            'jsonrpc' => self::JSON_RPC_VERSION,
            'result'  => $result,
            'id'      => $id,
        ];
    }

    public static function error(string $error)
    {
        return [
            'jsonrpc' => self::JSON_RPC_VERSION,
            'error'  => $error,
            'id'      => null,
        ];
    }
}
