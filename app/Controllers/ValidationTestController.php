<?php

namespace App\Controllers;

use Core\Support\Validator;

class ValidationTestController
{
    public function index()
    {
        return view('validation-test', ['title' => 'ValidationTestController Index']);
    }

    public function handleForm()
    {
        $data = $_POST;
        $validator = new Validator($data, [
            'email' => 'required|email',
            'name' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            // Return only the form fragment with errors for HTMX
            return view('partials/validation-form', [
                'errors' => $validator->errors(),
                'old' => $data,
            ]);
        }

        // On success, return a success message fragment
        return view('partials/validation-success', [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }
}