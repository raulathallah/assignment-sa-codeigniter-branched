<?php

namespace App\Models;

use App\Libraries\DataParams;
use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table            = 'students';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    //protected $returnType       = 'object';
    protected $returnType       = \App\Entities\Student::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'student_id',
        'name',
        'user_id',
        'study_program',
        'current_semester',
        'academic_status',
        'entry_year',
        'gpa',
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
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        //'student_id'            => 'required|is_unique',
        'name'                  => 'required',
        'study_program'         => 'required',
        'current_semester'      => 'required|greater_than_equal_to[1]|less_than_equal_to[14]',
        'academic_status'       => 'required|in_list[active, on leave, graduated]',
        'entry_year'            => 'required',
        'gpa'                   => 'required|greater_than_equal_to[0.00]|less_than_equal_to[4.00]',
    ];
    protected $validationMessages   = [
        'student_id' => [
            'required' => 'NIM is required',
            'is_unique' => 'NIM already exist'
        ],
        'study_program' => [
            'required' => 'Study program is required',
        ],
        'current_semester' => [
            'required' => 'Semester is required',
            'greater_than_equal_to' => 'Semester must be greater or equal to 1',
            'less_than_equal_to' => 'Semester must be lesser or equal to 14',
        ],
        'academic_status' => [
            'required' => 'Academic status is required',
            'in_list' => 'Academic status must be active, on leave, or graduated'
        ],
        'entry_year' => [
            'required' => 'Entry year is required',
        ],
        'gpa' => [
            'required' => 'GPA is required',
            'greater_than_equal_to' => 'Semester must be greater or equal to 0.00',
            'less_than_equal_to' => 'Semester must be lesser or equal to 4.00',
        ],

    ];
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


    public function getFilteredStudents(DataParams $params)
    {
        if (!empty($params->search)) { // Apply search
            $this->groupStart()
                ->like('code', $params->search, 'both', null, true)
                ->orLike('name', $params->search, 'both', null, true)
                ->groupEnd();
        }

        if (!empty($params->study_program)) {
            $this->where('study_program', $params->study_program);
        }

        if (!empty($params->academic_status)) {
            $this->where('academic_status', $params->academic_status);
        }

        if (!empty($params->entry_year)) {
            $this->where('entry_year', $params->entry_year);
        }

        // Apply sort
        $allowedSortColumns = ['code', 'name'];
        $sort = in_array($params->sort, $allowedSortColumns) ? $params->sort : 'id';
        $order = ($params->order === 'desc') ? 'desc' : 'asc';
        $this->orderBy($sort, $order);

        $result = [
            'students' => $this->paginate($params->perPage, 'students', $params->page),
            'pager' => $this->pager,
            'total' => $this->countAllResults(false)
        ];
        return $result;
    }

    public function getAllStudyProgram()
    {
        $study_program = $this->select('study_program')->distinct()->findAll();
        return array_column($study_program, 'study_program');
    }

    public function getAllAcademicStatus()
    {
        //$academic_status = $this->select('academic_status')->distinct()->findAll();
        //return array_column($academic_status, 'academic_status');


        return ['active', 'on leave', 'graduated'];
    }

    public function getAllEntryYear()
    {
        $entry_year = $this->select('entry_year')->distinct()->findAll();
        return array_column($entry_year, 'entry_year');
    }
}
