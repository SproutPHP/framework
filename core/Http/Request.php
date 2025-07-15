<?php

namespace Core\Http;

class Request
{
    public $method, $uri;

    private function __construct($method, $uri)
    {
        $this->method = $method;
        $this->uri = $uri;
    }

    /**
     * Static method that reads the HTTP method + URI and returns a new Request object
     */
    public static function capture()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        return new self($method, $uri);
    }

    /**
     * File method to get the input files
     */
    public function file($key)
    {
        return $_FILES[$key] ?? null;
    }

    /**
     * File method to check if input is file
     */
    public function hasFile($key)
    {
        return isset($_FILES[$key]) && is_uploaded_file($_FILES[$key]['tmp_name']);
    }
}