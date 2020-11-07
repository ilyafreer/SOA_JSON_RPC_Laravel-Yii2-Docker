<?php

namespace App\Http\Controllers;


class DataController extends Controller
{
    public function getCasinoBanner(array $params)
    {
        return 'test';
//        $data = Data::where('page_uid', $params['page_uid'])->first();
//
//        return $data;
    }

    public function create(array $params)
    {
        $data = DataCreate::create($params);

        return $data;
    }

    public function test()
    {
        echo '<h1>Data from client on Yii2</h1>';
        $a = file_get_contents("http://172.20.0.3/site/about");
//        $a = 'server test';
        echo $a;
    }
}
