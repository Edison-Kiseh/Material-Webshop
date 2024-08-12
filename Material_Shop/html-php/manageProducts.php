<?php
  //including the page that contains the connection to the database
  include('topPage.php');

  //checking if a user is logged in before accessing this page and also making sure that only admins can surf to it
  if($_SESSION['id'] == '' || $_SESSION['permissions'] != 'administrator'){
    header("Location: ./index.php");
  }

  //Preparing the SQL statement
  $stmt = $conn->prepare("SELECT * FROM products WHERE removed = 'no'");
  if ($stmt === false) {
      die('Prepare() failed: ' . htmlspecialchars($conn->error));
  }

  //Executing the statement
  $stmt->execute();

  //Binding the result variables
  $stmt->bind_result($id, $name, $price, $description, $type, $image, $removed);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Admin page - Manage products</title>
        <link href="../css/reset.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="../css/shoppingCart.css" rel="stylesheet" />
        <link href="../css/style.css" rel="stylesheet" />
        <!-- <script src="../js/jquery.js"></script> -->

    </head>

    <body>
        <!-- Including navigation bar -->
        <?php
            include('navigationBar.php');
        ?>

          <h1>Manage products</h1>

          <div class="container" style="margin-bottom: 60px">
            <table class="table table-bordered">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Product</th>
                    <th scope="col">Price</th>
                    <th scope="col">Description</th>
                    <th scope="col">Type</th>
                    <th scope="col">Image path</th>
                    <th scope="col">Removed</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    while($stmt->fetch()){
                      echo "<tr><td>$id</td>
                      <td>$name</td>
                      <td>$price</td>
                      <td>$description</td>
                      <td>$type</td>
                      <td>$image</td>
                      <td>$removed</td>
                      <td><a href=\"addModifyProduct.php?productAction=modify&productID=$id\"><button class=\"btn btn-primary\" style=\"width: 100px\">Modify</button></a>
                      <a href=\"productActions.php?productAction=remove&productID=$id\"><button class=\"btn btn-primary\" style=\"width: 100px\">Remove</button></a></td>";
                    }
                  ?>
                </tbody>
              </table>
              <?php echo " <a href=\"addModifyProduct.php?productAction=add\"><button class=\"btn btn-primary\" style=\"float: right;\">Add product</button></a><br/> "?>
          </div>
    </body>
</html>