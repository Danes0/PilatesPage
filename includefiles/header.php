<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Header Section</title>
</head>
<body>

    <!--Header Section-->
    <header id="header">
        <div class="header-container">
            <!-- Logo -->
            <a href="#"><img src="images/logo.png" class="logo" alt="page-logo"></a>
            <!-- Navigation Menu -->
            <nav>
                <ul id="navbar">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Class Types</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a class="active" href="login.php">My Account</a></li>
                </ul>
            </nav>
        </div>
        <!-- Search Form -->
        <div>
            <form class="d-flex" role="search" action="#" method="get" onsubmit="return false;">
                <input class="search" type="search" placeholder="Search" aria-label="Search" id="searchInput">
                <button class="button" type="button" onclick="search()">Search</button>
            </form>
        </div>
    </header>

    <!-- Link to Font Awesome icons -->
    <script src="https://kit.fontawesome.com/b5343ede9a.js" crossorigin="anonymous"></script>
</body>
</html>
