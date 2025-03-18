<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Student extends Entity
{
    protected $attributes = [
        'id'                => null,
        'student_id'        => null,
        'user_id'           => null,
        'name'              => null,
        'study_program'     => null,
        'current_semester'  => null,
        'academic_status'   => null,
        'entry_year'        => null,
        'gpa'               => null,
    ];

    protected $casts = [
        'id'                => 'integer',
        'current_semester'  => 'integer',
        'entry_year'        => 'integer',
    ];

    protected $dates   = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    public function getStudentInfo()
    {
        return $this->attributes['student_id'] . ' - ' . $this->attributes['name'];
    }

    // private $id;
    // private $student_id;
    // private $name;
    // private $study_program;
    // private $current_semester;
    // private $academic_status;
    // private $entry_year;
    // private $gpa;
    // private $created_at;
    // private $updated_at;
    // private $deleted_at;

    // // public function __construct(array $data)
    // // {
    // //     $this->student_id = $data['student_id'];
    // //     $this->name = $data['name'];
    // //     $this->study_program = $data['study_program'];
    // //     $this->current_semester = $data['current_semester'];
    // //     $this->academic_status = $data['academic_status'];
    // //     $this->entry_year = $data['entry_year'];
    // //     $this->gpa = $data['gpa'];
    // // }

    // public function __get($atribute)
    // {
    //     if (property_exists($this, $atribute)) {
    //         return $this->$atribute;
    //     }
    // }

    // public function __set($atribut, $value)
    // {
    //     if (property_exists($this, $atribut)) {
    //         $this->$atribut = $value;
    //     }
    // }
}
