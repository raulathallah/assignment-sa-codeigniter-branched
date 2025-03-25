<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Course;
use App\Entities\Enrollment as EntitiesEnrollment;
use App\Libraries\DataParams;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\StudentModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;
use Myth\Auth\Models\UserModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Style\Alignment as Alignment;

class Enrollment extends BaseController
{
    private $modelEnrollment;
    private $modelCourse;
    private $modelStudent;
    private $modelUser;
    protected $db;
    protected $enrollmentsData;

    public function __construct()
    {
        $this->db = db_connect();
        $this->db->initialize();

        $this->modelEnrollment = new EnrollmentModel();
        $this->modelCourse = new CourseModel();
        $this->modelStudent = new StudentModel();

        $this->modelUser = new UserModel();
    }

    public function index()
    {
        //
        $params = new DataParams([
            'search' => $this->request->getGet('search'),
            //'sort' => $this->request->getGet('sort'),
            //'order' => $this->request->getGet('order'),
            'page' => $this->request->getGet('page_courses'),
            'perPage' => $this->request->getGet('perPage')
        ]);
        $result = $this->modelEnrollment->getFilteredEnrollments($params);

        $data = [
            'enrollments' => $result['enrollments'],
            'pager' => $result['pager'],
            'total' => $result['total'],
            'params' => $params,
            'baseUrl' => in_groups('admin') ? base_url('admin/enrollments') : base_url('enrollments'),
            'type' => in_groups('admin') == 1 ? 'admin' : '',
            'addHref' => in_groups('admin') ? '/admin/enrollments/create' : '/enrollments/create'
        ];

        return view('enrollments/v_enrollments', $data);
    }

    private function filterData($student_id = '', $name = '')
    {
        return $this->modelEnrollment->getEnrollmentReportData($student_id, $name);
    }

    public function enrollmentForm()

    {

        $student_id = $this->request->getVar('student_id');

        $name = $this->request->getVar('name');

        $filteredData = $this->filterData($student_id, $name);

        $data = [

            'title' => 'Enrollment Report',

            'enrollments' => $filteredData,

            'filters' => [

                'student_id' => $student_id,

                'name' => $name

            ]

        ];

        return view('report/enrollments', $data);
    }

    public function enrollmentExcel()
    {
        $student_id = $this->request->getVar('student_id');
        $name = $this->request->getVar('name');

        $enrollments = $this->filterData($student_id, $name);

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'LAPORAN ENROLLMENT MATA KULIAH');

        $sheet->mergeCells('A1:J1');

        $sheet->getStyle('A1')->getFont()->setBold(true);

