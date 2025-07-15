<?php

namespace App\Controllers;

use Core\Http\Request;
use Core\Support\Storage;
use Core\Support\Validator;

class ValidationTestController
{
    public function index()
    {
        if (is_htmx_request()) {
            // Only return the form fragment for HTMX requests
            return view('partials/validation-test', [
                'errors' => [],
                'old' => [],
            ]);
        }

        return view('validation-test', ['title' => 'ValidationTestController Index']);
    }

    public function handleForm()
    {
        $request = Request::capture();

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'avatar' => $request->input('avatar'),
        ];

        // Or You can get all input data simply using
        // $data = $request->data;

        $validator = new Validator($data, [
            'email' => 'required|email',
            'name' => 'required|min:3',
            'avatar' => 'image|mimes:jpg,jpeg,png,gif',
        ]);

        // File Upload Validation
        $avatarError = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = Storage::put($file, 'avatars');
            if (!$path) {
                $avatarError = 'File upload failed.';
            }
        } else {
            $avatarError = "Please upload an avatar.";
        }

        if ($validator->fails() || $avatarError) {
            $errors = $validator->errors();
            if ($avatarError)
                $errors['avatar'] = $avatarError;
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
            'avatar_url' => isset($path) ? Storage::url($path) : null,
        ]);
    }
}
