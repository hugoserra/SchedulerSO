<style>
  .report-main
  {
    width: 100%;
    height: 92%;
    display: flex;
    align-items: center;
    flex-direction: column;
    overflow-y: scroll;
  }

  td,th
  {
    border:var(--dark) solid 2px;
  }
</style>

<div class="transition-top"></div>
<div class="report-main legends anim" id="report-main">
  <h3>Relatório De Escalonamento.</h3>
  <div id="ark-name" >Arquivo:</div>
  <div id="scheduling-name">Escalonador:</div>
  <br>
  <div class="row" style="display:flex; align-items:flex-start;">
    <table class="legends">
      <thead>
        <tr>
          <th>Processos</th>
          <th>Tempo De Espera</th>
          <th>Tempo De Resposta</th>
          <th>Tempo De Chegada</th>
          <th>Tempo Requirido</th>
        </tr>
      </thead>
      <tbody>
        @php
          $TME = 0;
          $TMR = 0;
        @endphp

        @if(!$error)
          @if(isset($processes['report']))
          @foreach($processes['report'] as $process)
            <tr>
              <td>{{$process["NAME"]}}</td>
              <td>{{$process["TDE"]}}</td>
              <td>{{$process["TDR"]}}</td>
              <td>{{$process["TDC"]}}</td>
              <td>{{$process["TR"]}}</td>
            </tr>
              @php
                $TME += $process["TDE"];
                $TMR += $process["TDR"];
              @endphp
            @endforeach

            @php
              if(count($processes['report'])>0)
              {
                $TMR = round($TMR/count($processes['report']),2);
                $TME = round($TME/count($processes['report']),2);
              }
            @endphp
          @else
          @foreach($processes as $process)
            <tr>
              <td>{{$process["NAME"]}}</td>
              <td>{{$process["TDE"]}}</td>
              <td>{{$process["TDR"]}}</td>
              <td>{{$process["TDC"]}}</td>
              <td>{{$process["TR"]}}</td>
            </tr>
              @php
                $TME += $process["TDE"];
                $TMR += $process["TDR"];
              @endphp
            @endforeach

            @php
              if(count($processes)>0)
              {
                $TMR = round($TMR/count($processes),2);
                $TME = round($TME/count($processes),2);
              }
            @endphp
          @endif
        @endif

      </tbody>
    </table>
    <table class="legends ml-2">
      <thead>
        <tr>
          <th>TMR</th>
          <th>TME</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>{{$TMR}}</td>
          <td>{{$TME}}</td>
        </tr>
      </tbody>
    </table>
  </div>
  @if($error)
    <div class="warning-msg col">
      <h3>Warning!!</h3>
      <div style="text-align: center;">
        <?php echo ($error);?>
      </div>
    </div>
  @endif
  <br>
</div>
<div class="transition-bottom"></div>

<script>

document.getElementById("ark-name").innerText = "Arquivo: "+getFileName();
document.getElementById("scheduling-name").innerText = "Escalonador: "+getSavedValue("scheduling");

</script>
