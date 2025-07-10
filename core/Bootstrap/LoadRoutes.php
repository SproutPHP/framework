<?php

namespace Core\Bootstrap;

class LoadRoutes
{
    public static function boot()
    {
        require_once __DIR__ . '/../../routes/web.php';
    }
}
