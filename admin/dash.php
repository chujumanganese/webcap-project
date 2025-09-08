<?php
    require_once "../scripts/function.php";
    require_once "./function_escalated.php";

    if(isset($_SESSION["admin"])){
        $name = $_SESSION["admin"];
    }else
    { header("location: ../one/login.php"); exit("Login to gain access");}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin </title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="./dash.css">
    <link rel="stylesheet" href="../css/media.css">
</head>
<body>
    <aside>
        <div class="user-info">
            <h2>Hello, <?php echo "$name"; ?></h2><!-- this part of the code need review -->
            <?php $image_side = profile_images($_SESSION["admin"]);
                foreach($image_side as $w):
                    extract($w);
            ?>
                <img src=<?= "../uploads/$profile_image" ?> alt="" width="60px" height="60px">
            <?php endforeach; ?>
        </div><br><br><br>
        
       <div class="dropdown">
            <p>Settings</p>
       </div>
       <ul>
            <li>
                <img src="../svg new icons/user-search.svg" alt="" width="20px" height="20px">
                <a href="profile.html">Admin Profile</a>
            </li>
            <li>
                <img src="../svg new icons/plus-square.svg" alt=""  width="20px" height="20px">
                <a href="./dash.php#changepasswordsnow">Change password</a></li>
            <li>
                <img src="../svg new icons/dashboard-alt.svg" alt="" width="20px" height="20px">
                <a href="#usertable">view users</a>
            </li>
            <li title="Help users with login issues">
                <img src="../svg new icons/dashboard-alt.svg" alt="" width="20px" height="20px">
                <a href="#userhelplogin">Users Login</a>
            </li>
            <li>
                <img src="../svg new icons/arrow-from-right-stroke.svg" alt="" width="20px" height="20px">
                <a href="../one/logout.php">Logout</a>
            </li>
       </ul>
    </aside>
    <section>
       <nav>
            <div class="Harmburger">
                <div class="b"></div>
                <div class="b"></div>
                <div class="b"></div>
            </div>
            <div class="right">
                <img src="../svg new icons/heart.svg" alt="" width="20px" height="20px">
                <img src="../svg new icons/plus-square.svg" alt="" width="20px" height="20px">
                <img src="../svg new icons/mail-open.svg" alt="" width="20px" height="20px">
            </div>
       </nav>
       <br />
       <div class="uses">
            <h1>Dashboard</h1>
            <div class="directory">Home / Dashboard</div>
       </div><br>
       <div class="others">
           <div class="parent">
                <div class="box" style="--a:  #005c99;">
                <h2>Total Posts</h2>
                <p class="rt">
                    <?php $r = count_admin_postes() ?? [];
                       foreach($r as $t) {
                           echo $t["counted"];
                       };
                    ?>
                </p>
                <div class="base">
                    <p>More Info</p>
                    <img src="../svg new icons/arrow-out-up-square-half.svg" alt="" width="20px" height="20px">
                </div>
                </div>
                <div class="box" style="--a: #664200;">
                    <h2>Total News</h2>
                    <p class="rt">
                        <?php
                            $ol = get_all_specific("news") ?? [];
                            foreach($ol as $t){
                                echo $t["counted"];
                            }
                        ?>
                    </p>
                    <div class="base">
                        <p>More Info</p>
                        <img src="../svg new icons/arrow-out-up-square-half.svg" alt="" width="20px" height="20px">
                    </div>
                </div>
           </div>
           <div class="parent">
                <div class="box" style="--a:#996300;">
                <h2>Total Tech</h2>
                <p class="rt">
                    <?php
                        $ol = get_all_specific("tech") ?? [];
                        foreach($ol as $t){
                            echo $t["counted"];
                        }
                    ?>
                </p>
                <div class="base">
                    <p>More Info</p>
                    <img src="../svg new icons/arrow-out-up-square-half.svg" alt="" width="20px" height="20px">
                </div>
                </div>
                <div class="box" style="--a:  #666600;">
                    <h1>Others</h1>
                    <p class="rt">
                       <!-- Javascript code required -->
                    </p>
                    <div class="base">
                        <p>More Info</p>
                        <img src="../svg new icons/arrow-out-up-square-half.svg" alt="" width="20px" height="20px">
                    </div>
                </div>
           </div>
       </div><br>
       <div class="filesystem">
            <div class="text">
                <p>All Blog Photos</p>
                <h2>+</h2>
            </div>
            <div class="down">
                <?php 
                    $dir = "../uploads";
                    $files = get_images() ?? [];

                    foreach($files as $file){
                        if($file === "." || $file === ".."){
                            continue;
                        }
                        echo <<< L
                            <img src="$dir/$file" width="100px" height="100px"/>
                        L;
                    }
                ?>
            </div>
       </div>
       <div class="body">
            <div class="t1">
                <h1 id="usertable">All Blogs</h1><br>
                <div class="table-wrapper">
                    <table >
                        <tr>
                            <th>Blog Title</th>
                            <th>Date Published</th>
                            <th>Category</th>
                            <th>Likes</th>
                            <th>Username</th>
                            <th>Action</th>
                        </tr>
                        <?php $reply = Get_all() ?? [];
                            foreach ($reply as $v) {
                                extract($v);
                        ?>
                            <tr>
                                <td>
                                    <div class="tblu">
                                        <img src=<?= "../uploads/$imagepath" ?> alt="" width="100px">
                                        <div class="fo">
                                            <h3><?= $blogtitle ?></h3><br />
                                            <p><?= short_text($blogdescription, 10) . "..." ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p><?= $dates ; ?></p>
                                </td>
                                <td>
                                    <p><?= $blogcategory ?></p>
                                </td>
                                <td><?= $likes; ?></td>
                                <td><?= $username ;?></td>
                                <td>
                                    <div class="actioner">
                                        <img src="../svg new icons/mail-open.svg" alt="" width="20px" height="20px">
                                        <img src="../icons/trash.svg" alt=""  width="20px" height="20px">
                                    </div>
                                </td>
                            </tr>
                        <?php }; ?>
                    </table>
                </div><br>
            </div><br><br>

            <div class="t1" id="userhelplogin">
                <h1 id="usertable">User Credentials and help</h1><br>
                <div class="table-wrapper">
                    <table >
                        <tr>
                            <th>profile picture</th>
                            <th>Username</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                        <?php $reply = userlogin_admin() ?? [];
                            foreach ($reply as $v) {
                                extract($v);
                        ?>
                            <tr>
                                <td>
                                    <div class="tblu">
                                        <img src=<?= "../uploads/$profile_image" ?> alt="" width="40px" height="40px">
                                    </div>
                                </td>
                                <td>
                                    <p><?= $username ; ?></p>
                                </td>
                                <td>
                                    <p><?= $firstname; ?></p>
                                </td>
                                <td><?= $lastname; ?></td>
                                <td><?= $email; ?></td>
                                <td>
                                    <div class="actioner">
                                        <img src="../svg new icons/mail-open.svg" alt="" width="20px" height="20px">
                                        <img src="../icons/trash.svg" alt=""  width="20px" height="20px">
                                    </div>
                                </td>
                            </tr>
                        <?php }; ?>
                    </table>
                </div><br>
            </div><br><br>

            <!-- This part of the page is to post a vlog to the website  -->
            <div class="t2" id="changepasswordsnow">
                <h1>Update credentials</h1><br>
                <?php passwd_profile("old", "new"); ?>
                <form action="" method="POST" id="postblogs">
                    <div>
                        <input name="old" type="text" placeholder="Old password ....." maxlength="100">
                    </div><br>
                    <div>
                        <input name="new" type="text" placeholder="New password ....." maxlength="100">
                    </div><br>
                    <button name="btnposts">Post Blog</button>
                </form>
            </div>
       </div>
    </section>

    <script>
        /* This part of the code is to hold the user location on the screen before reload */
        const e = document.querySelector("section");
        window.addEventListener("beforeunload", sert);
        window.addEventListener("load", ser);

        function sert(){
            localStorage.setItem("locations", e.scrollTop);
        }

        function ser(){
            let positions = localStorage.getItem("locations");

            if(positions != null){
               setTimeout(() => {
                    e.scrollTop = parseInt(positions);
               }, 0);
            }
        }

        /* This part of the code is to make the error button disappear */
        const er_box = document.querySelector(".errorflash");

        setTimeout(() => {
            er_box.style.display = "none";
        }, 4000);

        //this part of the code is going to reset the form whenever it is submitted to the server 
        const form = document.querySelector("#postblogs");

        form.addEventListener("submit", submitteds);

    </script>
</body>
</html>