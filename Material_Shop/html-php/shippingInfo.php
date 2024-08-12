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
        <title>Shipping info</title>
        <link href="../css/reset.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="../css/style.css" rel="stylesheet" />
        <link href="../css/shippingInfo.css" rel="stylesheet" />

        <script>
            function validateForm()//To make sure that the form has been filled
            {
                var street = document.getElementById('streetName').value;
                var zip = document.getElementById('zip').value;
                var number = document.getElementById('streetNo').value;
                var muni = document.getElementById('muni').value;
                var country = document.getElementById('country').value;

                if(street == "" || zip == "" || number == "" || muni == "" || country == "")
                {
                    if(street == "")
                    {
                        document.getElementById("streetName").placeholder = "This field is required!"
                    }
                    if(zip == "")
                    {
                        document.getElementById("zip").placeholder = "This field is required!"
                    }
                    if(number == "")
                    {
                        document.getElementById("streetNo").placeholder = "This field is required!"
                    }
                    if(muni == "")
                    {
                        document.getElementById("muni").placeholder = "This field is required!"
                    }
                    if(country == "")
                    {
                        document.getElementById("country").placeholder = "This field is required!"
                    }
                    
                return false;
                }

                return true;

            }
        </script>
    </head>

    <body>
        <!-- Including navigation bar -->
        <?php include('navigationBar.php'); ?>

        <h2>Enter your billing address</h2>
        <?php
            $uid = $_SESSION['id'];

            //first checking if the user previously set an address on their profile
            $stmt2 = $conn->prepare("SELECT streetName, number, zipCode, municipality, country FROM users WHERE userID = ?");
            $stmt2->bind_param("i", $uid);

            $stmt2->execute();

            $stmt2->bind_result($streetName, $number, $zipCode, $municipality, $country);

            $stmt2->fetch();

            //if the user already an address in the database, it will be fetched an displayed. They can choose to continue with the existing or change it
            if($streetName != '' || $number != 0 || $zipCode != 0 && $municipality != '' && $country != ''){
                // $stmt2->bind_result($streetName, $number, $zipCode, $municipality, $country);
                $addressExists = true;
            }
            else{
                $addressExists = false;

                $streetName = "";
                $number = "";
                $zipCode = "";
                $municipality = "";
                $country = "";
            }

            $stmt2->close();
        ?>

        <form action="?action=infoEntered" method="POST" onsubmit="return validateForm()">

        <!--message to display if user's address is alreay present in the db-->
        <?php
            if($addressExists == true){
                echo "<p style=\"text-align: center; color: red\">Proceed with your current address or modify it</p>";
            }
        ?>
                    
            <div class="form-group">
              <label for="streetName">Street name:</label>
              <?php echo "<input type=\"text\" class=\"form-control\" id=\"streetName\" name=\"street\" value=\"$streetName\">" ?>
            </div>
            <div class="form-group">
              <label for="streetNo">Street number:</label>
              <?php echo "<input type=\"text\" class=\"form-control\" id=\"streetNo\" name=\"number\" value=\"$number\">" ?>
            </div>
            <div class="form-group">
                <label for="muni">Municipality:</label>
                <?php echo "<input type=\"text\" class=\"form-control\" id=\"muni\" name=\"muni\" value=\"$municipality\">" ?>
            </div>
            <div class="form-group bottom">
                <div class="form-group">
                    <label for="country">Country:</label>
                    <?php echo "<input type=\"text\" class=\"form-control\" id=\"country\" name=\"country\" value=\"$country\">" ?>
                </div>
                <div class="form-group">
                    <label for="zip">ZIP / Postal code:</label>
                    <?php echo "<input type=\"text\" class=\"form-control\" id=\"zip\" name=\"zip\" value=\"$zipCode\">" ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <?php
            if(isset($_GET['action']) && $_GET['action'] == "infoEntered"){
                $uid = $_SESSION['id'];
                $street = htmlspecialchars($_POST['street']);
                $number = htmlspecialchars($_POST['number']);
                $municipality = htmlspecialchars($_POST['muni']);
                $zip = htmlspecialchars($_POST['zip']);
                $country = htmlspecialchars($_POST['country']);

                // Preparing the statement to insert/update user address in the database
                $stmt = $conn->prepare("UPDATE users SET streetName = ?, number = ?, zipCode = ?, municipality = ?, country = ? WHERE userID = ?");
                $stmt->bind_param("sssssi", $street, $number, $zip, $municipality, $country, $uid);

                $fullAddress = $street . " " . $number . ", " . $zip . " " . $municipality . " - " . $country;

                // Executing the statement
                if($stmt->execute()){
                    header("Location: ./orderSummary.php?address=$fullAddress");
                } else {
                    echo "Could not insert/update shipping details";
                }

                // Closing the statement
                $stmt->close();
            }
        ?>
    </body>
</html>