<?php

namespace App\Cells;

use CodeIgniter\View\Cells\Cell;

class AcademicStatusCell extends Cell
{
    protected $status;
    protected $color;

    public function mount($status)
    {
        $this->status = $status;
    }

    public function getStatusProperty()
    {
        return $this->status;
    }

    public function getColorProperty()
    {
        if($this->status == "active"){
            $this->color = 'success';
        }else if($this->status == "on leave"){
            $this->color = 'danger';
        }else{
            $this->color = 'info';
        }

        return $this->color;
    }
}
