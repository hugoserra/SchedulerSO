<html id="html" lang="pt">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <script src="{{ asset('js/app.js') }}"></script>
     <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
     <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>Timeline</title>
  </head>
  <body>
    <div class="main" id="main">
      <x-sidebar></x-sidebar>
      @yield($currentScreen)
    </div>
  </body>
</html>
<script>

  function nextScreen()
  {
    var nameArray = getSavedValue("input-select-ark").split("\\");
    var name = nameArray[nameArray.length -1];
    window.location.href = "{{route('report')}}?ark="+name+"&sche="+getSavedValue("scheduling");
  }
  function previousScreen()
  {
    var nameArray = getSavedValue("input-select-ark").split("\\");
    var name = nameArray[nameArray.length -1];
    window.location.href = "{{route('timeline')}}";
  }
</script>
