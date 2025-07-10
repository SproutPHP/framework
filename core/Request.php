<?php

namespace Core;

class Request {
    public $method, $uri;

    private function __construct($method, $uri) {
        $this->method = $method;
        $this->uri = $uri;
    }

    /**
     * Static method that reads the HTTP method + URI and returns a new Request object
     */
    public static function capture(){
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        return new self($method, $uri);
    }
}