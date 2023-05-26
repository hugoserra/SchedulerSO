<?php

namespace App\Http\Controllers;
use App\Http\Controllers\FCFSController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class SchedulingController extends Controller
{


    public function SelectScheduling(Request $request)
    {
      if(!file_exists(Storage::path("Arquivos\\".$_FILES['ark']['name'])))
      {
        Storage::delete(Storage::allFiles("Arquivos"));
        move_uploaded_file($_FILES['ark']['tmp_name'],Storage::path("Arquivos\\".$_FILES['ark']['name']));
      }elseif($_FILES['ark']['size'] > 1000)
      {
        move_uploaded_file($_FILES['ark']['tmp_name'],Storage::path("Arquivos\\".$_FILES['ark']['name']));
      }
      $selecteScheduling = $request->input('scheduling');
      $pyCommand = "python " . Storage::path("python/Schedulings.py") . " " . Storage::path("Arquivos\\".$_FILES['ark']['name']) . " " . $selecteScheduling;
      // dd($pyCommand);
      $output = exec($pyCommand);

      $processes  = $this->PreparingProcesses(json_decode($output,true));
      // dd($output);

      if($processes==null)
      {
        if($output=="")
          $output = "Ocorreu um erro! <br><br>Por favor selecione novamente o arquivo escolhido, e execute o simulador.";
        return $this->ShowProcessesOnTimelinePiece(["MSG"=>$output]);
      }
      return $this->ShowProcessesOnTimelinePiece($processes);
    }

    public function ShowProcessesOnTimelinePiece($processes)
    {
      return view('screens',['currentScreen' => 'timeline',
                             'processes'     => $processes]);
    }

    public function PreparingProcesses($python_processes)
    {
      if($python_processes==null)
        return null;

      $final_process = [];

      $previous_process_end = false;
      $previous_process_start = false;
      $process_animation_start = 0;

      foreach ($python_processes as $key => $process)
      {
        if($key!="report")
        {
          $process["previous_process_end"]   = $previous_process_end;
          $process["previous_process_start"] = $previous_process_start;
          $process["process_animation_start"] = $process_animation_start;

          $previous_process_end     = $process["END"];
          $previous_process_start   = $process["START"];
          $process_animation_start += $process["END"]-$process["START"];
          array_push($final_process,$process);
        }
      }

      // dd($final_process);
      return $final_process;
    }
}
