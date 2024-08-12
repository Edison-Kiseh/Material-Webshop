<?php 
    //including the page that contains the connection to the database
    include('topPage.php');

    //Preparing the SQL statement
    $stmt = $conn->prepare("SELECT productID, name, price, description, image FROM products WHERE removed = 'no'");
    if ($stmt === false) {
        die('Prepare() failed: ' . htmlspecialchars($conn->error));
    }

    //Executing the statement
    $stmt->execute();

    //Binding the result variables
    $stmt->bind_result($id, $name, $price, $description, $image);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <link href="../css/reset.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="../css/home.css" rel="stylesheet" />
        <link href="../css/style.css" rel="stylesheet" />
        <script src="../js/jquery.js"></script>
        <script>
            $(document).ready(function() {
                // Initial fetch to load all available materials
                fetchMaterials('all');

                // Grouping material by types
                $("#materialType").change(function() {
                    var category = $(this).val(); // Selecting the value of the select statement
                    fetchMaterials(category);
                });

                // Function to fetch and display the material based on the selected material type
                function fetchMaterials(category) {
                    $.ajax({
                        url: 'materialByCategoryAndSearch.php', 
                        type: 'POST',
                        data: { category: category },
                        success: function(data) {
                            // Update the material container div with the received data
                            $("#materials").fadeOut(100, function(){
                                $(this).html(data);
                                $(this).fadeIn(300);
                            });
                        },
                        error: function(error) {
                            console.error("Error fetching the materials:", error);
                        }
                    });
                }

                //the search functionality
                $("#search").keyup(function() {
                    var searchString = $(this).val();
                    console.log(searchString);
                    
                    $.ajax({
                        method: "POST",
                        url: "materialByCategoryAndSearch.php",
                        data: { search: searchString },
                        success: function(data) {
                            $("#materials").fadeOut(100, function() {
                                $(this).html(data);
                                $(this).fadeIn(300);
                            });
                        },
                        error: function(jqXHR, textStatus) {
                            $("#materials").html("Request failed: " + textStatus);
                        }
                    });
                });
            });
        </script>
    </head>

    <body>
        <!-- Including navigation bar -->
        <?php
            include('navigationBar.php');
        ?>

        <div class="cover-image">
            <img src="../images/grey.jpeg" alt="cover image"/>
            <div class="text-overlay">
                <h1>Material Shop</h1><br/>
                <h4>We offer exclusive materials for any clothing type</h4>
            </div>
        </div>

        <div class="container-trui parent"> 
            <div class="container product-action">    
                <div>
                    <input type="text" name="search" placeholder="What are you looking for?" class="search" id="search"/>
                </div>

                <div>
                    <label for="types"><b>Material type:</b></label>
                    <select name="type" id="materialType" class="type">
                        <option value="all">All</option>
                        <option value="Silk">Silk</option>
                        <option value="Polyester">Polyester</option>
                        <option value="Wool">Wool</option>
                        <option value="Leather">Leather</option>
                    </select>
                </div>
            </div>

            <!-- Container that holds all the products -->
            <div class="container products" id="materials"></div>
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