        $sheet->getStyle('A1')->getFont()->setSize(14);

        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'Filter:');

        $sheet->setCellValue('B3', 'Student ID: ' . ($student_id ?? 'Semua'));

        $sheet->setCellValue('D3', 'Nama: ' . ($name ?? 'Semua'));

        $sheet->getStyle('A3:D3')->getFont()->setBold(true);
        $headers = [

            'A5' => 'NO',

            'B5' => 'NIM',

            'C5' => 'NAMA MAHASISWA',

            'D5' => 'PROGRAM STUDI',

            'E5' => 'SEMESTER',

            'F5' => 'KODE MK',

            'G5' => 'NAMA MATA KULIAH',

            'H5' => 'SKS',

            'I5' => 'TAHUN AKADEMIK',

            //'J5' => 'STATUS'

        ];

        foreach ($headers as $cell => $value) {

            $sheet->setCellValue($cell, $value);

            $sheet->getStyle($cell)->getFont()->setBold(true);

            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $row = 6;

        $no = 1;

        //dd($enrollments);

        foreach ($enrollments as $enrollment) {

            $sheet->setCellValue('A' . $row, $no);

            $sheet->setCellValue('B' . $row, $enrollment->student_id);

            $sheet->setCellValue('C' . $row, $enrollment->name);

            $sheet->setCellValue('D' . $row, $enrollment->study_program);

            $sheet->setCellValue('E' . $row, $enrollment->semester);

            $sheet->setCellValue('F' . $row, $enrollment->course_code);

            $sheet->setCellValue('G' . $row, $enrollment->course_name);
            $sheet->setCellValue('H' . $row, $enrollment->credits);
            $sheet->setCellValue('I' . $row, $enrollment->academic_year);
            //$sheet->setCellValue('J' . $row, $enrollment->status);

            $row =  $row + 1;
            $no = $no + 1;
        }

        foreach (range('A', 'J') as $column) {

            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Buat border untuk seluruh tabel

        $styleArray = [

            'borders' => [

                'allBorders' => [

                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,

                ],

            ],

        ];

        $sheet->getStyle('A5:J' . ($row - 1))->applyFromArray($styleArray);

        $filename = 'Laporan_Mata_Kuliah_Enrol_' . date('Y-m-d-His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment;filename="' . $filename . '"');

        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);

        $writer->save('php://output');

        exit();
    }


    public function create(): string
    {
        $new = new EntitiesEnrollment();
        $courses = $this->modelCourse->findAll();
        $students = $this->modelStudent->findAll();

        $actionRole = '';
        $admin = '/admin/enrollments';
        $student = '/enrollments';

        if (in_groups('admin')) {
            $actionRole = $admin . '/store';
        } else {
            $actionRole = $student . '/store';
        }

        return view('enrollments/v_enrollments_form', ['action' => $actionRole, 'enrollment' => $new, 'courses' => $courses, 'students' => $students]);
    }

    public function store()
    {
        $userToEmail = user()->email;

        $courseData = $this->modelCourse->find($this->request->getPost('course_id'));

        $studentData = null;
        if ($this->request->getPost('student_id')) {
            $studentData = $this->modelStudent->find($this->request->getPost('student_id'));
        } else {
            $studentData = $this->modelStudent->where('user_id', user_id())->first();
        }

        if (in_groups('admin')) {
            $userToEmail = $this->modelUser->find($studentData->user_id)->email;
        }

        $time = new Time();

        $email = service('email');
        $email->setTo($userToEmail);
        $email->setSubject('Course enrollments');
        $data = [
            'title' => 'Course Enrollment Information',
            'name' => user()->username,
            'studentId' => $studentData->student_id,
            'studentFullName' => $studentData->name,
            'courseCode' => $courseData->code,
            'courseName' => $courseData->name,
            'courseCredits' => $courseData->credits,
            'date' => $time->format('Y-m-d H:i:s')
        ];
        $message = view('email', $data); // Isi konten email
        $email->setMessage($message);

        //return redirect()->to('/')->with('success', 'Email berhasil dikirim');
        $data = new EntitiesEnrollment($this->request->getPost());
        $course = $this->modelCourse->find($this->request->getPost('course_id'));
        $data->id = null;
        $data->status = 'active';

        if ($studentData) {
            $data->student_id = $studentData->id;
        }
        $data->semester = $course->semester;

        $store = $this->modelEnrollment->save($data);
        if (!$store) {
            // Output any error (like if save failed)
            dd($this->modelEnrollment->errors());
        }

        if ($email->send()) {
            session()->setFlashdata('success', 'Enrollments berhasil disimpan');

            if (in_groups('admin')) {
                return redirect()->to('admin/enrollments');
            }

            return redirect()->to('/enrollments');
        } else {

            $data = ['error' => $email->printDebugger()];

            $this->modelEnrollment->delete($this->modelEnrollment->getInsertID());
            return view('email_form', $data);
        }
    }

    public function delete($id)
    {
        $enrollment = $this->modelEnrollment->find($id);
        if (empty($enrollment)) {
            return redirect()->to('admin/enrollments')->with('error', 'Enrollment tidak ditemukan');
        }

        $this->modelEnrollment->delete($id);
        return redirect()->to('admin/enrollments')->with('message', 'Enrollment berhasil dihapus');
    }
}
