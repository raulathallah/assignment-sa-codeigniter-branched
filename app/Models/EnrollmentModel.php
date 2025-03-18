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
}
