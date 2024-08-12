<?php
  //including the page that contains the connection to the database
  include('topPage.php');

  //checking if a user is logged in before accessing this page 
  if($_SESSION['id'] == ''){
    header("Location: ./index.php");
  }

  if(isset($_POST['productAction'])){
    if(isset($_POST['productID'])){
      $id = htmlspecialchars($_POST['productID']);
    }

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
    else if($_POST['productAction'] == 'modify'){
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, type = ?, description = ?, image = ? WHERE productID = ?");
        $stmt->bind_param("sdsssi", $name, $price, $type, $desc, $image, $id);

        $message = 'updated';
    }

    $stmt->execute();

    $stmt->close();

    //redirecting to the admin page after deletion
    header("Location: ./manageProducts.php?message=$message");

  }
  elseif(isset($_GET['productAction'])){
    $id = htmlspecialchars($_GET['productID']);

    if($_GET['productAction'] == 'remove'){
        $stmt = $conn->prepare("UPDATE products SET removed = 'yes' WHERE productID = ?");

        $stmt->bind_param("i", $id);

        $message = 'deleted';
    }
    $stmt->execute();

    $stmt->close();

    //redirecting to the admin page after deletion
    header("Location: ./manageProducts.php?message=$message");

  }else{
    header("Location: ./manageProducts.php");
  }
?>
