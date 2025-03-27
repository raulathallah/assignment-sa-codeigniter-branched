<?php

namespace App\Controllers;

use App\Entities\Mahasiswa as EntitiesMahasiswa;
use App\Entities\Student;
use App\Libraries\DataParams;
use App\Models\M_Mahasiswa as ModelsMahasiswa;
use App\Models\StudentGradeModel;
use App\Models\StudentModel;
use CodeIgniter\I18n\Time;
use Myth\Auth\Models\UserModel;
use TCPDF;

class Mahasiswa extends BaseController
{
    private $modelStudent;
    private $modelGrades;
    private $modelUser;

    protected $studentsData;
    protected $db;
    private $baseUrlRoutes = '/student';

    public function __construct()
    {
        $this->db = db_connect();
        $this->db->initialize();

        $this->modelStudent = new StudentModel();
        $this->modelGrades = new StudentGradeModel();
        $this->modelUser = new UserModel();

        $this->studentsData = [

            (object)[

                'id' => 1,
                'student_id' => '181001',
                'name' => 'Agus Setiawan',

                'study_program' => 'Teknik Informatika',
                'current_semester' => 5,

                'academic_status' => 'Aktif',
                'entry_year' => 2018,
                'gpa' => 3.75

            ],

            (object)[

                'id' => 2,
                'student_id' => '182002',
                'name' => 'Budi Santoso',

                'study_program' => 'Sistem Informasi',
                'current_semester' => 4,

                'academic_status' => 'Aktif',
                'entry_year' => 2018,
                'gpa' => 3.45

            ],

            (object)[

                'id' => 3,
                'student_id' => '183003',
                'name' => 'Cindy Paramitha',

                'study_program' => 'Teknik Komputer',
                'current_semester' => 3,

                'academic_status' => 'Aktif',
                'entry_year' => 2018,
                'gpa' => 3.90

            ],

            (object)[

                'id' => 4,
                'student_id' => '184004',
                'name' => 'Deni Saputra',

                'study_program' => 'Teknik Informatika',
                'current_semester' => 6,

                'academic_status' => 'Aktif',
                'entry_year' => 2018,
                'gpa' => 3.60

            ],

            (object)[

                'id' => 5,
                'student_id' => '185005',
                'name' => 'Eko Prasetyo',

                'study_program' => 'Sistem Informasi',
                'current_semester' => 2,

                'academic_status' => 'Aktif',
                'entry_year' => 2018,
                'gpa' => 3.25

            ]

        ];
    }

    public function index(): string
    {
        $parser = \Config\Services::parser();
        // $db = db_connect();
        // $db->initialize();
        // if ($db->connID) {
        //     echo "Connected to Database, ";
        //     print_r($db->getDatabase());
        //     echo '<br />';
        // }
        //$students = $this->mhs_model->getAllStudents();
        // $temp = array();
        // foreach ($students as $row) {
        //     array_push($temp, $row->toArray());
        // }

        $students = $this->modelStudent->findAll();

        $params = new DataParams([
            'search' => $this->request->getGet('search'),
            // 'filters' => [
            //     'semester' => $this->request->getGet('semester')
            // ],
            'study_program' => $this->request->getGet('study_program'),
            'academic_status'   => $this->request->getGet('academic_status'),
            'entry_year'   => $this->request->getGet('entry_year'),
            'sort' => $this->request->getGet('sort'),
            'order' => $this->request->getGet('order'),
            'page' => $this->request->getGet('page_courses'),
            'perPage' => $this->request->getGet('perPage')
        ]);
        $result = $this->modelStudent->getFilteredStudents($params);

        // $students_courses = $db
        //     ->query('SELECT students.id, courses.name FROM courses 
        //         JOIN enrollments ON enrollments.course_id = courses.id 
        //         JOIN students ON enrollments.student_id = students.id
        //     GROUP BY students.id,')
        //     ->getResult('array');



        $dataArray = [
            'students' => $result['students']
        ];

        $data = [
            //'title' => 'Manajemen Users',
            'students' => $result['students'],
            'pager' => $result['pager'],
            'total' => $result['total'],
            'params' => $params,
            'study_program' => $this->modelStudent->getAllStudyProgram(),
            'academic_status' => $this->modelStudent->getAllAcademicStatus(),
            'entry_year' => $this->modelStudent->getAllEntryYear(),
            'baseUrl' => base_url($this->baseUrlRoutes),
            'content' => $parser->setData($dataArray)->render('components/student_list')
        ];
        //, ['cache' => 1800, 'cache_name' => 'student_list']
        return view('students/v_mahasiswa', $data);
    }

