<?php function navbars() { ?>
    <?php
        //we want to check if the session is set 
        if(isset($_SESSION["user"])){
            $user = $_SESSION["user"];
        }
    ?>
    <nav>
        <div class="r">
            <h1>Blog</h1> 
        </div>
        <ul>
            <li>
                <input type="text" list="lost" placeholder="Explore blogs...." onkeyup="live()">
            </li>
            <li><a href="register.php">Sign up</a></li>
            <?php if(!isset($user)){ ?>
                <li><a href="login.php">sign in</a></li>
            <?php }else {?>
                <li class="pimg">
                    <a href="/vlog%20project1/one/dashboard.php">
                        <img src="<?= "../uploads/".profileimage(); ?>" width="27px" height="25px" title="click to update image">
                    </a>
                </li>
            <?php } ?>
        </ul>
        <datalist id="lost">
            <option value="blog">Blog</option>
        </datalist>
    </nav>
<?php } ?>

<script>
    function live(){
        const input = document.querySelector("nav ul li input");
        const output = document.querySelector("#lost option")
        
        /* call some ajax to send the data to the server */
        const xhr = new XMLHttpRequest();

        xhr.onreadystatechange = senders;

        function senders(){
            if(xhr.readyState == 4 && xhr.status == 200){
                output.textContent = xhr.responseText;
                output.value = xhr.responseText;
                console.log(xhr.responseText);
            }
        }

        xhr.open("GET", "../scripts/function.php?nav_input=55", true);
        xhr.send();
    }
</script>

<?php function footer(){ ?>
    <footer>
        <div>
            <div class="footer-first_child">
                <div class="o">
                <h1><span>B</span>log</h1><br>
                <small>This Blog post was designed by Chuju, a student of webcapz techologies, we use this blog site to say our views on matters, and publicly display them.</small>
            </div><br>
            <!-- This is the blog part -->
            <div class="o footer-second_child">
                <h3>Explore</h3>
                <ul class="footimg">
                    <?php $output = getfooterblogs(); 
                        foreach($output as $t):
                            extract($t);
                    ?>
                        <li>
                            <a href="">
                                <img src=<?= "../uploads/$imagepath" ?> alt="" width="80px" height="50px">
                                <h5><?= short_text($blogdescription, 7). "..." ; ?></h5>
                            </a>
                        </li>
                    <?php endforeach; ?> 
                </ul>
            </div><br>
            <div class="o">
                <h3>Tag Cloud</h3> <br><br>
                <ul class="ok">
                    <li><a href="">Sign in</a></li>
                    <li><a href="">Sign up</a></li>
                    <li><a href="">Lifestyle</a></li>
                    <li><a href="">Jokes</a></li>
                    <li><a href="">Sports</a></li>
                </ul>
            </div><br>
            <div class="o">
                <h3>Socials</h3>
                <ul class="socials">
                    <li>
                        <img src="../icons/facebook.svg" alt="" width="20px" height="20px">
                        <p>Facebook</p>
                    </li>
                    <li>
                        <img src="../icons/x.svg" alt=""  width="20px" height="20px">
                        <p>Twitter</p>
                    </li>
                    <li>
                    <h1>in</h1>
                        <p>Instagram</p>
                    </li>
                </ul>
            </div>
        </div>
        <hr>
    </footer>
<?php }; ?>

<?php
    ############# This part is for the administrators of the page #########
    #######################################################################

    function admin_aside($username = "", $image = ""){
        $r = profileimage() ?? [];
?>
    <div class="user-info">
        <h2>Hello, <?= "$username"; ?></h2>
        <img src=<?= "../uploads/$r" ?> alt="" width="60px" height="60px">
    </div><br><br><br>
        
    <div class="dropdown">
        <p>User</p>
    </div>
    <ul>
        <li>
            <img src="../svg new icons/user-search.svg" alt="" width="20px" height="20px">
            <a href="profile.php">Profile</a>
        </li>
        <li>
            <img src="../svg new icons/mail-open.svg" alt=""  width="20px" height="20px">
            <a href="./dashboard.php#postblogs">Post Vlog</a></li>
        <li>
            <img src="../svg new icons/lock-solid.svg" alt="" width="17px" height="17px">
            <a href="">Change password</a>
        </li>
        <li>
            <img src="../svg new icons/arrow-from-right-stroke.svg" alt="" width="20px" height="20px">
            <a href="logout.php">Logout</a>
        </li>
    </ul>
<?php }; ?>
<?php
    function admin_nav(){
?>
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
<?php }; ?>

<?php function caution(){?>
    <div class="cautionary"> <br>
        <div class="message">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Et 
        </div><br>
        <div class="footer">
            <button style="--b:red;">Accept</button>
            <button style="--b:green;">Decline</button>
        </div>
    </div>
<?php } ?>