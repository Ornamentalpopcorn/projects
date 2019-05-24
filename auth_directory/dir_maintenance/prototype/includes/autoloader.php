<?Php

spl_autoload_register(function ($class_name) {
    if (file_exists('includes/model/class/'.$class_name . '.php') ) {
       require_once('includes/model/class/'.$class_name . '.php');
    }
});

// function autoload($className)
spl_autoload_register(function ($class_name) {
   $className = ltrim("includes\\model\\class\\src\\" . $class_name, '\\');
   $fileName  = '';
   $namespace = '';
   if ($lastNsPos = strrpos($className, '\\')) {
       $namespace = substr($className, 0, $lastNsPos);
       $className = substr($className, $lastNsPos + 1);
       $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
   }
   $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

   require $fileName;
});
// spl_autoload_register('autoload');


?>
