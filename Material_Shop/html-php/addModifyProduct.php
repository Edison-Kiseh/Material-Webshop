<?php
   //including the page that contains the connection to the database
   include('topPage.php');
   
    //checking if a user is logged in before accessing this page 
    if($_SESSION['id'] == ''){
        header("Location: ./index.php");
    }

   //getting the requested action
   if(isset($_GET['productAction'])){
    $action = htmlspecialchars($_GET['productAction']);

    if($action == 'modify'){
        $id = htmlspecialchars($_GET['productID']);
    }
   }
   else{
    header("Location: ./manageProducts.php");
   }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Product Action</title>
        <link href="../css/reset.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="../css/style.css" rel="stylesheet" />
        <link href="../css/shippingInfo.css" rel="stylesheet" />
        <!-- <script src="../js/jquery.js"></script> -->

        <script>
            function validateForm() // To make sure that the form has been filled
            {
                var name = document.getElementById('name').value;
                var price = document.getElementById('price').value;
                var desc = document.getElementById('description').value;
                var image = document.getElementById('image').value;
                var type = document.getElementById('type').value;

                if(name == "" || price == "" || desc == "" || image == "" || type == "none") {
                    if(name == "") {
                        document.getElementById("name").placeholder = "This field is required!";
                    } else {
                        document.getElementById("name").placeholder = "";
                    }

                    if(price == "") {
                        document.getElementById("price").placeholder = "This field is required!";
                    } else {
                        document.getElementById("price").placeholder = "";
                   }

                    if(desc == "") {
                        document.getElementById("description").placeholder = "This field is required!";
                    } else {
                        document.getElementById("description").placeholder = "";
                    }

                    if(image == "") {
                        document.getElementById("image").placeholder = "This field is required!";
                    } else {
                        document.getElementById("image").placeholder = "";
                    }

                    if (type == "none") {
                        document.getElementById('typeError').innerText = "Please select a type from the list";
                    }

                    return false;
                }

                return true;
            }
        </script>
    </head>

    <body>
        <!-- Including navigation bar -->
        <?php
            include('navigationBar.php');

            if($action == 'add'){
                echo "<h2>Add product</h2>";
            }else{
                echo "<h2>Modify product</h2>";

                $stmt = $conn->prepare("SELECT name, price, description, image, type FROM products WHERE productID = ?");
                $stmt->bind_param("i", $id);

                $stmt->execute();

                $stmt->bind_result($name, $price, $description, $image, $type);
                $stmt->fetch();
            }
        ?>

        <form autocomplete="off" action="../html-php/productActions.php" method="post" onsubmit="return validateForm()">
            <input type="hidden" name="productAction" value="<?php echo $action; ?>">
            <?php 
                if($action != 'add'){
                    echo "<input type=\"hidden\" name=\"productID\" value=\"$id\">";
                }
            ?>

            <div class="form-group">
                <label for="name">Product name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo ($action == 'modify') ? $name : ''; ?>"/>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" step="0.01" id="price" name="price" value="<?php echo ($action == 'modify') ? $price : ''; ?>">
            </div>
            <div class="form-group">
                <label for="types">Type:</label>
                <div id="typeError" style="color: red"></div>
                <?php
                    if($action ==  'modify'){
                        echo "<select name=\"type\" id=\"type\" class=\"form-control\" style=\"width: 170px\">
                                <option value=\"\" disabled <?php echo (empty($type) || $action != 'modify') ? 'selected' : ''; ?>>Select a type</option>
                                <option value=\"Silk\" <?php echo ($type == 'Silk') ? 'selected' : ''; ?>>Silk</option>
                                <option value=\"Polyester\" <?php echo ($type == 'Polyester') ? 'selected' : ''; ?>>Polyester</option>
                                <option value=\"Wool\" <?php echo ($type == 'Wool') ? 'selected' : ''; ?>>Wool</option>
                                <option value=\"Leather\" <?php echo ($type == 'Leather') ? 'selected' : ''; ?>>Leather</option>
                            </select>";
                    }
                    else{
                        echo "<select name=\"type\" id=\"type\" class=\"form-control\" style=\"width: 170px\">
                                <option value=\"none\">>Select a type</option>
                                <option value=\"Silk\">>Silk</option>
                                <option value=\"Polyester\">>Polyester</option>
                                <option value=\"Wool\">>Wool</option>
                                <option value=\"Leather\">>Leather</option>
                            </select>";
                    }
                ?>
            </div>
            <div class="form-group">
                <label for="image">Image url:</label>
                <input type="text" class="form-control" id="image" name="image" value="<?php echo ($action == 'modify') ? $image : ''; ?>">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" rows="3" name="description"><?php echo ($action == 'modify') ? htmlspecialchars($description) : ''; ?></textarea>
            </div>

            <input type="submit" name="submit" value="Submit" class="addModify"/>
        </form>
    </body>
</html>
