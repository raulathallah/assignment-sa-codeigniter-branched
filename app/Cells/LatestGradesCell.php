<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class LatestGradesCell extends Cell
{
    protected $dataCourses;

    public function mount($dataCourses)
    {
        $this->dataCourses = $dataCourses;
    }

    public function getDataCoursesProperty()
    {
        //$table = new \CodeIgniter\View\Table();

        $dataSorted = $this->dataCourses;
        uasort($dataSorted, function ($a, $b) {
            return $a['grade_value'] > $b['grade_value'] ? -1 : 1;
        });

        $dataFiltered = array_slice($dataSorted, 0, 5);


        $dataTable = array();
        foreach ($dataFiltered as $row) {
            array_push(
                $dataTable,
                [
                    'code' => $row['code'],
                    'name' => $row['name'],
                    'grade_value' => $row['grade_value'] . ' (' . $row['grade_letter'] . ')'
                ]
            );
        }

        //$table->addRow(['Course Code', 'Course Name', 'Grades']);
        //foreach ($dataTable as $row) {
        //    $table->addRow($row);
        //}
        return $dataTable;
    }
}
