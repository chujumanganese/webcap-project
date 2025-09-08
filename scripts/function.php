<style>
    .offlines{
        color: red;
        text-align: center;
        margin: 1em;
    }
</style>

<?php 

ob_start();
//we don't want the session to continue after the browser has been closed
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain'=>'',
    'secure'=>false,
    'httponly'=> true,
    'samesite' => 'Lax'
]);

// start the session, when you close the browser, the session is gone
session_start();

try {

    /* We say that this connection is for users that are not logged in; */
    $conn = new mysqli("Localhost", "blogsite", "webcapz", "rizu");

    /* The escalated Login */
    $upgrade_conn = new mysqli("Localhost", "root", "767990", "rizu");

} catch (\Throwable $th) {
    exit("<h1 class='offlines'>Server not available at the moment. </h1>");
}

//We check if we have a problem with the database 
if(!$conn){
    errormessage("The sever refused to connnect to database", true);
}

function errormessage(string $message, bool $f){
    $answer = $f ? "error-modal" : "success";

    echo <<< TEXT
        <div class="errorflash" id=$answer>
           <p>$message</p>
       </div><br>
    TEXT;

    return;
}

function POSTVLOG(string $title, string $description, string $category, $btn, $image){
    global $conn;

    if(!isset($_SESSION["user"])){
        return;
    }else{
        $user_name_1 = $_SESSION["user"];
    }

    if(!isset($_POST[$btn])){
        return;
    }

    //we do some input validation 
    $titles = htmlspecialchars($_POST[$title]);
    $descriptions = htmlspecialchars($_POST[$description]);
    $categories = htmlspecialchars($_POST[$category]);

    //check if the title and description is empty
    if(trim($titles) == "" || trim($descriptions == "")){
        errormessage("Please fill in the empty fields", true);
        return;
    }

    if(strlen($titles) > 100){
        errormessage("Title is too long", true);
        return;
    }

    //check if the select item is in the array 
    $rray = ["news", "tech", "sports", "fashion", "science"] ;
    $select = in_array($categories, $rray);

    if(!$select){
        errormessage("Invalid category", true);
        return;
    }

    //process the file -- if false will not proceed;
    $file_pth = "";

    if(!valid_file($_FILES[$image], $file_pth)){
        return;
    }

    //if all is set, we now send the details into the database 
    $sql = <<< ST
        INSERT IGNORE INTO `posts`(`blogtitle`, `blogdescription`, `blogcategory`, `imagepath`, `username`) VALUES ('$titles','$descriptions','$categories','$file_pth', '$user_name_1')
    ST;

    $result = $conn->query($sql);

    if($result){
        unset($_SESSION["randoms"]);
        unset($_POST[$title]);
        unset($_POST[$description]);
        errormessage("Blog post successfull", false);
        return;
    }
}

function valid_file($file,& $file_pth) :bool{
 
    if(!$file["name"] || !$file["tmp_name"]){
        errormessage("Please choose a file", true);
        return false;
    }

    $name = $file["name"];
    $size = $file["size"];
    $temporary_name = $file["tmp_name"];
    $ext = pathinfo($name, PATHINFO_EXTENSION);
    $target_dir = "../uploads/";
    $allowed_mimes = ["image/png", "image/jpeg", "image/jpg"];
    $validmime = in_array(mime_content_type($temporary_name), $allowed_mimes);

    //check if the file name is set 
    if($name == "" && $temporary_name == ""){
        errormessage("No file choosen, please choose a file", true);
        return false;
    }

    //check if the file size is equal to the one specified 1mb
    if($size > 1000000){
        errormessage("The file size must be 1mb or less", true);
        return false;
    }

    //check the mime type of the file
    if(!$validmime){
        errormessage("This is not a valid file", true);
        return false; 
    }

    //check if the file is correct
    if($ext != "jpeg" && $ext != "png" && $ext != "jpg"){
        errormessage("The file type is not valid", true);
        return false;
    }

    // we now assume that all the file checks have been done then we send the file to the uploads directory
    $newfile = uniqid("postimage"); // we now note that the image name will be 13 characters + postimage
    $push_dir = $target_dir . $newfile . '.' . $ext;

    if(move_uploaded_file($temporary_name, $push_dir)){
        $file_pth = $newfile . '.' . $ext;
        return true;
    }else{
        errormessage("The post was not successfull", true);
        return false;
    }
    return true;
}

