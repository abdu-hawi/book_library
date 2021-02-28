<?php

$bHandle = fopen("../json/goodreads_books.json", "r");
if (!$bHandle){
    echo "can't open file";
    return;
}


$i = 0;
$authorsStr = '{"1":"Ronald J. Fields","2":"Anita Diamant","3":"Barbara Hambly","4":"Jennifer Weiner","5":"Nigel Pennick","6":"Alfred J. Church","7":"Michael Halberstam","8":"Rachel Roberts","9":"V.L. Locey","10":"Anton Szandor LaVey","11":"Kenneth Anger","12":"Bernard Knight","13":"Carolyn Haines","14":"Benjamin Hoff","15":"Christopher Ross","16":"Tom Wolfe","17":"Don Piper","18":"Cecil Murphey","19":"Randy Alcorn","20":"Jennifer L. Holm","21":"Cynthia Rylant","22":"Margot Hovley","23":"Wayne Kinsey","24":"Francoise Sagan","25":"Stephen King","26":"Edward Herrmann","27":"Peter Maass","28":"L.J. Smith","29":"Anne Emmert","30":"Christopher Michael McDonough","31":"Richard E. Prior","32":"Mark Jackson Stansbury","33":"Mark Stansbury","34":"Zabrina Murray","35":"James Russell Lowell","36":"Lisa Mills","37":"Zhang Yun","38":"David Blake","39":"David J. Ashton","40":"John Williams","41":"Peter Cameron","42":"Stefano Tummolini","43":"mHmd`ly frwGy","44":"Carrie Ann Ryan","45":"Margaret Yorke","46":"Nikki Faye","47":"Jane Austen","48":"Lindsey Schussman","49":"Raina Palmatier","50":"Larry Shles","51":"Bradley L. Winch","52":"Isaac Asimov","53":"Scott Brick","54":"Ernest Hemingway","55":"Will Patton","56":"Kit Tunstall","57":"Kit Fawkes","58":"Esra Bahadir Cesar","59":"H. Kent Baker","60":"Victor Ricciardi","61":"Freddy Jackson Brown","62":"Duncan Gillard","63":"Steven C. Hayes","64":"Paula Rabinowitz","65":"Stacey Ballis","66":"Jenika Snow","67":"Elizabeth Guizzetti","68":"Ira Severin","69":"Sean M. Theriault","70":"Queen Rex","71":"Ian Botham","72":"Margaret Doody","73":"Rosalia Coci","74":"Beppe Benvenuto","75":"B. Batregzedmaa","76":"Mandy Stanley","77":"Robert Burleigh","78":"Florence Dupre la Tour","79":"Terence Conran","80":"Roger Dubern","81":"John McGowan","82":"Hugh Johnson","83":"Isser Woloch","84":"Gregory S. Brown","85":"ndr brhny mrnd","86":"Jeremy Black","87":"Claire Fay","88":"Arun Maira","89":"Melanie Cusick-Jones","90":"Neville Astley","91":"Praveen Gupta","92":"Dipak Jain","93":"Sally Green","94":"Maya Cross","95":"Robert Walser","96":"Daniele Pantano","97":"James Reidel","98":"Reto Sorg","99":"Agatha Christie","100":"Emilia Fox","101":"Beatrix Potter","102":"Harry Deane Willmott","103":"Joan Carney","104":"Friedrich Nietzsche","105":"drywsh ashwry","106":"Sadegh Hedayat","107":"Randall S. Davis","108":"W.J. Burley","109":"Shay Ray Stevens","110":"Elaine Szewczyk","111":"Duncan Rouleau","112":"Joe Casey","113":"Joe Kelly","114":"Steven T. Seagle","115":"Tracey Garvis-Graves","116":"Serena Lauzi","117":"M. Ageyev","118":"Ed Brubaker","119":"Spike Brown","120":"Gerald L. Gutek","121":"mHmd Th","122":"John Langan","123":"Judith Tarr","124":"Jason Delgado","125":"Chris Martin","126":"Catherine McKenzie","127":"Amy Raby","128":"Lisa Clark O\'Neill","129":"Michel Faber","130":"Leslie Gent","131":"Heather Brewer","132":"Kate Mosse","133":"Kathleen Bryan","134":"Thomas F. Monteleone"}';
$genresStr = '{"1":"history, historical fiction, biography","2":"fiction","3":"fantasy, paranormal","4":"mystery, thriller, crime","5":"poetry","6":"romance","7":"non-fiction","8":"children","9":"young-adult","10":"comics, graphic"}';
while (($line = fgets($bHandle)) !== false && $i<100){
    $contents = json_decode($line,true);
    $link =  $contents["link"];
    $company =  $contents["publisher"];
    $num_pages =  $contents["num_pages"];
    $image_url =  $contents["image_url"];
    $title =  $contents["title"];
    $authArrayID = [];
    $genresArray = getGenres($contents["book_id"]);
    $authContent = json_decode($authorsStr,true);
    foreach ($contents["authors"] as $content){
        $name =  getAuthor($content["author_id"]);
        if (FALSE !== $key = array_search($name,$authContent)){
            array_push($authArrayID,$key);
        }
    }
    $genresArrayID = [];
    foreach ($genresArray as $genArr){
        if (FALSE !== $key = array_search($genArr,$genresArray)){
            array_push($genresArrayID,$key);
        }
    }
////// insert to db
//
    require_once "../DB/insert.php";
    if (insertBook($link,$company,$num_pages,$image_url,$title,$genresArrayID,$authArrayID)){
        echo "TRUE <br>";
    }else{
        echo "FALSE<br>";
    }
    $i++;
}
closeDB();
fclose($bHandle);

function getGenres($book_id){
    $gHandle = fopen("../json/goodreads_book_genres_initial.json", "r");
    if (!$gHandle){
        echo "can't open file";
        return false;
    }
    $i = 0;
    $arr = [];
    while (($line = fgets($gHandle)) !== false && $i<100){
        $contents = json_decode($line,true);
        if ($book_id == $contents["book_id"]){
            foreach ($contents["genres"] as $key=>$val){
                array_push($arr,$key);
            }
        }
        $i++;
    }
    fclose($gHandle);
    return $arr;
}

function getAuthor($id){
    $aHandle = fopen("../json/goodreads_book_authors.json", "r");
    if (!$aHandle){
        echo "can't open file";
        return false;
    }
    while (($line = fgets($aHandle)) !== false){
        $contents = json_decode($line,true);
        if ($id == $contents["author_id"]){
            fclose($aHandle);
            return $contents["name"];
        }
    }
    return "Null";
}
