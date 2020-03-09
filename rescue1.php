<?php

$servername = "";
$username = "";
$password = "";
$dbname = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM wp_posts";
$result = $conn->query($sql);
$postID=0;
$images="";
$content="";
    if ($result->num_rows > 0) {
    // output data of each row
        while($row = $result->fetch_assoc()) {
                if($row["post_type"]=="portfolio_page"){
                    $images="<div class='potfolio_images'>";
                   $content="<div class='potfolio_content'>".$row["post_content"]."<br>";
                    if (! strpos($content, "[php snippet=2]") ) $content= $content."[php snippet=2]";
                    $postID=$row["ID"];
                    //search child image
                    $result1 = $conn->query($sql);
                    if ($result1->num_rows > 0) {
                        while($row1 = $result1->fetch_assoc()) {
                            if($row1["post_mime_type"]=="image/jpeg" && $row1["post_parent"]==$postID) $images= $images."<img src='".$row1["guid"]."'>";
                        }
                    }
                   $images=$images."</div>";
                   $content=$images.$content;."</div>";
                   $sql1="UPDATE wp_posts SET post_content='".str_replace("'", '\"',$content) ."' WHERE ID=".$row["ID"];
                     $result1=$conn->query($sql1);
                     if($result1->num_rows > 0) echo done;
//                    $images="";
                   if($postID==7828){
                        echo $sql1;
                        $result1=$conn->query($sql1);
                        echo $result1;
                   }
                   $content="";
                }
        }
    } else {
    echo "0 results";
    }
$conn->close();
?>
