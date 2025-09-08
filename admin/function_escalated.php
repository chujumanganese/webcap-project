<?php 

// start the session, when you close the browser, the session is gone

function count_admin_postes(){
    global $conn;
    $sql = $conn->query("SELECT COUNT(*) as counted from posts");

    if($sql){
        return $sql;
    }
}

function get_all_specific($cat){
    global $conn;
    $sql = $conn->query("SELECT COUNT(*) as counted from posts where `blogcategory` = '$cat'");

    if($sql){
        return $sql;
    }
}

//we want to see all the images in filesystem
function get_images(){
    $dir = "../uploads";

    if(is_dir($dir)){
        $files = scandir($dir);
        return $files;
    }else{

    }
}

function Get_all(){
    global $conn;
    $sql = $conn->query("SELECT * from posts");

    if($sql){
        return $sql;
    }
}

function passwd_profile($old, $new){ //This function is to update the user profile 
    global $conn;
    if(!isset($_POST["btnposts"])){
        return;
    }

    if(trim($_POST[$old]) == "" || trim($_POST[$new]) == ""){
        echo "Please fill in the fields";
        return;
    }

    $old_P = htmlspecialchars($_POST[$old]);
    $new_P = htmlspecialchars($_POST[$new]);

    $sql = $conn->query("UPDATE users SET `passwords` = '$new_P' WHERE `passwords` = '$old_P' and `class_of_user` = 'admin'");

    if($conn->affected_rows > 0){
        errormessage("Password changed successfully", false);
        unset($_POST[$new]);
        return;
    }else{
        errormessage("Update Failed",true);
        return ;
    }
}

//to get all user the user login information
function userlogin_admin(){
    global $conn;
    $sql = $conn->query("SELECT * from users");

    if($sql){
        return $sql;
    }
}