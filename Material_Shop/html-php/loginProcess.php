<?php
    //including the page that contains the connection to the database
    include('topPage.php');

    //verifying if information sent from the login page has been received
    if(isset($_POST['login']) && !empty($_POST['password']) && !empty($_POST['email'])){
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        //Preparing the SQL statement
        $stmt = $conn->prepare("SELECT userID, password, permissions FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        if ($stmt === false) {
            die('Prepare() failed: ' . htmlspecialchars($conn->error));
        }

        //Executing the statement
        $stmt->execute();

        //Binding the result variables
        $stmt->bind_result($uid, $existingPassword, $perms);

        //fetch the results
        $stmt->fetch();
        
        //if the users exists
        if($uid){
            //comparing passwords to see if they match
            $password_match = password_verify($password, $existingPassword);

            //if the passwords are the same
            if($password_match){
                $_SESSION['id'] = $uid;
                $_SESSION['permissions'] = $perms;
                
                header("Location: ./index.php?action=loggedIn");
            }
            else{
                header("Location: ./login.php?action=incorrectPass");
            }
        }

        else{
            header("Location: ./login.php?action=not_found");
        }
    }
    else{
        header("Location: ./index.php");
    }

    $conn->close();
    $stmt->close();
?>