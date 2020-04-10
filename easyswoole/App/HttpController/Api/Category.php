<?php


namespace App\HttpController\Api;


use EasySwoole\Http\AbstractInterface\Controller;

class Category extends Controller
{

    public function category()
    {
        $data = [
            'id' => 1,
            'name' => 'Alilini',
        ];

        return $this->writeJson('200', $data, 'ok');
        // $this->response()->write('weishenmyaozheyagnzixie');
    }
}
