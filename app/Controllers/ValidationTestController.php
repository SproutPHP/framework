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

        // List private files for the right grid
        $privateFiles = $this->getPrivateFiles();

        return view('validation-test', [
            'title' => 'ValidationTestController Index',
            'privateFiles' => $privateFiles,
        ]);
    }

    /**
     * Helper to get private files list
     */
    protected function getPrivateFiles()
    {
        $privateDir = Storage::path('', 'private');
        $privateFiles = [];

        if (is_dir($privateDir)) {
            foreach (scandir($privateDir) as $file) {
                if ($file !== '.' && $file !== '..' && is_file($privateDir . '/' . $file)) {
                    $privateFiles[] = $file;
                }
            }
        }
        return $privateFiles;
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

    /**
     * Handle private file upload
     */
    public function handlePrivateUpload()
    {
        $request = Request::capture();
        $error = null;
        $path = null;
        if ($request->hasFile('private_file')) {
            $file = $request->file('private_file');
            $path = Storage::put($file, '', 'private');
            if (!$path) {
                $error = 'Private file upload failed.';
            }
        } else {
            $error = 'Please select a file to upload.';
        }

        // HTMX: return only the private files list fragment
        if (isset($_SERVER['HTTP_HX_REQUEST']) && $_SERVER['HTTP_HX_REQUEST'] === 'true') {
            $privateFiles = $this->getPrivateFiles();
            return view('partials/private-files-list', [
                'privateFiles' => $privateFiles,
                'error' => $error,
            ]);
        }

        // Fallback: redirect back
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    /**
     * Render private files list fragment (for HTMX)
     */
    public function privateFilesListFragment()
    {
        $privateFiles = $this->getPrivateFiles();
        return view('partials/private-files-list', [
            'privateFiles' => $privateFiles,
        ]);
    }

    /**
     * Securely download a private file
     */
    public function downloadPrivateFile()
    {
        $filename = isset($_GET['file']) ? urldecode($_GET['file']) : null;
        if (!$filename) {
            http_response_code(400);
            echo 'Missing file parameter.';
            exit;
        }
        $privatePath = Storage::path($filename, 'private');
        if (!is_file($privatePath)) {
            http_response_code(404);
            echo 'File not found.';
            exit;
        }
        // Set headers for download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($privatePath));
        readfile($privatePath);
        exit;
    }
}
