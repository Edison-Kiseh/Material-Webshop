<?php
  //including the page that contains the connection to the database
  include('topPage.php');

  //checking if a user is logged in before accessing this page and also making sure that only admins can surf to it
  if($_SESSION['id'] == '' || $_SESSION['permissions'] != 'administrator'){
    header("Location: ./index.php");
  }
  
  //Preparing the SQL statement
  $stmt = $conn->prepare("SELECT userID, firstName, lastName, email, permissions FROM users WHERE userRemoved = 'no'");
  if ($stmt === false) {
      die('Prepare() failed: ' . htmlspecialchars($conn->error));
  }

  //Executing the statement
  $stmt->execute();

  //Binding the result variables
  $stmt->bind_result($id, $firstName, $lastName, $email, $permissions);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Admin page - Manage users</title>
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

          <h1>Manage users</h1>

          <div class="container" style="margin-bottom: 60px">
            <table class="table table-bordered">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">First name</th>
                    <th scope="col">Last name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Permissions</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    while($stmt->fetch()){
                      echo "<tr><td>$id</td>
                      <td>$firstName</td>
                      <td>$lastName</td>
                      <td>$email</td>
                      <td>$permissions</td>";
                      //an operation won't be able to be performed on an administrator
                      if($permissions == 'user'){
                        echo "<td><a href=\"userActions.php?userAction=modify&userID=$id\"><button class=\"btn btn-primary\" style=\"width: 180px\">Change permissions</button></a>
                        <a href=\"userActions.php?userAction=remove&userID=$id\"><button class=\"btn btn-primary\" style=\"width: 100px\">Remove</button></a></td>";
                      }
                      else{
                        echo "<td><b>Can't perform operation on another adminstrator</b></td>";
                      }
                    }
                  ?>
                </tbody>
              </table>
              <?php echo " <a href=\"register.php?userAction=add\"><button class=\"btn btn-primary\" style=\"float: right\">Add user</button></a><br/> "?>
          </div>
    </body>
</html>