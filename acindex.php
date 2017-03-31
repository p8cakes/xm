<html>
<head>
  <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

  <script>
  $(document).ready(function(){
    $('#xmHead').focus();
  });

  // Autocomplete goes here
  $(document).ready(function() {
    $('#xmHead').autocomplete({
      source: "getheads.php"
    });
  });
  </script>
</head>

<body>
   <form>
      <label for="xmHead">Head:</label>
      <input id="xmHead" name="xmHead" />
   </form>
</body>
</html>