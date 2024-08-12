<?php
   //including the page that contains the connection to the database
   include('topPage.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Sign up</title>
        <link href="../css/reset.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="../css/style.css" rel="stylesheet" />
        <link href="../css/shippingInfo.css" rel="stylesheet" />
        <!-- <script src="../js/jquery.js"></script> -->
        <script>
            function reEnterPassword() {
                var pass1 = document.getElementById('pass1');
                var pass2 = document.getElementById('pass2');
                pass1.value = ""; // Clear the password field
                pass2.value = ""; // Clear the re-enter password field
                pass1.placeholder = "Both passwords do not correspond";
                pass2.placeholder = "Both passwords do not correspond";
            }

            function validateForm() {
                var firstname = document.getElementById('fname').value;
                var lastname = document.getElementById('lname').value;
                var email = document.getElementById('email').value;
                var password1 = document.getElementById('pass1').value;
                var password2 = document.getElementById('pass2').value;

                var isValid = true;

                if (firstname === "") {
                    document.getElementById("fname").placeholder = "This field is required!";
                    isValid = false;
                } else {
                    document.getElementById("fname").placeholder = "";
                }

                if (lastname === "") {
                    document.getElementById("lname").placeholder = "This field is required!";
                    isValid = false;
                } else {
                    document.getElementById("lname").placeholder = "";
                }

                if (email === "") {
                    document.getElementById("email").placeholder = "This field is required!";
                    isValid = false;
                } else {
                    document.getElementById("email").placeholder = "";
                }

                if (password1 === "") {
                    document.getElementById("pass1").placeholder = "This field is required!";
                    isValid = false;
                } else {
                    document.getElementById("pass1").placeholder = "";
                }

                if (password2 === "") {
                    document.getElementById("pass2").placeholder = "This field is required!";
                    isValid = false;
                } else {
                    document.getElementById("pass2").placeholder = "";
                }

                if (isValid && password1 !== password2) {
                    reEnterPassword();
                    return false;
                }

                return isValid;
            }
        </script>

    </head>

    <body>
        <!-- Including navigation bar -->
        <?php
            include('navigationBar.php');
            
            if(isset($_GET['userAction']) && $_GET['userAction'] == 'add'){
                $action = 'add';
                $account = "";
                $buttonSubmit = "<input type=\"submit\" name=\"addUser\" value=\"Add user\" class=\"add\"/>";
            }
            else{
                $action = 'signup';
                $account = "<p>Already have an account? <a href=\"../html-php/login.php\">Login</a> instead.</p>";
                $buttonSubmit = "<input type=\"submit\" name=\"register\" value=\"Register\" class=\"register\"/>";
            }
        ?>

        <h2><?php echo $action == 'add' ? "Add user" : "Sign up"; ?></h2>

        <form autocomplete="off" action="../html-php/registration.php" method="post" onsubmit="return validateForm()">
            <!--message to display depending on if the user already exists in the database-->
            <?php
                if(isset($_GET['action'])){
                    if($_GET['action'] == "userExists"){
                        echo "<p class=\"message\" style=\"color: red\">Users already exists</p>";
                    }
                }
            ?>
            
            <input type="hidden" name="action" value="<?php echo $action; ?>">
            <div class="form-group">
                <label for="fname">First name:</label>
                <input type="text" class="form-control" id="fname" name="fname">
            </div>
            <div class="form-group">
                <label for="lname">Last name:</label>
                <input type="text" class="form-control" id="lname" name="lname">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="pass1">Password:</label>
                <input type="password" class="form-control" id="pass1" name="password">
            </div>
            <div class="form-group">
                <label for="pass2">Confirm Password:</label>
                <input type="password" class="form-control" id="pass2" name="password2">
            </div>


            <?php
                echo $account;
                echo $buttonSubmit;
            ?>
        </form>
    </body>
</html>
