<?php
$conn = new mysqli("localhost","root","","book_reader")
or dir("Can't connect to db");

function closeDB(){
    global $conn;
    mysqli_close($conn);
}