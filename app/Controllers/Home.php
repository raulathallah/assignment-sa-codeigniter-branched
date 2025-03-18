<?php

namespace App\Controllers;

use CodeIgniter\Files\File;

class Home extends BaseController
{
    public function index(): string
    {
        //dd(user());
        return view('home');
    }

    public function dashboardStudent()
    {
        return view('dashboard/student');
    }

    public function dashboardAdmin()
    {
        return view('dashboard/admin');
    }

    public function dashboardLecturer()
    {
        return view('dashboard/lecturer');
    }


    public function sendEmail()
    {
        $email = service('email');

        $usersToEmail = [
            'haha9999@yopmail.com',
            'hihi9999@yopmail.com'
        ];

        $email->setTo($usersToEmail);
        $email->setSubject('Email Test dengan Template HTML');

        $filePath = ROOTPATH . 'public/uploads/Laporan.pdf';
        $imagePath = ROOTPATH . 'public/uploads/foto.jpg';

        if (file_exists($filePath)) {
            $email->attach($filePath);
        }

        if (file_exists($imagePath)) {
            $email->attach($imagePath);
        }

        $data = [
            'title' => 'Pemberitahuan Penting',
            'name' => 'John Doe',
            'content' => 'Ini adalah isi email yang akan dikirimkan.',
            'features' => [

                'Fitur 1: Informasi penting',

                'Fitur 2: Detail produk',

                'Fitur 3: Cara penggunaan'

            ]

        ];

        $message = view('email', $data); // Isi konten email

        $email->setMessage($message);

        if ($email->send()) {

            return redirect()->to('/')->with('success', 'Email berhasil dikirim');
        } else {

            $data = ['error' => $email->printDebugger()];

            return view('email_form', $data);
        }
    }

    public function upload()

    {
        $userfile = $this->request->getFile('userfile');

        if (!$userfile->isValid()) {

            return view('home', [

                'error' => $userfile->getErrorString()

            ]);
        }

        $validationRulesDocument = [
            'userfile' => [
                'label' => 'Dokumen',
                'rules' => [
                    'uploaded[userfile]',
                    'mime_in[userfile,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document]',
                    'max_size[userfile,5120]', // 5MB dalam KB (5 * 1024)
                ],
                'errors' => [
                    'uploaded' => 'Silakan pilih file untuk diunggah',
                    'mime_in' => 'File harus berformat PDF, DOC, atau DOCX',
                    'max_size' => 'Ukuran file tidak boleh melebihi 5MB'
                ]
            ]
        ];



        if ($this->isAllowedFileTypeDocument($userfile)) {
            if (!$this->validate($validationRulesDocument)) {

                return view('home', [

                    'errors' => $this->validator->getErrors()

                ]);
            }
        }



        $validationRulesImage = [
            'userfile' => [
                'label' => 'Gambar',
                'rules' => [
                    'uploaded[userfile]',
                    'is_image[userfile]',
                    'mime_in[userfile,image/jpg,image/jpeg,image/png,image/gif]',
                    'max_size[userfile,5120]', // 5MB dalam KB (5 * 1024)
                    //'min_dims[userfile,700,700]'
                ],
                'errors' => [
                    'uploaded' => 'Silakan pilih file gambar untuk diunggah',
                    'is_image' => 'File harus berupa gambar',
                    'mime_in' => 'File harus berformat JPG, JPEG, PNG, atau GIF',
                    'max_size' => 'Ukuran file tidak boleh melebihi 5MB',
                    //'min_dims' => 'Dimensi file minimum 700x700'
                ]

            ]

        ];

        if ($this->isAllowedFileTypeImage($userfile)) {
            if (!$this->validate($validationRulesImage)) {

                return view('home', [

                    'errors' => $this->validator->getErrors()

                ]);
            }
        }



        $newName = $userfile->getRandomName();

        //$userfile->move(WRITEPATH . 'uploads', $newName);
        //$filepath = WRITEPATH . 'uploads/original' . $newName;
        $image = service('image');



        $image->withFile($userfile)
            ->fit(100, 100, 'center')

            ->save(WRITEPATH . 'uploads/thumbnail/' . 'thumbnail_' . $newName);

        $image->withFile($userfile)
            ->resize(300, 300, true, 'height')
            ->text(
                'Copyright 2020 My Photo Co',
                [
                    'color'     => '#fff',
                    'opacity'   => 0.5,
                    'withShadow'   => true,
                    'hAlign'    => 'center',
                    'vAlign'    => 'botton',
                    'fontSize'  => 20,
                ]
            )
            ->save(WRITEPATH . 'uploads/watermark/' .  'wm_' . $newName);


        $userfile->move(WRITEPATH . 'uploads/original', 'original_' . $newName);
        $filepath = WRITEPATH . 'uploads/original/' . $newName;

        $data = ['uploaded_fileinfo' => new File($filepath)];

        return view('uploads/success_page', $data);
    }

    // Function to check if the file type is not allowed
    private function isAllowedFileTypeImage($file)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = $file->getClientExtension(); // Get file extension

        // If the file's extension is not in the allowed list
        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            return false; // Not allowed
        }

        return true; // Allowed
    }

    // Function to check if the file type is not allowed
    private function isAllowedFileTypeDocument($file)
    {
        $allowedExtensions = ['docx', 'doc', 'pdf'];
        $fileExtension = $file->getClientExtension(); // Get file extension

        // If the file's extension is not in the allowed list
        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            return false; // Not allowed
        }

        return true; // Allowed
    }
}
