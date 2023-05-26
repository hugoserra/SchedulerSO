<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TimelinePiece extends Component
{
    public $start;
    public $process_animation_start;
    public $end;
    public $process;
    public $queue;

    public function __construct($process)
    {
        $this->start = $process['START'];
        if(isset($process["process_animation_start"]))
          $this->process_animation_start = $process["process_animation_start"];
        $this->end = $process['END'];
        $this->process = $process['NAME'];
        $this->queue = $process['QUEUE'];
    }

    public function render()
    {
        return view('components.TimelinePiece');
    }

}
