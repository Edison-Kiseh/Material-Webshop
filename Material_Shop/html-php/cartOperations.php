<?php
    // Include the page that contains the database connection
    include('topPage.php');

    // Check if user is logged in
    if (!isset($_SESSION['id'])) {
        header("Location: ./login.php"); // Redirect to login if not logged in
        exit();
    }

    // Fetching the cart associated with the current user
    $stmt = $conn->prepare("SELECT cartID FROM carts WHERE userID = ?");
    $stmt->bind_param("i", $_SESSION['id']);

    // Executing the statement
    if ($stmt->execute()) {
        // Binding the result variables
        $stmt->bind_result($cartID);

        //Fetch the results
        if ($stmt->fetch()) {
            //Closing statement
            $stmt->close();

            //Handling action to add product to cart
            if (isset($_GET['action']) && $_GET['action'] == "addProduct" && isset($_GET['prodID']) && isset($_GET['quantity'])) {
                $prodID = htmlspecialchars($_GET['prodID']);
                $quantity = htmlspecialchars($_GET['quantity']);

                //First checking if the same product already exists in the cart
                $stmtCheck = $conn->prepare("SELECT quantity FROM cartItems WHERE productID = ?");
                $stmtCheck->bind_param("i", $prodID);

                //executing statement
                $stmtCheck->execute();

                //binding the result to the variable
                $stmtCheck->bind_result($quantityInCart);

                //perform different actions depending on whether the product already exists or not
                if($stmtCheck->fetch()){
                    //getting the previous quantity and adding it to the curren
                    $updatedQuantity = htmlspecialchars($quantityInCart) + $quantity;

                    //closing the check statement
                    $stmtCheck->close();

                    //increasing the product quantity in the cart if the product already exists in there
                    $stmt2 = $conn->prepare("UPDATE cartItems SET quantity = ? WHERE productID = ?");
                    $stmt2->bind_param("ii", $updatedQuantity, $prodID);
                }
                else{
                    //first closing the check statement
                    $stmtCheck->close();

                    //Prepare statement to insert into cartItems
                    $stmt2 = $conn->prepare("INSERT INTO cartItems (cartID, productID, quantity) VALUES (?, ?, ?)");
                    $stmt2->bind_param("iii", $cartID, $prodID, $quantity);
                }

                //Execute the statement
                if ($stmt2->execute()) {
                    $stmt2->close();
                    header("Location: ./productDetails.php?productID=" . $prodID . "&action=displayMsg");
                } else {
                    echo "Error executing statement: " . $stmt2->error;
                }
            } else {
                header("Location: ./productDetails.php"); //Redirect if parameters are missing
                exit();
            }
        } else {
            //No cart found for the user
            $stmt->close();
            echo "No cart found for this user.";
            exit();
        }
    } else {
        echo "Error executing statement: " . $stmt->error;
        exit();
    }

    //Close the database connection
    $conn->close();
?>
