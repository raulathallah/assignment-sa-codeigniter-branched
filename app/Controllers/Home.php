<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\StudentGradeModel;
use App\Models\StudentModel;
use CodeIgniter\Files\File;
use CodeIgniter\I18n\Time;

class Home extends BaseController
{

    private $modelStudent;
    private $modelGrades;
    private $modelCourse;

    private $creditRequiredPerSemester;
    private $db;
    public function __construct()
    {
        $this->db = db_connect();
        $this->db->initialize();

        $this->modelStudent = new StudentModel();
        $this->modelGrades = new StudentGradeModel();
        $this->modelCourse = new CourseModel();

        $this->creditRequiredPerSemester = [
            ['semester' => 1, 'credits_required' => 20],
            ['semester' => 2, 'credits_required' => 20],
            ['semester' => 3, 'credits_required' => 20],
            ['semester' => 4, 'credits_required' => 20],
            ['semester' => 5, 'credits_required' => 20],
            ['semester' => 6, 'credits_required' => 18],
            ['semester' => 7, 'credits_required' => 14],
            ['semester' => 8, 'credits_required' => 12],
        ];
    }

    public function index(): string
    {
        return view('home');
    }

    public function dashboardStudent()
    {
        $creditsByGrade = $this->getCreditsByGrade();
        $creditComparison = $this->getCreditComparison();
        $gpaData = $this->getGpaPerSemester();

        return view('dashboard/student', [
            'creditsByGrade' => json_encode($creditsByGrade),
            'creditComparison' => json_encode($creditComparison),
            'gpaData' => json_encode($gpaData),

        ]);
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

            return view('students/v_mahasiswa_upload', [

                'error' => $userfile->getErrorString()

            ]);
        }

