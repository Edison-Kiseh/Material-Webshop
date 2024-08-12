<?php
  //including the page that contains the connection to the database
  include('topPage.php');

  //checking if a user is logged in before accessing this page 
  if($_SESSION['id'] == ''){
    header("Location: ./index.php");
  }
?>

<!DOCTYPE html>
<html>
  <head>
      <title>Orders</title>
      <link href="../css/reset.css" rel="stylesheet" />
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
      <link href="../css/shoppingCart.css" rel="stylesheet" />
      <link href="../css/style.css" rel="stylesheet" />
      <!-- <script src="../js/jquery.js"></script> -->
  </head>

  <body>
      <!-- Including navigation bar -->
      <?php include('navigationBar.php'); ?>

      <h1>Your orders</h1>

      <div class="container">
          <?php
            $uid = $_SESSION['id'];

            //select all orders placed by current user
            $stmt = $conn->prepare("SELECT * FROM products INNER JOIN orderItems ON products.productID = orderItems.productID INNER JOIN orders ON orders.orderID = orderItems.orderID WHERE orders.userID = ? ORDER BY orderDate DESC");
            $stmt->bind_param("i", $uid);
            $stmt->execute();

            //Getting the result
            $result = $stmt->get_result();

            //first checking if the user has items in their cart then taking necessary measures
            if ($result->num_rows == 0) {
                echo "<div class=\"emptyInfo\"><h2>You have yet to place any orders</h2></div>";
                echo "<a href=\"../html-php/index.php\" class=\"button\"><button class=\"btn btn-primary\">Continue shopping</button></a>";
            } else {
                echo '<table class="table table-bordered">
                        <thead>
                          <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Order date</th>
                          </tr>
                        </thead>
                        <tbody>';

                //variable to store the total price
                $totalPrice = 0;

                //Fetching the data
                while ($row = $result->fetch_assoc()) {
                    $productID = htmlspecialchars($row['productID']);
                    $productName = htmlspecialchars($row['name']);
                    $price = htmlspecialchars($row['price']);
                    $image = htmlspecialchars($row['image']);
                    $quantity = htmlspecialchars($row['quantity']);
                    $orderDate = htmlspecialchars($row['orderDate']);
                    $totalPrice += $quantity * $price;

                    //Displaying the fetched data
                    echo "<tr>";
                      echo "<td style=\" display: flex\"><div style=\"display: flex; align-items: center; display: inline\"><img src=\"$image\" style=\"width: 100px\" alt=\"material\"/> <span style=\"margin-left: 20px\">$productName</span></td>";
                      echo "<td><b>\$$price</b></td>";
                      echo "<td><b>$quantity</b></td>";
                      echo "<td><b>$orderDate</b></td>";
                    echo "</tr>";
                    
                }

                //Closing the statement
                $stmt->close();

                    echo " </tbody>";
                  echo "</table>";
                
                  echo "<div class=\"bottom\">";
                  echo "<h5 style=\"margin: 10px 0px 60px 0px\">Total: \$$totalPrice</h5>";
                  echo "</div>";
            }
          ?>
      </div>
  </body>
</html>
