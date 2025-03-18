<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Student;
use App\Models\StudentModel;
use CodeIgniter\HTTP\ResponseInterface;
use Myth\Auth\Controllers\AuthController;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Models\UserModel;

class Auth extends AuthController
{
    protected $auth;
    protected $config;
    protected $userModel;
    protected $groupModel;
    protected $studentModel;
    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
        $this->studentModel = new StudentModel();

        $this->auth = service('authentication');
    }


    public function index()
    {
        //
    }


    public function attemptLogin()
    {
        $result = parent::attemptLogin();
        return $this->redirectBasedOnRole();
    }

    public function attemptRegister()
    {
        // Jalankan registrasi bawaan
        $store = parent::attemptRegister();
        $email = $this->request->getPost('email');

        //$roleGroup = $this->request->getPost('role_group');
        $roleGroup = 'student';

        $user = $this->userModel->where('email', $email)->first();

        $new = [
            'student_id'        => $this->request->getPost('student_id'),
            'user_id'           => $user->id,
            'name'              => $this->request->getPost('name'),
            'study_program'     => $this->request->getPost('study_program'),
            'current_semester'  => $this->request->getPost('current_semester'),
            'academic_status'   => $this->request->getPost('academic_status'),
            'entry_year'        => $this->request->getPost('entry_year'),
            'gpa'               => $this->request->getPost('gpa'),
        ];

        $data = new Student($new);

        $validationRules = $this->studentModel->getValidationRules();
        $validationMessages = $this->studentModel->getValidationMessages();
        $validationRules['student_id'] = 'required|is_unique[students.student_id]';

        if (!$this->validate($validationRules, $validationMessages)) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        $this->studentModel->save($data);

        if ($user) {
            // Tambahkan ke group student
            $studentGroup = $this->groupModel->where('name', $roleGroup)->first();
            if ($studentGroup) {
                $this->groupModel->addUserToGroup($user->id, $studentGroup->id);
            }
        }
        return redirect()->route('login')->with('message', lang('Auth.registerSuccess'));
    }

    private function redirectBasedOnRole()
    {
        $userId = user_id();

        if ($userId == null) {
            return redirect()->to('/login');
        }

        $userGroups = $this->groupModel->getGroupsForUser($userId);

        foreach ($userGroups as $group) {
            if ($group['name'] === 'admin') {
                return redirect()->to('dashboard/admin');
            } else if ($group['name'] === 'lecturer') {
                return redirect()->to('dashboard/lecturer');
            } else if ($group['name'] === 'student') {
                return redirect()->to('dashboard/student');
            }
        }

        return redirect()->to('/');
    }
}
