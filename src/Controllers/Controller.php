<?php

namespace Src\Controllers;

use Slim\Views\PhpRenderer;

class Controller
{
    public function __construct(
        protected PhpRenderer $renderer,
    )
    {
        $this->setLayout();
    }
    protected function setLayout():void
    {
        $this->renderer->setLayout('layout.php');
    }
    protected function generateSlug() {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $slug = '';

        for ($i = 0; $i < 8; $i++) {
            $slug .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $slug;
    }
}