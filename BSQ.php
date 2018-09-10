<?php

main($argv);

function main($arg){
  $timestart=microtime(true);
  check_arg($arg);
  check_file($arg[1]);
  $result = [];
  $strtab = open($arg[1]);
  $tab = string_to_array($strtab);
  check_size($tab);
  $size = intval(get_size($tab));
  check_size_of_tab($size, $tab);
  line_to_array($tab);
  check_line_size($tab, $size);
  check_line_content($tab);
  $result = bsq($tab, $size);
  response($tab, $result);
  $timeend=microtime(true);
  $time=$timeend-$timestart;
  $page_load_time = number_format($time, 3);
  echo "Debut du script: ".date("H:i:s", $timestart)."\n";
  echo "Fin du script: ".date("H:i:s", $timeend)."\n";
  echo "Script execute en " . $page_load_time . " sec\n";
}

function check_arg($arg){
  if (count($arg) != 2){
    echo "BAD ARGUMENT\n must be :\n php BSQ.php ******.txt\n";
    exit;
  }
  else {
    return 1;
  }
}

function check_file($file){
  if (!is_file($file)){
    echo "ARGUMENT IS NOT A FILE OR DOESN'T EXIST\n";
    exit;
  }
  else if (!preg_match('#^.*\.(txt)$#', $file)) {
    echo "FILE IS NOT A .TXT\n";
    exit;
  }
  else {
    return 1;
  }
}

function check_size($array){
  if (!preg_match('#[0-9]+#', $array[0])){
    echo "FIRST LINE IN THE FILE MUST BE A NUMBER\n";
    exit;
  }
  else {
    return 1;
  }
}

function check_size_of_tab($size, $array){
  if($size != (count($array)-1)) {
    echo "THE BOARD MUST HAVE THE SAME SIZE AS THE SIZE COUNTER (FIRST NUMBER IN THE TXT FILE)\n";
    exit;
  }
  else{
    return 1;
  }
}

function check_line_size($array, $size){
  foreach ($array as $line) {
    for ($i=0; $i < $size-1; $i++) {
      if (count($array[$i]) != count($array[$i+1])){
        echo "EACH LINE OF THE BOARD MUST HAVE THE SAME SIZE\n";
        exit;
      }
    }
  }
}

function check_line_content($array){
  foreach ($array as $key => $line) {
    if ($key == count($array)-1){
      continue;
    }
    foreach ($line as $value) {
      if(!preg_match('#\.#', $value) && !preg_match('#o#', $value)){
        echo "BOARD MUST CONTAIN ONLY . OR o\n";
        exit;
      }
    }
  }
}

function open($file){
  $handle = fopen($file, "r");
  return fread($handle, filesize($file));
}

function string_to_array($string){
  $array = explode("\n", $string);
  return $array;
}

function line_to_array(&$array){
  foreach ($array as &$value) {
    $value = str_split($value);
  }
}

function get_size(&$array){
  $var=$array[0];
  array_shift($array);
  return $var;
}

function bsq($tab, $size){
  $result = [];
  $bsqr_size = 1;
  foreach ($tab as $y => $line) {
    $tab_x = count($line);
    $tab_y = $size;
    foreach ($line as $x => $value) {
      echo "x= ".$x." / y= ".$y." BSQ=".$bsqr_size."\n";
      if($value == "o"){
        continue;
      }
      for ($sq_size=$bsqr_size; ($sq_size + $y) < $tab_y && ($sq_size + $x) < $tab_x; $sq_size++) {
        for ($sq_y=0; $sq_y <= $sq_size; $sq_y++) {
          for ($sq_x=0; $sq_x <= $sq_size; $sq_x++) {
            if ($tab[$y+$sq_y][$x+$sq_x]!=".") {
              if ($bsqr_size < $sq_size) {
                $bsqr_size = $sq_size;
                $bsqr_x = $x;
                $bsqr_y = $y;
                $result = [$bsqr_size, $bsqr_x, $bsqr_y];
              }
              break 3;
            }
          }
        }
      }
    }
  }
  return $result;
}

function response($tab, $result){
  foreach ($tab as $y => $line) {
    foreach ($line as $x => $value) {
      echo $value." ";
    }
    echo "\n";
  }
  foreach ($tab as $y => $line) {
    foreach ($line as $x => $value) {
      if ($result[1] <= $x && $x < $result[0]+$result[1] && $result[2] <= $y && $y < $result[0]+$result[2]){
        echo "x ";
      }
      else{
        echo $value." ";
      }
    }
    echo "\n";
  }
  var_dump($result);
}




?>
