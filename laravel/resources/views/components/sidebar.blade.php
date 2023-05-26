<style>
  .menu-lateral
  {
    width: 190px !important;
    height: 90% !important;
    padding: 10px !important;
    border: var(--line-light) solid 3px;
    border-radius: 10px;
    background: var(--bg-texts);
    margin-left: 10px !important;
    display: flex;
    flex-direction: column;
    align-items: center;
    z-index: 10;
    flex:none;
    position: relative;
  }

  .menu-icon
  {
    height: 35px !important;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 3px;
  }

  .marca
  {
    opacity: 0.2;
    transition: all 0.5s;
    margin-top: 8px;
  }

  .marca:hover
  {
    opacity: 1;
  }
</style>

<div class="menu-lateral legends">
  Configurações
  <br><br>
  <button class="menu-icon" onclick="timelineToTop();">Voltar Ao Topo</button>
  <div class="menu-icon">
    <button class="menu-icon" id='previous' onclick="previousScreen()" style="margin-right:2px;"><</button>
    <button class="menu-icon" id="next" onclick="nextScreen()" style="margin-left:2px;">></button>
  </div>
  <div class="menu-icon col" id="scale">
    <label>Escala</label>
    <input class="menu-icon" id="scale-input" type="range" min="1" max="30" value="10" onchange="scale(this.value); saveValue(this);" style="height:10px !important;">
  </div>
  <div class="menu-icon col" id="animation-speed">
    <label>Velocidade Animação</label>
    <input class="menu-icon" id="animation-speed-input" type="range" min="1" max="30" value="7.5" onchange="animationSpeed(this.value); saveValue(this);" style="height:10px !important;">
  </div>
  <div class="menu-icon" id="animation-div">
    <label style="margin-bottom:3.6px;">Animação:</label>
    <input type="checkbox" id="animationCheckbox" onchange="saveValue(this);">
  </div>

  <div class="marca" style="border:solid gray 2px; padding:10px; border-radius:5px; text-align:center;">
      <a href="{{asset('Testes.zip')}}" style="text-decoration-line:none; color:gray;">Download Planilhas de Teste</a>
  </div>

  <div class="marca" style="position:absolute; bottom:20px;">
    <label style="border:solid gray 2px; padding:10px; border-radius:5px;">
      <a href="https://www.instagram.com/hugoserrap/" style="text-decoration-line:none; color:gray;">by @hugoserrap</a>
    </label>
  </div>
</div>

<script>

document.getElementById("animation-speed-input").value = getSavedValue("animation-speed-input");
document.getElementById("scale-input").value = getSavedValue("scale-input");
document.getElementById("animationCheckbox").checked = getSavedValue("animationCheckbox");

</script>
