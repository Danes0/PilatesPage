<?php
    /* Connection to  the database */
    include './includefiles/connection.php';

    // Check if a form has been submitted from myaccount.php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['add_reservation'])) {
            // Process adding a reservation
            $class_date = $_POST['class_date'];
            $class_time = $_POST['class_time'];
            $instructor = $_POST['instructor'];
            // Proceed with inserting the reservation
            $sql_insert = "INSERT INTO class_reservations (user_id, class_date, class_time, instructor) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("isss", $user_id, $class_date, $class_time, $instructor);
            $stmt_insert->execute();
        } elseif (isset($_POST['delete_reservation'])) {
            // Process deleting a reservation
            $reservation_id = $_POST['reservation_id'];
            $sql_delete = "DELETE FROM class_reservations WHERE class_id = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param("i", $reservation_id);
            $stmt_delete->execute();
        }
    }
    // Get user reservations ordered by date from smallest to largest
    $user_id = $_SESSION['user_id'];
    $sql_select = "SELECT * FROM class_reservations WHERE user_id = ? ORDER BY class_date ASC";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $user_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reservations Section</title>
</head>
<body>
    <!-- Class Reservations Section -->
    <div class="class-reservations">
        <div class="show-reservations">
            <?php if ($result->num_rows > 0): ?>
                <!--  Display existing reservations -->
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Instructor</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--  Populate table with data retrieved from database -->
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['class_date']; ?></td>
                                <td><?php echo date('H:i', strtotime($row['class_time'])); ?></td>
                                <td><?php echo $row['instructor']; ?></td>
                                <td>
                                    <form action="myaccount.php" method="post">
                                        <input type="hidden" name="reservation_id" value="<?php echo $row['class_id']; ?>">
                                        <!-- Button to  delete the current reservation -->
                                        <button class="delete_reservation" type="submit" name="delete_reservation">
                                            <i class="fa-regular fa-calendar-xmark"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <!--  No existing reservations message -->
                <p>You don't have any reservations.</p>
            <?php endif; ?>

            <div class="add_reservation">
                <!-- Add a Reservation Section-->
                <h4>Add Reservation</h4>
                <!--  Form for adding new reservations -->
                <form action="myaccount.php" method="post" onsubmit="return validateForm()">
                    <!-- Date field -->
                    <label>Date</label>
                    <input type="date" name="class_date" id="class_date" required>
                    <!-- Time field -->
                    <label>Time</label>
                    <select name="class_time" id="class_time" required>
                        <option value="Time" disabled selected>Time</option>
                        <option value="09:00">9:00</option>
                        <option value="10:00">10:00</option>
                        <option value="11:00">11:00</option>
                        <option value="12:00">12:00</option>
                        <option value="13:00">13:00</option>
                        <option value="14:00">14:00</option>
                        <option value="15:00">15:00</option>
                        <option value="16:00">16:00</option>
                        <option value="17:00">17:00</option>
                        <option value="18:00">18:00</option>
                        <option value="19:00">19:00</option>
                        <option value="20:00">20:00</option>
                    </select>
                    <!-- Instructor field -->
                    <label>Instructor</label>
                    <select name="instructor" required>
                        <option value="Instructor" disabled selected>Instructor</option>
                        <option value="Nicol">Nicol</option>
                        <option value="Robert">Robert</option>
                        <option value="Susan">Susan</option>
                    </select>
                    <!--  Submit Button to add the new reservation -->
                    <button type="submit" name="add_reservation">Add Reservation</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
