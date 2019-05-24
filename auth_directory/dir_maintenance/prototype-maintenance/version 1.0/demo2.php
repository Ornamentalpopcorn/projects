<?php
    /**
     * Minimal class autoloader
     *
     * @param string $class Full qualified name of the class
     */
     $path    = 'includes/model/class/src/ChrisKonnertz/StringCalc/';
     include($path . "/StringCalc.php");
     $files = scandir($path);
     // print_r($files);
     unset($files[0]);
     unset($files[1]);
     foreach ($files as $file) {

        // scandir($file);
        if (strpos($file, ".php") === FALSE) {
          // echo $file ;

          // foreach ($file as $f) {
            $sub_folder = scandir($path . "/" .$file);
            unset($sub_folder[0]);
            unset($sub_folder[1]);
            // print_r($sub);
            foreach ($sub_folder as $sf) {
                  // echo $path  . $file . "/" . $sf;
                  // echo "<br>";

                  if (strpos($sf, ".php") === FALSE) {
                      $sub_folder2 = scandir($path . "/" . $file . "/" . $sf);
                      unset($sub_folder2[0]);
                      unset($sub_folder2[1]);
                      // print_r($sub_folder2);

                      foreach ($sub_folder2 as $sf2) {

                         if (strpos($sf2, ".php") === FALSE) {
                           $sub_folder3 = scandir($path  . $file . "/" . $sf . "/" . $sf2);
                           unset($sub_folder3[0]);
                           unset($sub_folder3[1]);
                           foreach ($sub_folder3 as $sf3) {
                             // echo ($path  . $file . "/" . $sf . "/" . $sf2 . "/" . $sf3);
                             $path_list[] =($path  . $file . "/" . $sf . "/" . $sf2 . "/" . $sf3);
                             // echo "<br>";
                           }

                         } else {
                           // echo ($path  . $file . "/" . $sf . "/" . $sf2);
                           // echo ($path  . $file . "/" . $sf . "/" . $sf2);
                           $path_list[] = ($path  . $file . "/" . $sf . "/" . $sf2);
                           // echo "<br>";
                         }

                      }

                  } else {
                    // echo ($path  . $file . "/" . $sf);
                    $path_list[] = $path  . $file . "/" . $sf;
                    // echo "<br>";
                  }

            }

            // echo  "<br/>";
          // }
        }

     }
     $reverse = array_reverse($path_list);

     // echo "<pre>";
     // print_r($path_list);
     // echo "</pre>";
     // echo "<h1>REVERSE</h1>";
     // echo "<pre>";
     // print_r($reverse);
     // echo "</pre>";
     require_once("includes/model/class/src/ChrisKonnertz/StringCalc/Calculator/CalculatorInterface.php");
     require_once("includes/model/class/src/ChrisKonnertz/StringCalc/Support/UtilityTrait.php");
     foreach ($path_list as $p) {
       echo "$p <br>";
       require_once($p);
     }
     exit();


    function miniAutoloader($class)
    {
      // echo "includes/model/class/src/" . $class . '.php';
        require __DIR__ . "/includes/model/class/src/" . $class . '.php';
        // require __DIR__ . "\\includes\\model\\class\\src\\" . $class . '.php';
        // require __DIR__ . "/includes/model/class/src/" . $class . '.php';
    }
    spl_autoload_register('miniAutoloader');
    $term = isset($_POST['term']) ? $_POST['term'] : null;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>StringCalc Demo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/framy/latest/css/framy.min.css">
    <style>
        body { padding: 20px }
        h1 { margin-bottom: 40px }
        h4 { margin-top: 40px }
        form { margin-bottom: 20px }
        div.success { border: 1px solid #4ce276; padding: 10px; border-top-width: 10px }
        div.error { border: 1px solid #f36362; padding: 10px; border-top-width: 10px }
    </style>
</head>
<body>
    <h1>StringCalc Demo</h1>

    <form method="POST">

        <div class="form-element">
            <label for="term">Term:</label>
            <input id="term" class="form-field" name="term" type="text" value="<?php echo $term !== null ? $term : '1+(2+max(-3,3))' ?>">
        </div>

        <input type="submit" value="Calc" class="button">
    </form>

    <div class="block result">
        <?php
            $stringCalc = new ChrisKonnertz\StringCalc\StringCalc();
            if ($term !== null) {
                try {
                    $result = $stringCalc->calculate($term);
                    echo '<div class="success">Result: <code><b>' . $result . '</b></code> (Type: ' . gettype($result) . ')</div>';
                } catch (ChrisKonnertz\StringCalc\Exceptions\StringCalcException $exception) {
                    echo '<div class="error">'.$exception->getMessage();
                    if ($exception->getPosition()) {
                        echo ' at position <b>' . $exception->getPosition() . '</b>';
                    }
                    if ($exception->getSubject()) {
                        echo ' with subject "<b>' . $exception->getSubject() . '</b>"';
                    }
                    echo '</div>';
                } catch (Exception $exception) {
                    echo '<div class="error outside">'.$exception->getMessage().'</div>';
                }
            }
        ?>
    </div>

    <div class="block grammar">
        <?php
            $grammar = new \ChrisKonnertz\StringCalc\Grammar\StringCalcGrammar();
            echo '<h4>Grammar rules</h4><pre>'.$grammar->__toString().'</pre>';
        ?>
    </div>
</body>
</html>