    public function detail($id): string
    {
        //$studentsObject = $this->mhs_model->getStudentByNIM($nim);
        //$students = $studentsObject->toArray();
        $parser = \Config\Services::parser();
        $student = $this->modelStudent->find($id);
        $gradesTable = $this->db->table('student_grades');
        $gradesTable
            ->select('*')
            ->join('enrollments', 'student_grades.enrollment_id = enrollments.id')
            ->join('courses', 'enrollments.course_id = courses.id')
            ->where('enrollments.student_id', $id);
        $grades = $gradesTable->get()->getResult('array');
        $studentArray = $student->toArray();
        // $students_grades = $db
        //     ->query('SELECT * FROM courses 
        //         JOIN enrollments ON enrollments.course_id = courses.id 
        //         JOIN student_grades ON enrollments.id = student_grades.enrollment_id
        //     ')
        //     ->getResult('array');

        $studentArray['academic_status_cell'] = view_cell('AcademicStatusCell', ['status' => $student->academic_status]);
        //, DAY, 'cache_academic_status'

        $studentArray['latest_grades_cell'] = view_cell('LatestGradesCell', ['dataCourses' => $grades]);
        //, HOUR * 6, 'cache_grades_cell'

        $studentArray['title_profile'] = 'Detail Mahasiswa';

        $data['content'] = $parser->setData($studentArray)->render('components/student_profile');
        return view('students/v_mahasiswa_detail', $data);
    }

    public function detailProfile()
    {
        $id = user_id();
        $parser = \Config\Services::parser();
        $student = $this->modelStudent->where('user_id', $id)->first();

        $gradesTable = $this->db->table('student_grades');
        $gradesTable
            ->select('*')
            ->join('enrollments', 'student_grades.enrollment_id = enrollments.id')
            ->join('courses', 'enrollments.course_id = courses.id')
            ->where('enrollments.student_id', $id);
        $grades = $gradesTable->get()->getResult('array');

        $studentArray = $student->toArray();

        $studentArray['academic_status_cell'] = view_cell('AcademicStatusCell', ['status' => $student->academic_status]);
        //, DAY, 'cache_academic_status'

        $studentArray['latest_grades_cell'] = view_cell('LatestGradesCell', ['dataCourses' => $grades]);
        //, HOUR * 6, 'cache_grades_cell'

        $studentArray['title_profile'] = 'My Profile';

        $data['content'] = $parser->setData($studentArray)->render('components/student_profile');
        $data['diploma_path'] = $studentArray['diploma_path'];
        return view('students/v_mahasiswa_detail', $data);
    }

    public function uploadDiploma()
    {

        return view('students/v_mahasiswa_upload');
    }

    public function create(): string
    {
        $new = new Student();
        return view('students/v_mahasiswa_form', ['type' => 'Create', 'action' => 'save_add', 'mahasiswa' => $new]);
    }

    public function update($id): string
    {
        $data = $this->modelStudent->find($id);
        return view('students/v_mahasiswa_form', ['type' => 'Update', 'action' => 'save_update', 'mahasiswa' => $data]);
    }

