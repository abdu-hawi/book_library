<?php

require_once ('db.php');

function insertAuthor($name){
    global $handle;
    if (empty($name))
        return false;

    $query = mysqli_prepare($handle, "INSERT INTO `authors` (name) VALUES (?)");
    if($query === FALSE)
        return false;
    mysqli_stmt_bind_param($query, "s",$name);

    if(!mysqli_stmt_execute($query))
        return false;
    return mysqli_stmt_insert_id($query);
}

function insertGenres($name){
    global $handle;
    if (empty($name))
        return false;

    $query = mysqli_prepare($handle, "INSERT INTO `geners` (name) VALUES (?)");
    if($query === FALSE)
        return false;
    mysqli_stmt_bind_param($query, "s",$name);

    if(!mysqli_stmt_execute($query))
        return false;
    return mysqli_stmt_insert_id($query);
}



function insertBook($link, $publisher, $num_pages, $image_url, $title, $genIDs, $authIDs){
    global $conn;

    mysqli_begin_transaction($conn);
    try {
        $query = mysqli_prepare($conn, "INSERT INTO `books` (link, publisher, num_pages, image_url, title) 
                                    VALUES (?, ?, ? , ? ,?)");
        if($query === FALSE){
            mysqli_rollback($conn);
            return false;
        }

        mysqli_stmt_bind_param($query, "ssiss",$link, $publisher, $num_pages, $image_url, $title);

        if(!mysqli_stmt_execute($query)){
            mysqli_rollback($conn);
            return false;
        }

        $id =  mysqli_stmt_insert_id($query);
        if ($authIDs > 0){
            foreach ($authIDs as $aID){
                $query = mysqli_prepare($conn, "INSERT INTO `author_book` (book_id, author_id) 
                                    VALUES (?, ?)");
                mysqli_stmt_bind_param($query, "ii",$id, $aID);
                mysqli_stmt_execute($query);
            }
        }
        if ($genIDs > 0 ){
            foreach ($genIDs as $gID){
                $query = mysqli_prepare($conn, "INSERT INTO `book_geners` (book_id, genres_id) 
                                    VALUES (?, ?)");
                mysqli_stmt_bind_param($query, "ii",$id, $gID);
                mysqli_stmt_execute($query);
            }
        }
        mysqli_commit($conn);
    }catch (Exception $exception){
        mysqli_rollback($conn);
        return false;
    }

    return true;
}