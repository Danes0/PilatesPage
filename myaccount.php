<?php
    // Start session for managing user login
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
    <title>My Account</title>
    <!-- Link to CSS file -->
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>

    <?php
        /*Header*/
        require_once('./includefiles/header.php');

        /* Connection to  the database */
        include './includefiles/connection.php';

        // Redirect to login page if user is not logged in
        if (!isset($_SESSION['loggedin'])) {
            header("location: login.php");
            exit;
        }

        // Get user information from the database
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT * FROM users WHERE user_id = $user_id";
        $result = $conn->query($sql);
        $user = $result->fetch_assoc();

        // Get profile image path from the database
        $sql_image = "SELECT image_path FROM profile_images WHERE user_id = $user_id";
        $result_image = $conn->query($sql_image);
        $image = $result_image->fetch_assoc();
        if ($image) {
            $_SESSION['profile_image'] = $image['image_path'];
        }

        // Handle form submission for uploading or deleting profile picture
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Code for uploading image
            if (isset($_POST['upload']) && isset($_FILES["profile_image"])) {
                // Directory where the images will be saved
                $target_dir = "uploadpic/";
                $target_file = $target_dir . basename($_FILES["profile_image"]["name"]); // Full file path
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Allow only JPG, JPEG and PNG files
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                    $statusUploap = "Only JPG, JPEG and PNG files are allowed.";
                    $uploadOk = 0;
                }

                // Check if upload is successful
                if ($uploadOk == 1) {
                    // If everything is fine, attempt to upload the file
                    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                        $statusUploap = "The file ". basename( $_FILES["profile_image"]["name"]). " has been uploaded.";
                        // Update the profile image path in the database
                        $image_path = $target_file;
                        $sql_update_image = "INSERT INTO profile_images (user_id, image_path) VALUES (?, ?) ON DUPLICATE KEY UPDATE image_path = ?";
                        $stmt_update_image = $conn->prepare($sql_update_image);
                        $stmt_update_image->bind_param("iss", $user_id, $image_path, $image_path);
                        $stmt_update_image->execute();
                        // Update the image path in the user
                        $user['profile_image'] = $image_path;
                        // Save the image path in the session
                        $_SESSION['profile_image'] = $image_path;
                    } else {
                        // Error message
                        $statusUploap = "An error occurred while uploading your file.";
                    }
                }
            // Code for deleting image
            } elseif (isset($_POST['delete'])) {
                $sql_delete_image = "DELETE FROM profile_images WHERE user_id = ?";
                $stmt_delete_image = $conn->prepare($sql_delete_image);
                $stmt_delete_image->bind_param("i", $user_id);
                $stmt_delete_image->execute();
                // Remove image path from session
                unset($_SESSION['profile_image']);
                $statusUploap = "Profile picture has been successfully deleted";
            }
        }
    ?>

    <!-- Profile Info -->
    <div class="profile-container">
        <h2>Welcome, <?php echo $user['username']; ?>!</h2>
        <div class="profile-info">
            <!-- Left section profile -->
            <div class="profile-left">
                <div class="imgProfile">
                    <img src="<?php echo isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'images/empty_profile.png'; ?>" alt="Profile Picture">
                </div>
                <!-- Display image status message-->
                <div id="statusMessage">
                    <?php
                        if(!empty($statusUploap)) {
                            echo $statusUploap;
                        }
                    ?>
                </div>
                <!-- Script to clear status message after 8 seconds -->
                <script>
                    setTimeout(function() {
                        document.getElementById('statusMessage').innerHTML = '';
                    }, 8000);
                </script>

                <!-- Form for uploading or deleting profile picture -->
                <div class="upload-img">
                    <form action="" method="post" enctype="multipart/form-data">
                        <!-- File input for selecting profile picture -->
                        <div class="choose-file-button">
                            <label for="profile_image_input">
                                <i class="fa-solid fa-upload"></i>
                                <input type="file" id="profile_image_input" name="profile_image" accept="image/*">
                            </label>
                        </div>
                        <!-- Buttons for uploading or deleting profile picture -->
                        <button class="upload-button" type="submit" name="upload">Upload/Change Profile Picture</button>
                        <button class="delete-button" type="submit" name="delete">Delete Profile Picture</button>
                    </form>
                </div>
            </div>
            <!-- Right section profile -->
            <div class="profile-right">
                <h3>Profile Information</h3>
                <div class="user-info">

                    <!-- Username Edit Form -->
                    <form class="edit-form" action="" method="post">
                        <h6><strong>Username</strong></h6>
                        <p>
                            <!-- Check if field is being edited -->
                            <?php if(isset($_POST['field']) && $_POST['field'] == 'username'): ?>
                                <input type="text" class="input-field" name="new_username" value="<?php echo $user['username']; ?>" required>
                                <!-- Save and Cancel buttons -->
                                <button class="save-button" type="submit" name="edit" value="username">Save</button>
                                <button class="cancel-button" type="button" onclick="window.location.href = 'myaccount.php';">Cancel</button>
                            <?php else: ?>
                                <!-- Display current field -->
                                <?php echo $user['username']; ?>
                                <!-- Button to edit field -->
                                <button class="edit-button" type="submit" name="field" value="username">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                            <?php endif; ?>
                        </p>
                        <!-- Input field hidden if edit is not pressed -->
                        <input type="hidden" name="field" value="username">
                    </form>

                    <!-- First Name Edit Form -->
                    <form class="edit-form" action="" method="post">
                        <h6><strong>Name</strong></h6>
                        <p>
                            <!-- Check if field is being edited -->
                            <?php if(isset($_POST['field']) && $_POST['field'] == 'first_name'): ?>
                                <input type="text" class="input-field"  name="new_first_name" value="<?php echo $user['first_name']; ?>" required>
                                <!-- Save and Cancel buttons -->
                                <button class="save-button" type="submit" name="edit" value="first_name">Save</button>
                                <button class="cancel-button" type="button" onclick="window.location.href = 'myaccount.php';">Cancel</button>
                            <?php else: ?>
                                <!-- Display current field -->
                                <?php echo $user['first_name']; ?>
                                <!-- Button to edit field -->
                                <button class="edit-button" type="submit" name="field" value="first_name">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                            <?php endif; ?>
                        </p>
                        <!-- Input field hidden if edit is not pressed -->
                        <input type="hidden" name="field" value="first_name">
                    </form>

                    <!-- Last Name Edit Form -->
                    <form class="edit-form" action="" method="post">
                        <h6><strong>Last Name<br></strong></h6>
                        <p>
                            <!-- Check if field is being edited -->
                            <?php if(isset($_POST['field']) && $_POST['field'] == 'last_name'): ?>
                                <input type="text" class="input-field"  name="new_last_name" value="<?php echo $user['last_name']; ?>" required>
                                <!-- Save and Cancel buttons -->
                                <button class="save-button" type="submit" name="edit" value="last_name">Save</button>
                                <button class="cancel-button" type="button" onclick="window.location.href = 'myaccount.php';">Cancel</button>
                            <?php else: ?>
                                <!-- Display current field -->
                                <?php echo $user['last_name']; ?>
                                <!-- Button to edit field -->
                                <button class="edit-button" type="submit" name="field" value="last_name">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                            <?php endif; ?>
                        </p>
                        <!-- Input field hidden if edit is not pressed -->
                        <input type="hidden" name="field" value="last_name">
                    </form>

                    <!-- Phone Edit Form -->
                    <form class="edit-form" action="" method="post">
                        <h6><strong>Phone</strong></h6>
                        <p>
                            <!-- Check if field is being edited -->
                            <?php if(isset($_POST['field']) && $_POST['field'] == 'phone'): ?>
                                <input type="text" class="input-field" name="new_phone" value="<?php echo $user['phone']; ?>" required>
                                <!-- Save and Cancel buttons -->
                                <button class="save-button" type="submit" name="edit" value="phone">Save</button>
                                <button class="cancel-button" type="button" onclick="window.location.href = 'myaccount.php';">Cancel</button>
                            <?php else: ?>
                                <!-- Display current field -->
                                <?php echo $user['phone']; ?>
                                <!-- Button to edit field -->
                                <button class="edit-button" type="submit" name="field" value="phone">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                            <?php endif; ?>
                        </p>
                        <!-- Input field hidden if edit is not pressed -->
                        <input type="hidden" name="field" value="phone">
                    </form>

                    <!-- Address Edit Form -->
                    <form class="edit-form" action="" method="post">
                        <h6><strong>Address</strong></h6>
                        <p>
                            <!-- Check if field is being edited -->
                            <?php if(isset($_POST['field']) && $_POST['field'] == 'address'): ?>
                                <input type="text" class="input-field" name="new_address" value="<?php echo $user['address']; ?>" required>
                                <!-- Save and Cancel buttons -->
                                <button class="save-button" type="submit" name="edit" value="address">Save</button>
                                <button class="cancel-button" type="button" onclick="window.location.href = 'myaccount.php';">Cancel</button>
                            <?php else: ?>
                                <!-- Display current field -->
                                <?php echo $user['address']; ?>
                                <!-- Button to edit field -->
                                <button class="edit-button" type="submit" name="field" value="address">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                            <?php endif; ?>
                        </p>
                        <!-- Input field hidden if edit is not pressed -->
                        <input type="hidden" name="field" value="address">
                    </form>

                    <!-- Date of Birth Edit Form -->
                    <form class="edit-form" action="" method="post">
                        <h6><strong>Date of Birth</strong></h6>
                        <p>
                            <!-- Check if field is being edited -->
                            <?php if(isset($_POST['field']) && $_POST['field'] == 'date_of_birth'): ?>
                                <input type="date" class="input-field" name="new_date_of_birth" value="<?php echo $user['date_of_birth']; ?>" required>
                                <!-- Save and Cancel buttons -->
                                <button class="save-button" type="submit" name="edit" value="date_of_birth">Save</button>
                                <button class="cancel-button" type="button" onclick="window.location.href = 'myaccount.php';">Cancel</button>
                            <?php else: ?>
                                <!-- Display current field -->
                                <?php echo $user['date_of_birth']; ?>
                                <!-- Button to edit field -->
                                <button class="edit-button" type="submit" name="field" value="date_of_birth">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                            <?php endif; ?>
                        </p>
                        <!-- Input field hidden if edit is not pressed -->
                        <input type="hidden" name="field" value="date_of_birth">
                    </form>

                    <!-- Health Condition Edit Form -->
                    <form class="edit-form" action="" method="post">
                        <h6><strong>Health Condition</strong></h6>
                        <p>
                            <!-- Check if field is being edited -->
                            <?php if(isset($_POST['field']) && $_POST['field'] == 'health_condition'): ?>
                                <input type="text" class="input-field" name="new_health_condition" value="<?php echo $user['health_condition']; ?>">
                                <!-- Save and Cancel buttons -->
                                <button class="save-button" type="submit" name="edit" value="health_condition">Save</button>
                                <button class="cancel-button" type="button" onclick="window.location.href = 'myaccount.php';">Cancel</button>
                            <?php else: ?>
                                <!-- Display current field -->
                                <?php echo $user['health_condition']; ?>
                                <!-- Button to edit field -->
                                <button class="edit-button" type="submit" name="field" value="health_condition">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                            <?php endif; ?>
                        </p>
                        <!-- Input field hidden if edit is not pressed -->
                        <input type="hidden" name="field" value="health_condition">
                    </form>
                </div>
            </div>

            <!-- Edit user data -->
            <?php
            // Check if the form has been submitted and edit action is set
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
                // Get the field to be edited
                $field = $_POST['edit'];
                // Determine which field is being edited and update the database accordingly
                if ($field == 'username') {
                    $new_value = $_POST['new_username'];
                    $sql_update = "UPDATE users SET username = ? WHERE user_id = ?";
                } elseif ($field == 'first_name') {
                    $new_value = $_POST['new_first_name'];
                    $sql_update = "UPDATE users SET first_name = ? WHERE user_id = ?";
                } elseif ($field == 'last_name') {
                    $new_value = $_POST['new_last_name'];
                    $sql_update = "UPDATE users SET last_name = ? WHERE user_id = ?";
                } elseif ($field == 'phone') {
                    $new_value = $_POST['new_phone'];
                    $sql_update = "UPDATE users SET phone = ? WHERE user_id = ?";
                } elseif ($field == 'address') {
                    $new_value = $_POST['new_address'];
                    $sql_update = "UPDATE users SET address = ? WHERE user_id = ?";
                } elseif ($field == 'date_of_birth') {
                    $new_value = $_POST['new_date_of_birth'];
                    $sql_update = "UPDATE users SET date_of_birth = ? WHERE user_id = ?";
                } elseif ($field == 'health_condition') {
                    $new_value = $_POST['new_health_condition'];
                    $sql_update = "UPDATE users SET health_condition = ? WHERE user_id = ?";
                }
                
                // Prepare SQL statement and bind parameters
                $stmt = $conn->prepare($sql_update);
                $stmt->bind_param("si", $new_value, $user_id);

                // Redirect to myaccount.php after successful update
                if ($stmt->execute()) {
                    echo '<script>window.location.href = "myaccount.php";</script>';
                    exit;
                }
            }
            ?>
        </div>
        
        <!-- Reservations Section -->
        <div class="reservations-container">
            <h3>Class Reservations</h3>
            <!-- Include reservations.php -->
            <?php include 'reservations.php'; ?>
        </div>

        <!-- Logout button -->
        <div class="logout-button">
            <button onclick="window.location.href = 'logout.php';"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</button>
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