<style>

  input[type="file"]
  {
    opacity: 0;
    height: 1px;
    width: 1px;
  }

  .select-ark
  {
     height: 100px !important;
     margin-bottom: 10px;
     border: var(--line-light) 1.6px solid;
     border-radius: 10px;
     background-color: var(--bg-texts);
     display: flex;
     flex-direction: column;
     align-items: center;
     justify-content: center;
     cursor: pointer;
  }

  .select-ark:hover
  {
    border: var(--line-dark) 1.6px solid;
  }

  .timeline
  {
    background: transparent;
    display: flex;
    width: 100%;
    height: 92%;
    flex-direction: column;
    align-items: center;
    overflow-y: scroll;
  }

  .menu
  {
    width: 60%;
    height: 200px;
    margin: 5px;
    border: solid 3px var(--line-light);
    border-radius: 10px;
    background: var(--bg-texts);
    flex: none;
    padding: 10px;
  }

</style>
<div class="timeline legends anim" id="timeline">
  <h3>Simulação de Timeline</h3>
  <div class="transition-top"></div>
  <div class="menu">
    <form method="post" action="{{route('processes')}}" enctype="multipart/form-data" style="display:flex; flex-direction:column;">
        @csrf
        <label class="select-ark legends">
          <input type="file" id="input-select-ark" name="ark" oninput="selectedArk(); saveValue(this);" required>
          <div class="row">
            <div id="label-ark-text">Escolha o arquivo para a simulação.</div>
            <button style="display:none; margin-left:10px; height:25px; border-radius:50px; font-family: monospace;" type="button" id="deselected-ark" onclick="deselectedArk()">x</button>
          </div>
        </label>
        <select name="scheduling" id="scheduling" required onchange="saveValue(this);">
          <option value="">Selecione o Escalonador</option>
          <option value="SJF_P">SJF Preemptivo</option>
          <option value="SJF_NP">SJF Não Preemptivo</option>
          <option value="RR">Round Robin</option>
          <option value="FCFS">FCFS</option>
          <option value="Priority_P">Prioridade Estática Preemptiva</option>
          <option value="Priority_NP">Prioridade Estática Não Preemptiva</option>
        </select>
        <button type="submit">Executar Simulação</button>
    </form>
  </div>
  @if(!$error)
    @foreach($processes as $process)
      @if($process)
        <x-Timelinepiece :process=$process></x-Timelinepiece>
      @endif
    @endforeach
  @else
    <div class="warning-msg col">
      <h3>Warning!!</h3>
      <div style="text-align: center;">
        <?php echo utf8_encode($error);?>
      </div>
    </div>
  @endif

  <div class="space-height"></div>
  <div class="transition-bottom"></div>
</div>

<script>

  // console.log(getSavedValue("input-select-ark"));
  old_input_ark();
  document.getElementById("scheduling").value = getSavedValue("scheduling");
  function old_input_ark()
  {
    // Get a reference to our file input
    const fileInput = document.querySelector('input[type="file"]');

     // Create a new File object
    const myFile = new File(['Hello World!'], getSavedValue("input-select-ark"), {
         type: 'text/plain',
         lastModified: new Date(),
    });

     // Now let's create a DataTransfer to get a FileList
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(myFile);
    fileInput.files = dataTransfer.files;

    var nameArray = getSavedValue("input-select-ark").split("\\");
    var name = nameArray[nameArray.length -1];
    document.getElementById("label-ark-text").innerText = name;
    document.getElementById("deselected-ark").style.display = "block";
  }

  function scale(lineH)
  {
    document.documentElement.style.setProperty('--lineH', lineH+'px');
  }

  function selectedArk()
  {
    var nameArray = document.getElementById("input-select-ark").value.split('\\');
    var name = nameArray[nameArray.length -1];
    document.getElementById("label-ark-text").innerText = name;
    document.getElementById("deselected-ark").style.display = "block";
  }

  function deselectedArk()
  {
    document.getElementById("input-select-ark").value = '';
    document.getElementById("label-ark-text").innerText = "Escolha o arquivo para a simulação.";
    document.getElementById("deselected-ark").style.display = "none";
  }

</script>
