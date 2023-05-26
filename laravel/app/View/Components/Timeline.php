<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Timeline extends Component
{
    public $processes;
    public $error = false;

    public function __construct($processes)
    {
      if(isset($processes["report"]))
        $processes["report"] = false;

      if(isset($processes["MSG"]))
        $this->error = $processes["MSG"];

      $this->processes = $processes;
    }

    public function render()
    {
      return view('components.timeline');
    }
}