function login(string $emails, string $passwords, string $remember , string $btn){
    global $conn;

    if(!isset($_POST[$btn])){
        return;
    }

    //Sanitize the user input at this point 
    $email = htmlspecialchars($_POST[$emails]);
    $password = htmlspecialchars($_POST[$passwords]);

    //Do some server checks
    if(trim($email) == ""){
        errormessage("Please enter email", true);
        return;
    }

    if(trim($password) == ""){
        errormessage("Please enter password", true);
        return;
    }

    $sql_ad = "SELECT * FROM `users` WHERE email = '$email' and passwords = '$password' and `class_of_user` = 'admin'";
    $admi = $conn->query($sql_ad);

    //check if it is admin
    if(mysqli_num_rows($admi) > 0){
        $console = $admi->fetch_assoc();
        $_SESSION["admin"] = $console["username"];
        header("location: ../admin/dash.php");
        exit;
    }

    //check if the remember me button is checked 
    function remember(){
        $token = bin2hex(random_bytes(32));

        
    }

    $sqls = "SELECT * FROM `users` WHERE email = '$email' and passwords = '$password' ";
    $ref = $conn->query($sqls);
    
    if(mysqli_num_rows($ref) > 0){
        $console = $ref->fetch_assoc();
        $_SESSION["user"] = $console["username"];
        header("location: ./dashboard.php");
        exit;
    }else{
        errormessage("Invalid User", true);
        return;
    }
}

function register(string $uname, string $fname, string $lname, $email, $pword1, string $pword2, $btn){
    global $conn; 

    if(!isset($_POST[$btn])){
        return;
    }

    //sanitize the users input to the program
    $username = htmlspecialchars($_POST[$uname]);
    $firstname = htmlspecialchars($_POST[$fname]);
    $lastname = htmlspecialchars($_POST[$lname]);
    $email = htmlspecialchars($_POST[$email]);
    $password_one = htmlspecialchars($_POST[$pword1]);
    $password_two = htmlspecialchars($_POST[$pword2]);

    if(trim($firstname) == "" || trim($lastname) == "" || trim($email) == ""){
        errormessage("fill in the empty fields", true);
        return;
    }

    $sql = "select `username` from users where username = '$username'";
    $r = $conn->query($sql);

    if(mysqli_num_rows($r) > 0){
        errormessage("Username already exist", true);
        return;
    }

    //Check if the both passwords match in the boxes
    if($password_one !== $password_two){
        errormessage("Passwords do not match", true);
        return;
    }

    //insert them into the database 
    $stmt = "INSERT INTO `users`(`username`, `firstname`, `lastname`, `email`, `passwords`) VALUES ('$username','$firstname','$lastname','$email','$password_two')";

    $re = $conn->query($stmt);

    if($re){
        errormessage("You have successfully Registered to the website", false);
    }else{
        errormessage("Not registered try again please", true);
    }
}

function FetchBlogs($offset, $limit){
    global $conn;

    $sql = "SELECT * FROM `posts` ORDER BY id DESC LIMIT $limit OFFSET $offset";
    $r = $conn->query($sql);
    
    return $r;
}

// FetchBlogs(1, 4);

function fetchsinglepage(){
    global $conn;
    if(!isset($_GET["cat"])){
        echo "There are no categories";
    }

    $cat = htmlspecialchars($_GET["cat"]);

    try{
        $result = $conn->query("SELECT * FROM posts WHERE `id` = $cat");
    }catch (\Throwable $th){
        echo "No results";
        return;
    };

    if($result){
        return $result;
    }else{
        echo "There are no categories";
        return;
    }
}

function gettrendingarticles(){
    global $conn;

    $trend = $conn->query("SELECT * FROM posts ORDER BY likes DESC ");
    if($trend){
        return $trend;
    }
}

function short_text($sentence, $num = 15){
    $r = str_word_count($sentence, 1);

    $sl = array_slice($r, 0, $num);

    return implode(" ", $sl);
}

