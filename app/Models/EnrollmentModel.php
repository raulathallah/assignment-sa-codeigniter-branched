<?php

namespace App\Models;

use App\Libraries\DataParams;
use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table            = 'enrollments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    //protected $returnType       = 'array';
    protected $returnType       = \App\Entities\Enrollment::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'student_id',
        'course_id',
        'academic_year',
        'semester',
        'status',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    //protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getFilteredEnrollments(DataParams $params)
    {
        $enrollmentsData = $this
            ->select('
                enrollments.id as id,
                students.name as studentName,
                courses.name as courseName,
                enrollments.status,
                enrollments.academic_year,
                enrollments.semester
            ')
            ->join('students', 'enrollments.student_id = students.id')
            ->join('courses', 'enrollments.course_id = courses.id');

        if (!in_groups('admin')) {
            $enrollmentsData->where('students.user_id', user()->id);
        }

        if (!empty($params->search)) { // Apply search
            $enrollmentsData->groupStart()
                ->like('students.name', $params->search, 'both', null, true)
                ->orLike('courses.name', $params->search, 'both', null, true)
                ->groupEnd();
        }

        // Apply sort
        // $allowedSortColumns = ['code', 'name'];
        // $sort = in_array($params->sort, $allowedSortColumns) ? $params->sort : 'id';
        // $order = ($params->order === 'desc') ? 'desc' : 'asc';
        // $this->orderBy($sort, $order);

        $result = [
            'enrollments' => $enrollmentsData->paginate($params->perPage, 'enrollments', $params->page),
            'pager' => $enrollmentsData->pager,
            'total' => $enrollmentsData->countAllResults(false)
        ];
        return $result;
    }


    public function getEnrollmentReportData($params = '')
    {
        $result = $this->select(
            'students.name, 
            courses.code as course_code,
            courses.name as course_name, 
            enrollments.semester, 
            enrollments.academic_year, 
            courses.credits, 
            students.student_id as student_id,
            students.study_program,
            enrollments.status'
        )
            ->join('courses', 'courses.id = enrollments.course_id')
            ->join('students', 'students.id = enrollments.student_id');

        // ->groupStart()
        //     ->like('student_id', $params->search, 'both', null, true)
        //     ->orLike('name', $params->search, 'both', null, true)
        //     ->groupEnd()


        if (isset($params)) {
            $result->groupStart()
                ->like('students.student_id', $params, 'both', null, true)
                ->orLike('students.name', $params, 'both', null, true)
                ->groupEnd();
        }


        return $result->findAll();
    }
}
