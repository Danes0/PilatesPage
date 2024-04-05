<?php
    // Start session for managing user login
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Section</title>
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
    <!-- Link to CSS file -->
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <?php
        /* Header */
        require_once('includefiles/header.php');
        /* Connection to  the database */
        include 'includefiles/connection.php';

        // Handle login form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get user input
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Prepare and execute SQL statement to fetch user from database
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            
            // Verify user credentials
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                // Redirect to user account page
                header("location: myaccount.php");
                exit;
            } else {
                // Set an error message if login fails
                $_SESSION['message'] = "Email or password is incorrect!";
            }
        }
    ?>

    <!-- Login section -->
    <div class="login-container">
        <h1>Login</h1>
        <div class="login-form">
            <form action="login.php" method="post">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <?php
                // Display any error messages
                if(isset($_SESSION['message'])) {
                    echo '<p style="color: #bc0000; font-style: italic; font-size: 14px;">' . $_SESSION['message'] . '</p>';
                    // Clear error message after displaying
                    unset($_SESSION['message']);
                }
            ?>
            <p>Don't have an account? <a href="register.php">Register now</a></p>
        </div>
    </div>

    <!-- footer -->
    <?php
        require_once('includefiles/footer.php');
    ?>
    <!-- Link to Font Awesome icons -->
    <script src="https://kit.fontawesome.com/b5343ede9a.js" crossorigin="anonymous"></script>
    <!--JavaScript-->
    <script src="js/script.js"></script>
</body>
</html>
