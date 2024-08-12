<?php
    //including the page that contains the connection to the database
    include('topPage.php');

    if(isset($_POST['register']) || $_POST['addUser']){
        //fetching the data entered by the user in the previous page
        $firstName = htmlspecialchars($_POST['fname']);
        $lastName = htmlspecialchars($_POST['lname']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $action = htmlspecialchars($_POST['action']);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        //Preparing the statement to insert user into the database
        $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashed_password);

        //selecting email to see if it already exists in the db
        $stmt2 = $conn->prepare("SELECT userID, email FROM users WHERE email = ?");
        $stmt2->bind_param("s", $email);

        //statement to create a new shopping cart depending on the user id
        $stmt3 = $conn->prepare("INSERT INTO carts (userID) VALUES (?)");

        function addUser($stmt, $conn, $stmt3, $action) {
            //Executing the statement
            if ($stmt->execute()) {
                //Getting the ID of the newly inserted user
                $uid = $conn->insert_id;
                echo "User added successfully. User ID: " . $uid;

                //calling the create cart function
                createCart($stmt3, $uid);

                //checking whether the user is signing up themselves or it is an admin adding them
                if($action == 'signup'){
                    //starting a session and setting userID in session
                    // session_start();
                    $_SESSION['id'] = $uid;
                    $_SESSION['permissions'] = 'user';
 
                    //redirecting to homepage
                    header("Location: ./index.php");
                }
                else{
                    header("Location: ./manageUsers.php");
                }
            } else {
                echo "Error: " . $stmt->error;
            }

            //closing the connection
            $stmt->close();
        }

        //Function to first check if the user exists before adding them to the database
        function checkUser($stmt2, $stmt, $conn, $stmt3, $action) {
            $stmt2->execute();
            $stmt2->store_result();

            if ($stmt2->num_rows > 0) {
                //If the user already exists, redirect to the register page indicating that they exist
                header("Location: ./register.php?action=userExists&userAction=$action");
            } else {
                //If the user does not exist, add them to the database of course
                addUser($stmt, $conn, $stmt3, $action);
            }

            // Closing the statement
            $stmt2->close();
        }

        // Function to create a cart for every newly registered user
        function createCart($stmt3, $uid) {
            $stmt3->bind_param("i", $uid);

            if ($stmt3->execute()) {
                echo "Cart created successfully for user ID: " . $uid;
            } else {
                echo "Error creating cart: " . $stmt3->error;
            }

            // Closing the statement
            $stmt3->close();
        }

        // Check if the user exists and then add the user
        checkUser($stmt2, $stmt, $conn, $stmt3, $action);
    }

    //closing the connection
    $conn->close();
?>