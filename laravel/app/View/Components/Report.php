<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Report extends Component
{
    public $processes;
    public $error = false;

    public function __construct($processes)
    {
        $this->processes = $processes;
        if(isset($processes["MSG"]))
        {
          $this->error = $processes["MSG"];
          $processes = [];
        }
    }

    public function render()
    {
        return view('components.report');
    }
}
