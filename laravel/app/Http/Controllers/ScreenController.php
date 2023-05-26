<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ScreenController extends Controller
{
  public function timeline()
  {
    $processes = [];
    return view("screens",["currentScreen"=>"timeline","processes"=>$processes]);
  }

  public function report(Request $request)
  {
    $pyCommand = "python " . Storage::path("python/Schedulings.py") . " " . Storage::path("Arquivos\\".$request['ark']) . " " . $request['sche'];
    $output = exec($pyCommand);
    $processes  = json_decode($output,true);
    if($processes==null)
      $processes["MSG"] = "Selecione o arquivo e execute a simulação para que o relatorio seja gerado!";
      
    return view("screens",["currentScreen"=>"report","processes"=>$processes]);
  }

}
