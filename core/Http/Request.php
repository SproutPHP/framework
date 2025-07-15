<?php

namespace Core\Http;

class Request
{
    public $method, $uri, $data = [], $files = [];

    private function __construct($method, $uri, $data = [], $files = [])
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->data = $data;
        $this->files = $files;
    }

    /**
     * Static method that reads the HTTP method + URI and returns a new Request object
     */
    public static function capture()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // If get/post request
        $data = array_merge($_GET, $_POST);

        // if JSON, decode and merge
        if (stripos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') === 0) {
            $json = file_get_contents('php://input');
            $jsonData = json_decode($json, true);
            if (is_array($jsonData)) {
                $data = array_merge($data, $jsonData);
            }
        }

        $files = $_FILES;

        return new self($method, $uri, $data, $files);
    }

    /**
     * To request the input fields
     */
    public function input($key, $default = null)
    {
        return $this->data[$key] ?? $default;
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