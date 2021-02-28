<?php

$jsons = ["goodreads_book_authors", "goodreads_book_genres_initial", "goodreads_book_series",
    "goodreads_book_works", "goodreads_books", "goodreads_reviews_dedup", "goodreads_reviews_spoiler",
    "goodreads_reviews_spoiler_raw"];

foreach ($jsons as $json){
    read($json);
}

function read($book){
    $handle = fopen("../json/".$book.".json", "r");
    if (!$handle){
        echo "can't open file";
        return;
    }
    $i = 0;
    echo $book."<br>";
    while (($line = fgets($handle)) !== false && $i<1){
        echo $line."<br><br>";
        $i++;
    }
    fclose($handle);
}
