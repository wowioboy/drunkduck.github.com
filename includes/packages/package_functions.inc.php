<?php

// Declare a package as loaded.
function package_loaded($name){
  global $PACKAGE_LIST;
  $PACKAGE_LIST[strtolower($name)] = 1;
}

// Find out if a package is loaded.
function is_package_loaded($name) {
  global $PACKAGE_LIST;
  return isset($PACKAGE_LIST[strtolower($name)]);
}

function get_loaded_packages() {
  global $PACKAGE_LIST;
  return array_keys($PACKAGE_LIST);
}
?>