<?php

namespace Src\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class OrderController extends Controller
{
    public function store(
        RequestInterface $request,
        ResponseInterface $response,
    )
    {
        $values = [
            'user_id'=>$_SESSION['user_id'],
            'card_id'=>$_SESSION['card_id'],
        ];
    }
}