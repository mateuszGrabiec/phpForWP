  //my printing post by category shortcode
  <php
  function getRowsBytype($servername,$username,$password,$dbname,$numOfCategory,$type){
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM wp_posts INNER JOIN wp_term_relationships 
  ON wp_posts.ID=wp_term_relationships.object_id AND wp_term_relationships.term_taxonomy_id=".$numOfCategory;
$result = $conn->query($sql);
$allCollection=array();
    if ($result->num_rows > 0) {
    // output data of each row
        while($row = $result->fetch_assoc()) {
                if($row["post_type"]==$type){
                   array_push($allCollection,$row);
                }
        }
    }
$conn->close();
  return $allCollection;
  }
  
  
  function getParam($row,$paramName){
      return $row[$paramName];
  }
  //
  function getURLImageToPost($servername,$username,$password,$dbname,$tablename,$type,$id){
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection 
    if ($conn->connect_error) { 
      die("Connection failed: " . $conn->connect_error); 
    }
    $sql = "SELECT ID,post_excerpt, post_status, guid, post_mime_type FROM ".$tablename." WHERE post_parent=".$id;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
    // output data of each row
        while($row = $result->fetch_assoc()) {
                if($row["post_mime_type"]==$type){
                  //if($row['post_excerpt']){
                   if($row['guid']){
                     $url=$row['guid'];
                     $conn->close();
                     return $url;
                     }
                   }else{
                   echo $row['post_excerpt'];
                   }
                //}
        }
    }
 }

function getHTMLPortfolioPages($numOfCategory){
   $servername = "";
  $username = "";
  $password = "";
  $dbname = "";
  $tablename="wp_posts";
  $type = "portfolio_page";
  $rowsByType=getRowsBytype($servername,$username,$password,$dbname,$numOfCategory,$type);
  if($rowsByType !== null){
    foreach ($rowsByType as &$row) {
      if(getParam($row,"post_content")!==""){
         $imageURL=getURLImageToPost($servername,$username,$password,$dbname,$tablename,"image/png",$row['ID']);
         if($imageURL!==null) echo "<div class='portfolio_image'><img src=\"".$imageURL."\" /></div>";
        //print_r($row);
         echo "<div class='portfolio_content'><div class='portfolio_title'>".getParam($row,"post_title")."</div>";
        echo "<p>".getParam($row,"post_content")."</p></div><br>";
        }
    }
  }
  }
  
 
 function printPortfolio_function( $atts = array() ) {
  
    // set up default parameters
    extract(shortcode_atts(array(
     'numofCategory' => '5'
    ), $atts));
    
    if($atts){
      $numofCategory=array_values($atts);
      $numofCategory=$numofCategory[0];
      getHTMLPortfolioPages($numofCategory);
      }
}

  add_shortcode('printPortfolio', 'printPortfolio_function');
?>
