<?php
$handle = fopen("../json/goodreads_book_genres_initial.json", "r");
if (!$handle){
    echo "can't open file";
    return;
}
$i = 0;
$genresArray = [];
while (($line = fgets($handle)) !== false && $i<100){
    $contents = json_decode($line,true);
    foreach ($contents["genres"] as $key=>$val){
        array_push($genresArray,$key);
    }
    $i++;
}
fclose($handle);
$genresArrayFromDB = [];
require_once "../DB/insert.php";
foreach (array_unique($genresArray) as $gen){
    $genresArrayFromDB[insertGenres($gen)] = $gen;
}
closeDB();
echo json_encode($genresArrayFromDB, JSON_UNESCAPED_UNICODE);