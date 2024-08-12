<!--navigation bar-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="../html-php/index.php">Materia</a>

        <div class="right-side ml-auto">
            <?php
                if(isset($_SESSION['id']) && isset($_SESSION['permissions'])){
                    //depending if the user is admin or not, the admin tab is shown on the navbar 
                    if($_SESSION['permissions'] == 'administrator'){
                        //dropdown menu for admin page
                        echo  "<div class=\"dropdown\">
                        <span class=\"dropbtn\">Admin page</span>
                        <div class=\"dropdown-content\">
                            <a href=\"./manageProducts.php\">products</a>
                            <a href=\"./manageUsers.php\">users</a>
                        </div>
                        </div>";
                    }

                    //the other links
                    echo '<a href="../html-php/orders.php">My Orders</a>';
                    echo '<a href="../html-php/shoppingCart.php"><img src="../images/shopping-cart.png" alt="shopping cart" class="shopping-cart" /></a>';
                    echo '<a href="../html-php/logout.php"><button class="btn btn-primary">Logout</button></a>';
                }
                else{
                    echo '<a href="../html-php/login.php"><img src="../images/shopping-cart.png" alt="shopping cart" class="shopping-cart" /></a>';
                    echo '<a href="../html-php/login.php"><button class="btn btn-primary">Register/Login</button></a>';
                }
            ?>
        </div>
    </div>
</nav>

<!-- Bootstrap dependency -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-c7Ew7+Y8DkZzFEr7C7Q4FTzjlqfZ6LMo4lMZxNIpUqQYQ9+7yU7z+Dj9sD6+eq5G" crossorigin="anonymous"></script>
