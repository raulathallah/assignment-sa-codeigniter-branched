<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Enrollment extends Entity
{
    protected $attributes = [
        'id'                => null,
        'student_id'        => null,
        'course_id'         => null,
        'academic_year'     => null,
        'semester'          => null,
        'status'            => null,
        'created_at'        => null,
        'updated_at'        => null
    ];

    protected $dates   = [
        'created_at',
        'updated_at',
        //'deleted_at'
    ];
    protected $casts = [
        'id'                => 'integer',
        'student_id'        => 'integer',
        'course_id'         => 'integer',
        'academic_year'     => 'integer',
        'semester'          => 'integer',
    ];
}
