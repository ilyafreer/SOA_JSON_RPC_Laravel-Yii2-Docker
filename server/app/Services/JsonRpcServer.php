<?php


namespace App\Services;
use App\Http\Response\JsonRpcResponse,
    Illuminate\Http\Request,
    App\Http\Controllers\Controller;

class JsonRpcServer
{
    public function handle(Request $request, Controller $controller)
    {
        try {
            $content = json_decode($request->getContent(), true);
            if (empty($content)) {
                throw new \Exception('Empty body of request from client. Request');
            }
            $result = $controller->{$content['method']}(...[$content['params']]);
            return JsonRpcResponse::success($result, $content['id']);
        } catch (\Exception $e) {
            return JsonRpcResponse::error($e->getMessage());
        }
    }
}
