<?php
  //including the page that contains the connection to the database
  include('topPage.php');

  //checking if a user is logged in before accessing this page and also making sure that only admins can surf to it
  if($_SESSION['id'] == '' || $_SESSION['permissions'] != 'administrator'){
    header("Location: ./index.php");
  }

  if(isset($_POST['userAction'])){
    $id = htmlspecialchars($_POST['productID']);

    $name = htmlspecialchars($_POST['name']);
    $price = htmlspecialchars($_POST['price']);
    $type = htmlspecialchars($_POST['type']);
    $desc = htmlspecialchars($_POST['description']);
    $image = htmlspecialchars($_POST['image']);

    if($_POST['productAction'] == 'add'){
        $stmt = $conn->prepare("INSERT INTO products (name, price, type, description, image, removed) VALUES (?, ?, ?, ?, ?, 'no')");
        $stmt->bind_param("sdsss", $name, $price, $type, $desc, $image);

        $message = 'added';
    }

    $stmt->execute();

    $stmt->close();

    //redirecting to the admin page after deletion
    header("Location: ./manageProducts.php?message=$message");

  }
  elseif(isset($_GET['userAction'])){
    $id = htmlspecialchars($_GET['userID']);

    if($_GET['userAction'] == 'remove'){
        $stmt = $conn->prepare("UPDATE users SET userRemoved = 'yes' WHERE userID = ?");

        $stmt->bind_param("i", $id);

        $message = 'deleted';
    }
    else if($_GET['userAction'] == 'modify'){
        $stmt = $conn->prepare("UPDATE users SET permissions = 'administrator' WHERE userID = ?");

        $stmt->bind_param("i", $id);

        $message = 'changedPermissions';
    }

    $stmt->execute();

    $stmt->close();

    //redirecting to the admin page after deletion
    header("Location: ./manageUsers.php?message=$message");

  }else{
    header("Location: ./manageUsers.php");
  }
?>