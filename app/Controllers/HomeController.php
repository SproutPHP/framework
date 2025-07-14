<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        $release = getLatestRelease();
        $appName = config('app.name', 'SproutPHP');
        return "<p>A minimilistic php-framework designed for go-to developer without the need for javascript or heavy modules. 🌳</p>
        <small>{$appName} latest release: <strong>$release</strong></small>";
    }
}
