<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Section</title>
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
    <!-- Link to CSS file -->
    <link rel="stylesheet" href="CSS/style.css">

</head>
<body>

<?php
    /* Header*/
    require_once('./includefiles/header.php');

    /* Connection to  the database */
    include './includefiles/connection.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $date_of_birth = $_POST['date_of_birth'];
        $sex = $_POST['sex'];
        $health_condition = $_POST['health_condition'];

        $stmt = $conn->prepare("INSERT INTO users (username, first_name, last_name, email, password, phone, address, date_of_birth, sex, health_condition) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $username, $first_name, $last_name, $email, $password, $phone, $address, $date_of_birth, $sex, $health_condition);

        if ($stmt->execute()) {
            $_SESSION['message2'] = "Successful registration. Sign in to continue.";
            header("location: login.php");
            exit;  // Termina la ejecución del script después de la redirección
        } else {
            $_SESSION['message2'] = "Error: " . $stmt->error;
        }
    }
?>

<div class="register-container">
    <h1>Sign Up</h1>
    <div class="register-form">
        <form action="register.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="tel" name="phone" placeholder="Phone Number" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="date" name="date_of_birth" required>
            <select name="sex" required>
                <option value="SelectG" disabled selected>Select Gender</option>
                <option value="female">Female</option>
                <option value="male">Male</option>
                <option value="other">Prefer not to say</option>
            </select>
            <textarea name="health_condition" placeholder="Health Condition"></textarea>
            <button type="submit">Register</button>
        </form>
    </div>
</div>

<!-- footer -->
<?php
require_once('./includefiles/footer.php');
?>
<!-- Link to Font Awesome icons -->
<script src="https://kit.fontawesome.com/b5343ede9a.js" crossorigin="anonymous"></script>
<!--JavaScript-->
<script src="js/script.js"></script>

</body>
</html>
