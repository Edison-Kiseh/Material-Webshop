<?php
    include('topPage.php');
    //top level exception handler
    include_once "logging/ExceptionHandling.php";

    //AJAX request to get material by search and category
    $search = isset($_POST['search']) ? $_POST['search'] : '';
    $category = isset($_POST['category']) ? $_POST['category'] : 'all';

    //Building the query based on search and category
    if ($category == 'all' && $search == '') {
        $query = "SELECT productID, name, image, price, removed FROM products";
    } elseif ($category == 'all') {
        $query = "SELECT productID, name, image, price, removed FROM products WHERE name LIKE ?";
    } elseif ($search == '') {
        $query = "SELECT productID, name, image, price, removed FROM products WHERE type = ?";
    } else {
        $query = "SELECT productID, name, image, price, removed FROM products WHERE type = ? AND name LIKE ?";
    }

    //Using the exception handler to catch the use of special characters in the material search field

    //regex character pattern
    $pattern = '/[!@#$%^&*(),.?":{}|<>]/';

    try{
        //match the search pattern with the special chars and then throw exception if the match
        if(preg_match($pattern, $search)){
            throw new MyException("<h2 style=\"text-align: center;\">Danger: use of special characters in the search field is not allowed!</h2>");
        }
    }

    catch(MyException $e){
        $e->HandleException();
    }

    catch(Exception $e){
        $e->getMessage();
    }

    //prepare the query
    $stmt = $conn->prepare($query);

    //Bind parameters based on search and category
    if ($category == 'all' && $search != '') {
        $search = "%$search%";
        $stmt->bind_param("s", $search);
    } elseif ($category != 'all' && $search == '') {
        $stmt->bind_param("s", $category);
    } elseif ($category != 'all' && $search != '') {
        $search = "%$search%";
        $stmt->bind_param("ss", $category, $search);
    }

    //execute the query
    $stmt->execute();

    $stmt->bind_result($id, $name, $image, $price, $remove);
    $stmt->store_result();

    //number of rows
    $num_row = $stmt->num_rows;

    if($num_row != 0){
        for ($counter = 1; $counter <= $num_row; $counter++) {

            if ($counter % 6 == 1) {
                if ($counter > 1) {
                    echo("</div>");
                }
                echo("<div class=\"container content\">");
            }

            echo "<div class='row'>";
                //fetching each product row in the table
                while ($stmt->fetch()) {
                    //fetching only for those products that have not been removed from the database by an admin
                    if($remove == 'no'){
                        echo "<div class='col' style=\"margin-bottom: 30px\">";
                            echo "<a href='../html-php/productDetails.php?productID=" . $id . "'><img src='" . htmlspecialchars($image) . "' alt='product image' id='image'/></a>";
                            echo "<p>";
                            echo "<span style=\"font-size: 20px;\">" . htmlspecialchars($name) . "</span><br/>";
                            echo "<span style=\"font-size: 18px\">$" . htmlspecialchars($price) . "</span>";
                            echo "</p>";
                        echo "</div>";
                    }
                }
            echo "</div>";
        }

        if ($counter > 1 && $counter % 6 != 1) {
            echo("</div>");
        }
    }
    else{
        echo("<h1 style=\"text-align: center; color: black\">Material(s) not found</h1>");
    }

    //Closing statement and connection
    $stmt->close();
    $conn->close();
?>