function profileimage(){
    global $conn;
    if(!isset($_SESSION['user'])){
        return "sign in";
    }

    $user = $_SESSION['user'];
    $r = $conn->query("SELECT `profile_image` FROM users where username = '$user' ");

    if($r){
       foreach($r as $t){
            return $t["profile_image"];
       }
    }
}

function getfooterblogs(){
    global $conn;
    $sql = "SELECT * FROM `posts`  order by id desc limit 3";
    $result = $conn->query($sql);

    if($result){
        return $result;
    }
}

//This part of the code is for the like button
if(isset($_GET["likes"]) && isset($_GET["id_like"])){
    like($_GET["id_like"]);
}

function like($ida){
    global $conn; 

    $sql = "UPDATE posts set `likes` = `likes` + 1 WHERE `id` = '$ida'";
    $result = $conn->query($sql);

    if($result){
        echo true;
    }else{
        echo false;
    }
}

//This function is to fetch personnal blogs to a specific user 
function get_admin_blogs(){
    global $conn;

    if(!isset($_SESSION["user"])){
        return;
    }

    $user_post = $_SESSION["user"];
    $sql = "SELECT * FROM posts WHERE `username` = '$user_post'";

    $c = $conn->query($sql);

    if($c){
        return $c;
    }
}

function get_ad_blogs(){
    global $conn;

    if(!isset($_SESSION["user"])){
        return;
    }

    $user_post = $_SESSION["user"];
    $sql = "SELECT COUNT(*) as blogs FROM posts WHERE `username` = '$user_post'";

    $c = $conn->query($sql);

    if($c){
        return $c;
    }
}

function count_news($component){
    global $conn;

    if(!isset($_SESSION["user"])){
        return;
    }
    $user_post = $_SESSION["user"];
    $sql = "SELECT COUNT(*) as blogs FROM posts WHERE `blogcategory` = '$component' and `username` = '$user_post'";

    $c = $conn->query($sql);

    if($c){
        return $c;
    }
}
//the search functionality of the nvabar to the database 
if(isset($_GET["nav_input"])){
    navbar_search();
}

function navbar_search(){
    global $conn; 
    $value = htmlspecialchars($_GET["nav_input"]);
    $r = $conn->query("SELECT * FROM posts");

    if($r){
        foreach($r as $f){
            echo $f["blogtitle"];
        }
    }
}

function edit_posts($title, $cat, $desc, $btn, $filed, $id){
    if(!isset($_GET["post"]) || !isset($_POST[$btn])){
        return;
    }

    $titl = htmlspecialchars($_POST[$title]);
    $description = htmlspecialchars($_POST[$desc]);
    $category = htmlspecialchars($_POST[$cat]);

    //check if the files are empty
    if(empty($titl) || empty(trim($category)) || empty(trim($description))){
        errormessage("Fill in the fields", true);
    }

    //check if the select item is in the array 
    $rray = ["news", "tech", "sports", "fashion", "science"] ;
    $select = in_array($category, $rray);

    if(!$select){
        errormessage("Invalid category", true);
        return;
    }

    //verify the file to be updated
    /* What will happen is that we are going to create a variable to access the file name 
        and then check whether it is true or false, then to save space we are going to replace a file.
    */
    $file_pth = "";

    if($_FILES[$filed]["name"]){
        if(!valid_file($_FILES[$filed], $file_pth)){
            return;
        };
    }

    global $conn;
    if($file_pth != ""){
        $sql = "UPDATE posts SET `blogtitle` = '$titl', `blogdescription` = '$description', `blogcategory` = '$category', `imagepath` = '$file_pth' where id='$id'";
        $result = $conn->query($sql);
    }else{
        $sql = "UPDATE posts SET `blogtitle` = '$titl', `blogdescription` = '$description', `blogcategory`='$category' where id='$id'";
        $result = $conn->query($sql);
    }

    if($result){
        $_SESSION["messages"] = "Post updated successfully";
        header("Location: ./dashboard.php");
    }

}

function other_categories(){
    global $conn;
    $user = $_SESSION["user"];
    $sql = $conn->query("SELECT COUNT(*) as l FROM posts where `blogcategory` != 'news' and `blogcategory` != 'tech' and `username`='$user'");

    if($sql){
        foreach($sql as $others){
            return $others["l"];
        }
    }
}