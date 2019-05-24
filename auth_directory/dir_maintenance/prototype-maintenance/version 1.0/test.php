<html lang="en">
<head>
  <title>Jquery select2 ajax autocomplete example code with demo</title>
  <script src="../../../dependencies/jquery/dist/jquery.min.js"></script> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/easy-autocomplete.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/jquery.easy-autocomplete.min.js"></script>
</head>
<body>


<div style="width:520px;margin:0px auto;margin-top:30px;height:500px;">
  <h2>Select Box with Search Option Jquery Select2.js</h2>
  <select class="itemName form-control" style="width:500px" name="itemName"></select>

  <input id="provider-json" />
</div>


<script type="text/javascript">

$(document).ready(function(){

    var options = {
    url: "test2.php",
    getValue: "name",
    placeholder: "Check Reference..",
    requestDelay: 250,
    list: {
      match: {
        enabled: true
      }
    }
    };

    $("#provider-json").easyAutocomplete(options);


  });

</script>


</body>
</html>