    public function save_add()
    {
        $data = new Student($this->request->getPost());
        $data->id = null;
        $validationRules = $this->modelStudent->getValidationRules();
        $validationMessages = $this->modelStudent->getValidationMessages();
        $validationRules['student_id'] = 'required|is_unique[students.student_id]';

        if (!$this->validate($validationRules, $validationMessages)) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        $store = $this->modelStudent->save($data);

        if (!$store) {
            // Output any error (like if save failed)
            dd($this->modelStudent->errors());
        }

        session()->setFlashdata('success', 'Student berhasil disimpan');
        return redirect()->to($this->baseUrlRoutes);
    }

    public function save_update()
    {
        $id = $this->request->getPost('id');
        $validationRules = $this->modelStudent->getValidationRules();
        $validationMessages = $this->modelStudent->getValidationMessages();
        $validationRules['student_id'] = 'required|is_unique[students.student_id,id,' . $id . ']';

        if (!$this->validate($validationRules, $validationMessages)) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }
        $data = new Student($this->request->getPost());

        $store = $this->modelStudent->save($data);
        if ($store) {
            session()->setFlashdata('success', 'Student berhasil diubah');
        }

        return redirect()->to($this->baseUrlRoutes);
    }


    public function delete($id)
    {
        //$this->mhs_model->deleteMahasiswa($nim);
        $this->modelStudent->delete($id);
        return redirect()->to($this->baseUrlRoutes);
    }

    public function studentsbyprogramForm()
    {
        $study_programs = $this->modelStudent->getAllStudyProgram();
        $entry_years = $this->modelStudent->getAllEntryYear();
        $data = [

            'title' => 'Student Report by Student Program',

            'study_programs' => $study_programs,

            'entry_years' => $entry_years

        ];

        return view('report/students', $data);
    }

    private function filterData($studyProgram = '', $entryYear = '')
    {
        return $this->modelStudent->getStudentByProgramData($studyProgram, $entryYear);
    }

    public function studentsbyprogramPdf()
    {
        $studyProgram = $this->request->getVar('study_program');
        $entryYear = $this->request->getVar('entry_year');
        $result = $this->filterData($studyProgram, $entryYear);
        // Generate PDF
        $pdf = $this->initTcpdf();
        $this->generatePdfHtmlContent($pdf, $result, $studyProgram, $entryYear);
        // Output PDF
        $filename = 'laporan_mahasiswa_' . date('Y-m-d') . '.pdf';
        $pdf->Output($filename, 'I');
        exit;
    }

    private function initTcpdf()

    {

        //$image_file = base_url('images/logo.png');
        $image_file = 'logo.png';
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetCreator('CodeIgniter 4');

        $pdf->SetAuthor('Administrator');

        $pdf->SetTitle('Student Data Report');

        $pdf->SetSubject('Student Data Report');

        $pdf->SetHeaderData($image_file, 10, 'UNIVERSITAS XYZ', '', [0, 0, 0], [0, 64, 128]);

        $pdf->setFooterData([0, 64, 0], [0, 64, 128]);

        $pdf->setHeaderFont(['helvetica', '', 12]);

        $pdf->setFooterFont(['helvetica', '', 8]);

        $pdf->SetMargins(15, 20, 15);

        $pdf->SetHeaderMargin(5);

        $pdf->SetFooterMargin(10);

        $pdf->SetAutoPageBreak(true, 25);

        $pdf->SetFont('helvetica', '', 10);

        $pdf->AddPage();

        return $pdf;
    }

    public function generatePdfHtmlContent($pdf, $students, $studyProgram, $entryYear)
    {
        // Set title and filters info
        $title = 'LAPORAN DATA MAHASISWA';

        if (!empty($studyProgram)) {
            $title .= ' - PROGRAM STUDI: ' . $studyProgram;
        }

        if (!empty($entryYear)) {
            $title .= ' - TAHUN MASUK: ' . $entryYear;
        }

        // $pdf->SetFont('helvetica', 'B', 14);
        // $pdf->Cell(0, 10, $title, 0, 1, 'C');
        // $pdf->Ln(5);

        // // Info waktu cetak
        // $pdf->SetFont('helvetica', 'I', 8);
        // $pdf->Cell(0, 5, 'Tanggal Cetak: ' . date('d-m-Y H:i:s'), 0, 1, 'R');
        // $pdf->Ln(5);

        // Table header
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(220, 220, 220);
        $html = '<h2 style="text-align:center;">' . $title . '</h2>
        <p style="margin-top:30px; text-align:right;">           
         Total Mahasiswa: ' . count($students) . ' 
        </p>
         <table border="1" cellpadding="5" cellspacing="0" style="width:100%;">
           <thead>
           <tr style="background-color:#CCCCCC; font-weight:bold; text-align:center;">
                       <th>No</th>
                       <th>NIM</th>
                       <th>Nama</th>
                       <th>Program Studi</th>
                       <th>Semester</th>
                       <th>Status</th>
                       <th>Tahun Masuk</th>
                       <th>IPK</th>
                   </tr>
               </thead>
               <tbody>';

        $no = 1;
        foreach ($students as $student) {
            $html .= '
           <tr>
            <td style="text-align:center;">' . $no . '</td>
            <td>' . $student->student_id . '</td>
            <td>' . $student->name . '</td>
            <td>' . $student->study_program . '</td>
            <td style="text-align:center;">' . $student->current_semester . '</td>
            <td style="text-align:center;">' . $student->academic_status . '</td>
            <td style="text-align:center;">' . $student->entry_year . '</td>
            <td style="text-align:center; font-weight:bold;">' . $student->gpa . '</td>
           </tr>';
            $no++;
        }
        $html .= '
         </tbody>
     </table>
     
     ';

        // Write HTML to PDF
        $pdf->writeHTML($html, true, false, true, false, '');
    }

    private function generatePdfContent($pdf, $students, $studyProgram, $entryYear)
    {
        $title = 'LAPORAN DATA MAHASISWA';

        if (!empty($studyProgram)) {
            $title .= ' - PROGRAM STUDI: ' . $studyProgram;
        }

        if (!empty($entryYear)) {
            $title .= ' - TAHUN MASUK: ' . $entryYear;
        }

        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, $title, 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->Cell(0, 5, 'Tanggal Cetak: ' . date('d-m-Y H:i:s'), 0, 1, 'R');
        $pdf->Ln(5);

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(220, 220, 220);

        $pdf->Cell(10, 7, 'No', 1, 0, 'C', 1);
        $pdf->Cell(30, 7, 'NIM', 1, 0, 'C', 1);
        $pdf->Cell(60, 7, 'Nama Mahasiswa', 1, 0, 'C', 1);
        $pdf->Cell(50, 7, 'Program Studi', 1, 0, 'C', 1);
        $pdf->Cell(20, 7, 'Semester', 1, 0, 'C', 1);
        $pdf->Cell(30, 7, 'Status', 1, 0, 'C', 1);
        $pdf->Cell(25, 7, 'Tahun Masuk', 1, 0, 'C', 1);
        $pdf->Cell(15, 7, 'IPK', 1, 1, 'C', 1);
        // Table content
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetFillColor(255, 255, 255);

        $no = 1;
        foreach ($students as $student) {
            $pdf->Cell(10, 6, $no++, 1, 0, 'C');
            $pdf->Cell(30, 6, $student->student_id, 1, 0, 'C');
            $pdf->Cell(60, 6, $student->name, 1, 0, 'L');
            $pdf->Cell(50, 6, $student->study_program, 1, 0, 'L');
            $pdf->Cell(20, 6, $student->current_semester, 1, 0, 'C');
            $pdf->Cell(30, 6, $student->academic_status, 1, 0, 'C');
            $pdf->Cell(25, 6, $student->entry_year, 1, 0, 'C');
            $pdf->Cell(15, 6, $student->gpa, 1, 1, 'C');
        }

        // Summary
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 7, 'Total Mahasiswa: ' . count($students), 0, 1, 'L');
    }
}
