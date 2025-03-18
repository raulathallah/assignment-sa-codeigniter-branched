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

class Mahasiswa extends BaseController
{
    private $modelStudent;
    private $modelGrades;

    private $modelUser;

    protected $db;
    private $baseUrlRoutes = '/student';

    public function __construct()
    {
        $this->db = db_connect();
        $this->db->initialize();

        $this->modelStudent = new StudentModel();
        $this->modelGrades = new StudentGradeModel();
        $this->modelUser = new UserModel();
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
        return view('students/v_mahasiswa_detail', $data);
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
}
