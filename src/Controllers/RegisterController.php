<?php

namespace Src\Controllers;

use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RegisterController extends Controller
{
    public function setLayout():void
    {

    }
    public function index(
        RequestInterface $request,
        ResponseInterface $response,
    )
    {
        return $this->renderer->render($response, '/auth/register.php');
    }
    public function register(
        RequestInterface $request,
        ResponseInterface $response,
    )
    {
        ORM::forTable('users')->create([
            'phone' => $request->getParsedBody()['phone'],
            'password' => $request->getParsedBody()['password'],
        ])->save();
        return $response->withHeader('Location', '/login')->withStatus(302);
    }
}