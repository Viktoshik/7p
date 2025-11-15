<?php

namespace Src\Controllers;

use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoginController extends Controller
{
    public function index(
        RequestInterface $request,
        ResponseInterface $response,
    )
    {
        return $this->renderer->render($response, '/auth/login.php');
    }
    public function login(
        RequestInterface $request,
        ResponseInterface $response,
    )
    {
        $phone = $request->getParsedBody()['phone'];
        $password = $request->getParsedBody()['password'];
        $user = ORM::forTable('users')->where('phone', $phone)->findOne();
        if (!$user) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
        if ($user['password'] !== $password) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
        $_SESSION['user_id'] = $user['id'];
        return $response->withHeader('Location', '/')->withStatus(302);
    }
}