<?php
    //including the page that contains the connection to the database
    include('topPage.php');

    //checking if a user is logged in before accessing this page 
    if($_SESSION['id'] == ''){
        header("Location: ./index.php");
    }

    $uid = $_SESSION['id'];

    //fetching user information
    $stmt = $conn->prepare("SELECT firstName, lastName, email FROM users WHERE userID = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();

    //fetching the result of the query
    $stmt->bind_result($firstName, $lastName, $email);
    $stmt->fetch();

    $stmt->close();

    //selecting the products ordered by the user
    $stmt2 = $conn->prepare("SELECT * FROM products INNER JOIN cartItems ON products.productID = cartItems.productID INNER JOIN carts ON carts.cartID = cartItems.cartID WHERE carts.userID = ?");
    $stmt2->bind_param("i", $uid);
    $stmt2->execute();

    //fetching the result of the query
    $result = $stmt2->get_result();

    $stmt2->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Orders</title>
        <link href="../css/reset.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="../css/orderSummary.css" rel="stylesheet" />
        <link href="../css/style.css" rel="stylesheet" />
        <!-- <script src="../js/jquery.js"></script> -->

    </head>

    <body>
        <h2>Proceed with order?</h2>

        <div class="container">
            <div class="personal-info">
                <h4>Personal information</h4>
                <hr/>
                <p><b>Full name:</b> <?php echo $firstName . " " . $lastName; ?></p>
                <p><b>Email:</b> <?php echo $email ?></p>
                <?php
                    if(isset($_GET['address'])){
                        $_SESSION['address'] = htmlspecialchars($_GET['address']);
                        echo "<p><b>Address:</b> " . $_SESSION['address'] . "</p>";
                    }
                ?>
            </div>

            <div class="order-info">
                <h4>Order information</h4>
                <hr/>

                <?php
                    $overallTotalPrice = 0;
                    //array that will be used to store all the fetched productIDs
                    $productIDArray = array();
                    $productArray = array();

                    while ($row = $result->fetch_assoc()) {
                        //adding the product ids to the array
                        $productIDArray[] = htmlspecialchars($row['productID']);
                        $productArray[] = $row;

                        $productName = htmlspecialchars($row['name']);
                        $price = htmlspecialchars($row['price']);
                        $quantity = htmlspecialchars($row['quantity']);

                        $totalPrice = $quantity * $price;
                        $overallTotalPrice += $totalPrice;

                        echo "<p><b>$productName:</b> \$$price X $quantity = \$$totalPrice</p>";
                    }

                    echo "<hr/>
                    <p class=\"total\"><b>Total:</b> \$$overallTotalPrice</p>"
                ?>

            </div>
        </div>

        <div class="buttons">
            <?php echo "<a href=\"../html-php/orderSummary.php?orderConfirmed=true\"><button class=\"orderConfirm btn btn-primary\">Confirm order</button></a>"; ?>
            <a href="../html-php/shoppingCart.php"><button class="btn btn-primary">Back to cart</button></a>
        </div>

        <!--Operations to perform when the confirm order button has been clicked-->
        <?php
            if(isset($_GET['orderConfirmed']) && $_GET['orderConfirmed'] == "true"){
                //Deleting items from the shopping cart
                $stmt3 = $conn->prepare("DELETE FROM cartItems WHERE productID = ?");
                foreach ($productIDArray as $productID) {
                    $stmt3->bind_param("i", $productID);
                    $stmt3->execute();
                }
                $stmt3->close();

                //Setting the order date
                $orderDate = date("Y-m-d H:i:s");

                //Calculating the total price
                $overallTotalPrice = 0;
                foreach ($productArray as $product) {
                    $price = htmlspecialchars($product['price']);
                    $quantity = htmlspecialchars($product['quantity']);
                    $overallTotalPrice += $quantity * $price;
                }

                //Adding the order to the orders table
                $stmt4 = $conn->prepare("INSERT INTO orders (userID, orderDate, total) VALUES (?, ?, ?)");
                $stmt4->bind_param("isd", $uid, $orderDate, $overallTotalPrice);
                $stmt4->execute();
                
                //Fetching the newly inserted order ID
                $orderID = $conn->insert_id;
                $stmt4->close();

                //Adding items to the orderItems table
                $stmt5 = $conn->prepare("INSERT INTO orderItems (orderID, productID, quantity, price) VALUES (?, ?, ?, ?)");
                foreach ($productArray as $product) {
                    $productID = htmlspecialchars($product['productID']);
                    $quantity = htmlspecialchars($product['quantity']);
                    $price = htmlspecialchars($product['price']);

                    $stmt5->bind_param("iiid", $orderID, $productID, $quantity, $price);
                    $stmt5->execute();
                }
                $stmt5->close();

                //Redirecting to order confirmation page
                $address = $_SESSION['address'];
                header("Location: ./orderConfirmed.php?address=$address");
            }
        ?>
    </body>
</html>