
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
  <script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
  <!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
  <script>
  $( function() {
    var availableTags = [
      "ActionScript",
      "AppleScript",
      "Asp",
      "BASIC",
      "C",
      "C++",
      "Clojure",
      "COBOL",
      "ColdFusion",
      "Erlang",
      "Fortran",
      "Groovy",
      "Haskell",
      "Java",
      "JavaScript",
      "Lisp",
      "Perl",
      "PHP",
      "Python",
      "Ruby",
      "Scala",
      "Scheme"
    ];
    $( "#tagss" ).autocomplete({
      source: availableTags
    });
  } );
  </script>

<div class="ui-widget">
  <label for="tags">Tags: </label>
  <input id="tagss">
</div>

<style media="screen">

.ui-menu .ui-widget .ui-widget-content .ui-autocomplete .ui-front {
  z-index: 9999 !important;
}

</style>
