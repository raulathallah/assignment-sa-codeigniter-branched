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

class Enrollment extends BaseController
{
    private $modelEnrollment;
    private $modelCourse;
    private $modelStudent;
    protected $db;

    public function __construct()
    {
        $this->db = db_connect();
        $this->db->initialize();

        $this->modelEnrollment = new EnrollmentModel();
        $this->modelCourse = new CourseModel();
        $this->modelStudent = new StudentModel();
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
            'type' => in_groups('admin') == 1 ? 'admin' : ''
        ];

        return view('enrollments/v_enrollments', $data);
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
        $studentData = $this->modelStudent->where('user_id', user_id())->first();

        dd($studentData);
        $email = service('email');
        $email->setTo($userToEmail);
        $email->setSubject('Course enrollments');
        $data = [
            'title' => 'Course Enrollment Information',
            'name' => user()->username,
            'studentId' => $studentData->student_id,
            'courseCode' => $courseData->code,
            'courseName' => $courseData->name,
            'courseCredits' => $courseData->credits,
            'date' => new Time()
        ];
        $message = view('email', $data); // Isi konten email
        $email->setMessage($message);

        if ($email->send()) {
            //return redirect()->to('/')->with('success', 'Email berhasil dikirim');

            $data = new EntitiesEnrollment($this->request->getPost());

            $course = $this->modelCourse->find($this->request->getPost('course_id'));

            $data->id = null;
            $data->status = 'active';
            $data->semester = $course->semester;

            $store = $this->modelEnrollment->save($data);

            if (!$store) {
                // Output any error (like if save failed)
                dd($this->modelEnrollment->errors());
            }

            session()->setFlashdata('success', 'Enrollments berhasil disimpan');
            return redirect()->to('admin/enrollments');
        } else {

            $data = ['error' => $email->printDebugger()];

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
