<?php
  //including the page that contains the connection to the database
  include('topPage.php');

  if(isset($_GET['address'])){
    $address = htmlspecialchars($_GET['address']);
  }

  
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
        <link href="../css/orderConfirmed.css" rel="stylesheet" />
        <link href="../css/style.css" rel="stylesheet" />
        <!-- <script src="../js/jquery.js"></script> -->

    </head>

    <body>
        <div class="container-trui parent">
            <h1 style="text-align: center;">Thank you for your order</h1>
            <?php echo "<p>You order has been dispatched and will be shipped to the below address shortly!</p><hr/>"; 
                echo "<p><b>$address</b></p>"?>
            <div class="container">
                <a href="../html-php/orders.php"><button class="btn btn-primary">View orders</button></a>
                <a href="../html-php/index.php"><button class="btn btn-primary">Continue shopping</button></a>
            </div>
        </div>
    </body>
</html>