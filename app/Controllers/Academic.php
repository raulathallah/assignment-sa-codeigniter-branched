<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Course;
use App\Libraries\DataParams;
use App\Models\CourseModel;
use App\Models\M_Course;
use CodeIgniter\HTTP\ResponseInterface;

class Academic extends BaseController
{
    protected $modelCourse;

    public function __construct()
    {
        $this->modelCourse = new CourseModel();
    }

    public function index()
    {
        //$courses = $this->course_model->getAllCourses();
        //$courses = $this->modelCourse->paginate(2, 'courses');
        $parser = \Config\Services::parser();

        $params = new DataParams([
            'search' => $this->request->getGet('search'),
            // 'filters' => [
            //     'semester' => $this->request->getGet('semester')
            // ],
            'semester' => $this->request->getGet('semester'),
            'credits'   => $this->request->getGet('credits'),
            'sort' => $this->request->getGet('sort'),
            'order' => $this->request->getGet('order'),
            'page' => $this->request->getGet('page_courses'),
            'perPage' => $this->request->getGet('perPage')
        ]);
        $result = $this->modelCourse->getFilteredCourses($params);


        // $table->addRow(['ID', 'Code', 'Name', 'Credits', 'Semester', 'Created At', 'Updated At']);
        // foreach ($courses as $row) {
        //     $table->addRow($row->toArray());
        // }

        // $template = [
        //     'table_open' => '<table border="1" cellpadding="12" cellspacing="1">',
        // ];

        // $table->setTemplate($template);
        // $data = $table->generate();
        $dataArray = [
            'courses' => $result['courses'],
        ];

        $data = [
            //'title' => 'Manajemen Users',
            'courses' => $result['courses'],
            'pager' => $result['pager'],
            'total' => $result['total'],
            'params' => $params,
            'semester' => $this->modelCourse->getAllSemester(),
            'credits' => $this->modelCourse->getAllCredits(),
            'baseUrl' => base_url('course'),
            'content' => $parser->setData($dataArray)->render('components/course_list')
        ];
        //, ['cache' => DAY, 'cache_name' => 'cache_course_list']
        return view('academic/v_course', $data);

        // return view('academic/v_course', ['courses' => $dataArray], ['cache' => DAY, 'cache_name' => 'cache_course_list']);
    }

    public function getCourseDetail($id)
    {
        $course = $this->modelCourse->find($id);
        $courseArray = $course->toArray();
        $parser = \Config\Services::parser();

        $data['content'] = $parser->setData($courseArray)->render('components/course_detail');
        return view('academic/v_course_detail', $data);
    }

    public function updateCourse($id): string
    {
        $data = $this->modelCourse->find($id);
        return view('academic/v_course_form', ['type' => 'Update', 'action' => 'save_update', 'course' => $data]);
    }

    public function createCourse(): string
    {
        $new = new Course();
        return view('academic/v_course_form', ['type' => 'Create', 'action' => 'save_add', 'course' => $new]);
    }

    public function course_save_add()
    {
        //$this->mhs_model->addStudent($newData);
        $data = new Course($this->request->getPost());
        $data->id = null;

        //save to db
        if ($this->modelCourse->save($data)) {

            session()->setFlashdata('success', 'Course berhasil disimpan');

            return redirect()->to('/course');
        }

        return redirect()->back()
            ->with('errors', $this->modelCourse->errors())
            ->withInput();
    }

    public function course_save_update()
    {
        $data = new Course($this->request->getPost());

        if ($this->modelCourse->save($data)) {

            session()->setFlashdata('success', 'Course berhasil diubah');

            return redirect()->to('/course');
        }

        return redirect()->back()
            ->with('errors', $this->modelCourse->errors())
            ->withInput();
    }

    public function deleteCourse($id)
    {
        $this->modelCourse->delete($id);
        return redirect()->to('/course');
    }

    public function getAcademicStatistic()
    {
        $data = $this->modelCourse->countAllResults();
        //$data = count($courses);

        return view('academic/v_academic_statistic', ['count' => $data], ['cache' => MINUTE * 0.5, 'cache_name' => 'cache_academic_statistic']);
    }
}
