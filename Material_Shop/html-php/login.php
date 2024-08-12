<?php
    //including the page that contains the connection to the database
    include('topPage.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <link href="../css/reset.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="../css/style.css" rel="stylesheet" />
        <link href="../css/shippingInfo.css" rel="stylesheet" />
        <!-- <script src="../js/jquery.js"></script> -->

        <script>
            function validateForm()//To make sure that the form has been filled
            {
                var email = document.getElementById('email').value;
                var password = document.getElementById('pass').value;

                if(email == "" || password == "")
                {
                    if(email == "")
                    {
                        document.getElementById("email").placeholder = "This field is required!"
                    }
                    if(password == "")
                    {
                        document.getElementById("pass").placeholder = "This field is required!"
                    }
                    
                return false;
                }

                return true;

            }
        </script>
    </head>

    <body>
        <!-- Including navigation bar -->
        <?php
            include('navigationBar.php');
        ?>

        <h2>Login</h2>

        <form autocomplete="off" action="../html-php/loginProcess.php" method="post" onsubmit="return validateForm()">
            <!--message to display depending on if a user is found or if there is an incorrect password-->
            <?php
                if(isset($_GET['action'])){
                    if($_GET['action'] == "not_found"){
                        echo "<p class=\"message\" style=\"color: red\">User not found</p>";
                    }
                    elseif($_GET['action'] == "incorrectPass"){
                        echo "<p class=\"message\" style=\"color: red\">Incorrect password</p>";
                    }
                }
            ?>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="pass" name="password">
            </div>

            <p>Don't yet have an account? <a href="../html-php/register.php">Sign up</a> instead.</p>

            <input type="submit" name="login" value="Submit" class="login"/>
        </form>
    </body>
</html>