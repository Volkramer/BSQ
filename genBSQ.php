<?php
if ($argc != 4) {
  echo "program x y density \n";
}

$x = $argv[1];
$y = $argv[2];
$density = $argv[3];
$plateau = $y."\n";

for ($i=0; $i < $y; $i++) {
  for ($j=0; $j < $x; $j++) {
    if (rand(0, $y)*2 < $density){
      $plateau .= "o";
    }
    else {
      $plateau .= ".";
    }
  }
  $plateau .= "\n";
}

$handle = fopen("plateau.txt", "w");
fwrite($handle, $plateau);
fclose($handle);

 ?>