        $validationRulesDocument = [
            'userfile' => [
                'label' => 'Dokumen',
                'rules' => [
                    'uploaded[userfile]',
                    //'mime_in[userfile,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document]',
                    'mime_in[userfile,application/pdf]',
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

                return view('students/v_mahasiswa_upload', [

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

        //$newName = $userfile->getRandomName();

        //$userfile->move(WRITEPATH . 'uploads', $newName);
        //$filepath = WRITEPATH . 'uploads/original' . $newName;
        //$image = service('image');
        // $image->withFile($userfile)
        //     ->fit(100, 100, 'center')
        //     ->save(WRITEPATH . 'uploads/thumbnail/' . 'thumbnail_' . $newName);
        // $image->withFile($userfile)
        //     ->resize(300, 300, true, 'height')
        //     ->text(
        //         'Copyright 2020 My Photo Co',
        //         [
        //             'color'     => '#fff',
        //             'opacity'   => 0.5,
        //             'withShadow'   => true,
        //             'hAlign'    => 'center',
        //             'vAlign'    => 'botton',
        //             'fontSize'  => 20,
        //         ]
        //     )
        //     ->save(WRITEPATH . 'uploads/watermark/' .  'wm_' . $newName);
        $studentData = $this->modelStudent->where('user_id', user_id())->first();

        $nowTime = new Time();
        $dateString = $nowTime->toDateString();
        $timeString = $nowTime->toTimeString();
        $dateStringWithoutSpecialChar = str_replace("-", "", $dateString);
        $timeStringWithoutSpecialChar = str_replace(":", "", $timeString);

        $fileName = $studentData->student_id . '_' . $dateStringWithoutSpecialChar . '_' . $timeStringWithoutSpecialChar .  '.pdf';

        $userfile->move(WRITEPATH . 'uploads/original', $fileName);
        $filepath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'original' . DIRECTORY_SEPARATOR . $fileName;

        if (user_id()) {
            $studentData->diploma_path = $fileName;
            $this->modelStudent->save($studentData);
        }

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

    public function downloadPdf($filename)
    {
        // Define the path using WRITEPATH and check for the file
        $filePath = WRITEPATH . 'uploads/original/' . $filename;

        if (file_exists($filePath)) {
            return $this->response->download($filePath, null);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }
    }

    private function getCreditsByGrade()
    {
        $getGradeCredits = $this->modelGrades->select('student_grades.grade_letter, student_grades.grade_value as credits')
            ->join('enrollments', 'student_grades.enrollment_id = enrollments.id')
            ->join('students', 'enrollments.student_id = students.id')
            ->where('students.user_id', user_id())
            ->findAll();

        $gradeCredits = [];

        foreach ($getGradeCredits as $row) {
            array_push($gradeCredits, ['grade_letter' => $row->grade_letter, 'credits' => $row->credits]);
        }



        // $dummyGradeCredits = [
        //     ['grade_letter' => 'A', 'credits' => 45],
        //     ['grade_letter' => 'B+', 'credits' => 20],
        //     ['grade_letter' => 'B', 'credits' => 32],
        //     ['grade_letter' => 'C', 'credits' => 8],
        //     ['grade_letter' => 'C', 'credits' => 18],
        //     ['grade_letter' => 'D', 'credits' => 6]
        // ];

        $backgroundColors = [
            'A' => 'rgb(54, 162, 235)', // Biru untuk A
            'B+' => 'rgb(75, 192, 192)', // Cyan untuk B+
            'B' => 'rgb(153, 102, 255)', // Ungu untuk B
            'C+' => 'rgb(255, 205, 86)', // Kuning untuk C+
            'C' => 'rgb(255, 159, 64)', // Oranye untuk C
            'D' => 'rgb(255, 99, 132)' // Merah untuk D
        ];

        $gradeLabels = [];
        $creditCounts = [];
        $colors = [];

        foreach ($gradeCredits as $row) {

            $gradeLabels[] = $row['grade_letter'] . ' = ' . $row['credits'] . ' Credits';

            $creditCounts[] = (int)$row['credits'];

            $colors[] = $backgroundColors[$row['grade_letter']];
        }


        return [

            'labels' => $gradeLabels,
            'datasets' => [
                [
                    'label' => 'Credits by Grade',
                    'data' => $creditCounts,
                    'backgroundColor' => $colors,
                    'hoverOffset' => 4
                ]

            ]

        ];
    }

    private function getCreditComparison()
    {
        $getCreditTaken = $this->modelCourse
            ->select('sum(courses.credits) as credits, enrollments.semester, students.id')
            ->join('enrollments', 'enrollments.course_id = courses.id')
            ->join('students', 'enrollments.student_id = students.id')
            ->where('students.user_id', user_id())
            ->groupBy('enrollments.semester, students.id')
            ->findAll();

        $creditTaken = [];

        foreach ($getCreditTaken as $row) {
            array_push($creditTaken, [
                'credits_taken' => $row->credits,
                'semester' => $row->semester,
                //'id' => $row->id,
                'credits_required' => $this->getCreditBySemester($row->semester, $this->creditRequiredPerSemester)
            ]);
        };

        // $creditTaken = array_reduce($creditTakenArray, function ($acc, $curr) {
        //     $id = $curr['id'];
        //     // If the id does not exist in the accumulator, initialize it
        //     if (!isset($acc[$id])) {
        //         $acc[$id] = ['id' => $id, 'semester' => $curr['semester'], 'credits_taken' => 0];
        //     }

        //     // Sum the price
        //     $acc[$id]['credits_taken'] += $curr['credits'];

        //     return $acc;
        // }, []);

        // Convert the grouped array to a plain array of values
        //$creditTaken = array_values($creditTaken);

        // $dummyCredits = [
        //     ['semester' => 1, 'credits_taken' => 20, 'credits_required' => 20],
        //     ['semester' => 2, 'credits_taken' => 19, 'credits_required' => 22],
        //     ['semester' => 3, 'credits_taken' => 22, 'credits_required' => 24],
        //     ['semester' => 4, 'credits_taken' => 20, 'credits_required' => 22],
        //     ['semester' => 5, 'credits_taken' => 18, 'credits_required' => 20],
        //     ['semester' => 6, 'credits_taken' => 16, 'credits_required' => 18]
        // ];

        foreach ($creditTaken as $row) {
            $labels[] = 'Semester ' . $row['semester'];
            $creditsTaken[] = (int)$row['credits_taken'];
            $creditsRequired[] = (int)$row['credits_required'];
        }

        return [
            'labels' => $labels,
            'datasets' => [

                [

                    'label' => 'Credits Taken',

                    'data' => $creditsTaken,

                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',

                    'borderColor' => 'rgb(54, 162, 235)',

                    'borderWidth' => 1

                ],

                [

                    'label' => 'Credits Required',

                    'data' => $creditsRequired,

                    'backgroundColor' => 'rgba(255, 99, 132, 0.5)',

                    'borderColor' => 'rgb(255, 99, 132)',

                    'borderWidth' => 1

                ]

            ]

        ];
    }

    private function getGpaPerSemester()
    {
        $getGpa = $this->modelCourse
            ->select('sum(courses.credits * student_grades.grade_value) / sum(courses.credits) as semester_gpa, enrollments.semester, students.id')
            ->join('enrollments', 'enrollments.course_id = courses.id')
            ->join('students', 'enrollments.student_id = students.id')
            ->join('student_grades', 'enrollments.id = student_grades.enrollment_id')
            ->where('students.user_id', user_id())
            ->groupBy('enrollments.semester, students.id')
            ->findAll();

        //dd($getGpa);

        $defaultGpaData = [
            ['semester' => 1, 'semester_gpa' => 0],
            ['semester' => 2, 'semester_gpa' => 0],
            ['semester' => 3, 'semester_gpa' => 0],
            ['semester' => 4, 'semester_gpa' => 0],
            ['semester' => 5, 'semester_gpa' => 0],
            ['semester' => 6, 'semester_gpa' => 0],
            ['semester' => 7, 'semester_gpa' => 0],
            ['semester' => 8, 'semester_gpa' => 0],
        ];

        foreach ($getGpa as $row) {
            $defaultGpaData[$row->semester - 1]['semester_gpa'] = $row->semester_gpa;
        }




        foreach ($defaultGpaData as $row) {

            $semesters[] = 'Semester ' . $row['semester'];

            $gpaData[] = round($row['semester_gpa'], 2);
        }

        return [

            'labels' => $semesters,

            'datasets' => [

                [

                    'label' => 'GPA',

                    'data' => $gpaData,

                    'borderColor' => 'rgba(75, 192, 192, 1)',

                    'tension' => 0.1,

                    'fill' => false

                ]

            ]

        ];
    }

    private function getCreditBySemester($semester, $array)
    {
        foreach ($array as $item) {
            if ($item['semester'] === $semester) {
                return $item['credits_required'];
            }
        }
        return "Semester not found.";
    }
}
