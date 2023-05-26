<style>

    @keyframes anim{{$end-$start}}s
    {
      from
      {
        height: 60px;
      }

      to
      {
        height: calc(var(--lineH) * {{$end-$start}} + (2 * var(--tam)) + 20px);
      }
    }

  .timeline-piece
  {
    display: flex;
    align-items: center;
    margin: 5px;
    transition: opacity 1.5s, height 0.5s ease-in-out;
    animation-timing-function: linear;
    opacity: 0;
    flex:none;
  }

  .line
  {
    display: flex;
    height: calc(100% - 30px);
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  .line-piece
  {
    width: 2px;
    flex: none;
    height: calc(100% - 30px);
    background: transparent;
    border-left: calc(var(--tam)/7) solid var(--line-light);
    border-right: calc(var(--tam)/7) solid var(--dark);
  }

  .circle
  {
    width: var(--tam);
    height: var(--tam);
    display: flex;
    flex: none;
    border-radius: var(--tam);
    border: 3.5px solid var(--dark);
    background: var(--line-light);
    justify-content:center;
    align-items: center;
    font-size: 7pt;
    font-family: cursive;
    color: white;
  }

  .brackets
  {
    width: 10px;
    height: calc(100% - 30px);
    margin-left: 5px;
    background: transparent;
    border: solid 3.5px var(--line-light);
    border-left: none;
    flex:none;
    display: flex;
    align-items: center;
  }

  .process
  {
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 20pt;
    font-family: cursive;
    color: gray;
    margin-left: 5px;
    margin-right: 5px;
  }

  .queue-piece
  {
    border: solid 2px var(--line-light);
    border-radius: 10px 0px 10px 10px;
    height: 100%;
    width: 20px;
    margin: 3px;
    padding-left: 70px;
    background: var(--bg-texts);
    padding-right: 70px;
    overflow-y: scroll;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .queue-element
  {
    font-size: 10pt;
    font-family: cursive;
    color: gray;
  }
</style>


<div class='timeline-piece' id="timeline-piece" style="height: calc(var(--lineH) * {{$end-$start}} + (2 * var(--tam)) + 20px);">
  <div class='queue-piece'>
    <div class="queue-element" style="margin-top:10px;">Fila</div>
      @for($i = 1; $i<=count($queue); $i++)
        <div class="queue-element">{{$i}}:{{$queue[($i-1)]}}</div>
      @endfor
      @if(count($queue)==0)
        <div class="queue-element">Vazia.</div>
      @endif
    <div class="queue-element" style="margin-top:10px;"></div>
  </div>

  <div class='line'>
    <div class='circle'>{{$start}}</div>
      <div class='line-piece'></div>
    <div class='circle'>{{$end}}</div>
  </div>
  <div class='brackets'></div>
  <div class='process'>{{$process}}</div>
</div>


<script>
initComponent();

function initComponent()
{
  setTimilinePieceId();
  showComponent({{$start}},{{$end}});
}

var currentID;
function setTimilinePieceId()
{
  if(!(currentID >= 1))
    currentID = 1;
  else
    currentID++;


  var timelinePiece = document.getElementById("timeline-piece");
  timelinePiece.setAttribute("id","timeline-piece-"+currentID);
}

var currentID2;
async function showComponent(start,end)
{

  if(document.getElementById("animationCheckbox").checked)
  {
    await sleep({{$process_animation_start}}/mult);
    if(!(currentID2 >= 1))
      currentID2 = 1;
    else
      currentID2++;
    var duracao = "{{$end-$start}}";
    duracao = (duracao/mult);
    timelinePiece = document.getElementById("timeline-piece-"+currentID2);
    pieces+=1;
    setProcessTime(start,end,timelinePiece);
    timelinePiece.style.opacity = "1";
    timelinePiece.style.animationName = "anim{{$end-$start}}s";
    timelinePiece.style.animationDuration = duracao+"s";
  }
  else
  {
    if(!(currentID2 >= 1))
      currentID2 = 1;
    else
      currentID2++;

    timelinePiece = document.getElementById("timeline-piece-"+currentID2);
    timelinePiece.style.opacity = "1";
  }

}

var pieces = 1;
function timelineScroll(start,end,height)
{
  var countPieces = document.getElementsByClassName("timeline-piece").length;
  var lineH = getComputedStyle(document.documentElement).getPropertyValue('--lineH').replace("px",'');
  var timeline = document.getElementById("timeline");
  timeline.scrollTo({
    top: start*lineH + 80*pieces,
    left: 100,
    behavior: 'smooth'
  });
}

async function setProcessTime(start,end,timelinePiece)
{
  timelinePiece.children[1].children[2].innerText = start;
  await sleep(1/mult);

  if(start!=end)
    var time = start+1;
  else
    var time = end;

  timelinePiece.children[1].children[2].innerText = time;
  timelineScroll(start,end,timelinePiece.offsetHeight);

  if(time<end)
    setProcessTime(start+1,end,timelinePiece);
}

var mult = document.getElementById('animation-speed-input').value;
function animationSpeed(timeMult)
{
  mult = timeMult;
}

function sleep(s)
{
  return new Promise(resolve => setTimeout(resolve, s*1000));
}

</script>
