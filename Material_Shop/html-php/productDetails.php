<?php 
    //including the page that contains the connection to the database
    include('topPage.php');

    //checking if a product id was sent from the previous page
    if(isset($_GET['productID'])){
        $productID = $_GET['productID'];
    }else{
        header("Location: ./index.php");
    }

    //Preparing the SQL statement
    $stmt = $conn->prepare("SELECT productID, name, price, description, image FROM products WHERE productID = ?");
    $stmt->bind_param("i", $productID);
    if ($stmt === false) {
        die('Prepare() failed: ' . htmlspecialchars($conn->error));
    }

    //Executing the statement
    $stmt->execute();

    //Binding the result variables
    $stmt->bind_result($id, $name, $price, $description, $image);

    //fetch the results
    $stmt->fetch();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Product Details</title>
        <link href="../css/reset.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="../css/productDetails.css" rel="stylesheet" />
        <link href="../css/style.css" rel="stylesheet" />
        <!-- <script src="../js/jquery.js"></script> -->
    </head>

    <body>
        <!-- Including navigation bar -->
        <?php
            include('navigationBar.php');

            if (isset($_GET['action']) && $_GET['action'] == "displayMsg") {
                echo "<script>alert('Item has been added to cart');</script>";
            }
        ?>

        <div class="container-trui">
            <div class="container details">
                <div class="col-6"><?php echo "<img src=\"$image\" alt=\"material\"/>" ?></div>

                <div class="col-6">
                    <?php
                        echo "<h1> " . htmlspecialchars($name) . " </h1>";
                        echo "<h3> $" . htmlspecialchars($price) . " </h3><hr/>";

                    ?>
                    <p>
                        <!--The item can be added to the cart depending on whether a user is logged in or not-->
                        <?php
                            //adding items to the cart or not depending where the user is properly logged in
                            if(isset($_SESSION['id'])){
                                echo "<form action=\"cartOperations.php\" method=\"GET\">
                                    <input type=\"hidden\" name=\"action\" value=\"addProduct\">
                                    <input type=\"hidden\" name=\"prodID\" value=\"$id\">
                                    <div><label for=\"quantityInput\">Quantity</label>
                                    <select name=\"quantity\" id=\"quantityInput\" style=\"width: 60px\">";
                                        for ($i = 1; $i <= 10; $i++) {
                                            echo "<option value=\"$i\">$i</option>";
                                        }
                                echo "</select></div><hr/>
                                    <button type=\"submit\" class=\"btn btn-primary add\">Add to cart</button>
                                </form>";
                            }
                            else{
                                echo "<div><label for=\"quantityInput\">Quantity: </label>
                                <select name=\"quantity\" id=\"quantityInput\" style=\"width: 60px\">";
                                    for ($i = 1; $i <= 10; $i++) {
                                        echo "<option value=\"$i\">$i</option>";
                                    }
                                echo "</select></div><hr/>
                                <a href=\"../html-php/login.php\"><button class=\"btn btn-primary add\">Add to cart</button></a>";
                            }
                        ?>
                    </p>
                    <?php
                        echo "<hr/><h5>About this product</h5>";
                        echo "<p> " . htmlspecialchars($description) . " </p>";
                    ?>
                </div>
            </div>
        </div>

          <footer class="bg-body-tertiary text-center text-lg-start">
            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
              © 2024 Copyright:
              <span>Material shop</span>
            </div>
            <!-- Copyright -->
          </footer>
    </body>
</html>