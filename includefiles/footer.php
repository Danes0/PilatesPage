<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Header Section</title>
</head>
<body>
    <!--Footer Section-->
    <footer>
        <!--Logo/Follow Column-->
        <div class="col1">
            <img class="logo" src="images/logo.png" alt="page-logo">
            <h4>Follow us</h4>
            <div class="icon">
                <i class="fab fa-facebook"></i>
                <i class="fab fa-instagram"></i>
                <i class="fab fa-twitter"></i>
                <i class="fab fa-youtube"></i>
            </div>
        </div>
        <!--Contact Column-->
        <div class="col2">
            <h4>Contact</h4>
            <p><strong>Address: </strong>120 Bloor St E, Toronto, ON M4W 1B7</p>
            <p><strong>Phone: </strong>+1(423) 3845-543</p>
            <p> <strong>Hours: </strong></p>
            <p>Monday to Friday 9 AM - 9 PM</p>
            <p>Saturday 9 AM - 4 PM</p>
        </div>
        <!--About Column-->
        <div class="col3">
            <h4>Company</h4>
            <a href="#">About us</a>
            <a href="#">Delivery Information</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms & Conditions</a>
        </div>
        <!--My Account Column-->
        <div class="col3">
            <h4>Account</h4>
            <a href="#">My Account</a>
            <a href="#">Sign In</a>
            <a href="#">Testimonials</a>
            <a href="#">Contact Us</a>
        </div>
        <!-- Newsletter Signup Column -->
        <div class="newsletter">
            <h4>Subscribe to Our Newsletter!</h4>
            <p>Get the latest news, promotions, and updates delivered straight to your inbox.</p>
            <form action="" method="post">
                <input type="email" name="email" placeholder="Your email address" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" required>
                <button type="submit" name="subscribe">Subscribe Now!</button>
            </form>
        </div>

        <!-- Custom Popup -->
        <div class="custom-popup" id="customPopup">
            <span class="custom-popup-close" onclick="closeCustomPopup()">&times;</span>
            <p id="customPopupMessage"></p>
        </div>
        
        <!--Copyright-->
        <div class="copyright">
            <p>©2024, Pilates. All right reserved.</p>
        </div>
    </footer>
    
    <!--Scripts-->
    <!-- Link to Font Awesome icons -->
    <script src="https://kit.fontawesome.com/b5343ede9a.js" crossorigin="anonymous"></script>
    <!--Script for popup-->
    <script src="../js/script.js"></script>
</body>
</html>