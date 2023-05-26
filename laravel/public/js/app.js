window.onload = function()
{
  anim();
  JSRules();
};

function anim()
{
  if(document.getElementById('report-main'))
    document.getElementById('report-main').style.opacity = '1';

  if(document.getElementById('timeline'))
    document.getElementById('timeline').style.opacity = '1';
}

function JSRules()
{
  if(!document.getElementById('timeline'))
  {
    document.getElementById('scale').style.display = 'none';
    document.getElementById('animation-speed').style.display = 'none';
    document.getElementById('animation-div').style.display = 'none';
  }
  else
  {
    document.documentElement.style.setProperty('--lineH', document.getElementById("scale-input").value+'px');
  }
};

function timelineToTop()
{
  var timeline = document.getElementById("timeline");
  var report   = document.getElementById("report-main");
  if(timeline!=null)
  timeline.scrollTop = 0;
  if(report!=null)
  report.scrollTop = 0;
}

function saveValue(e)
{
  var id = e.id;
  var val = e.value;
  if(e.type == "checkbox")
    var val = e.checked;
  localStorage.setItem(id, val);
}

function getSavedValue(v)
{
  if (!localStorage.getItem(v))
    return "";
  if(localStorage.getItem(v) == "false")
    return false;
  if(localStorage.getItem(v) == "true")
    return true;
  return localStorage.getItem(v);
}

function getFileName()
{
  var nameArray = getSavedValue("input-select-ark").split("\\");
  return nameArray[nameArray.length -1];
}
