<!DOCTYPE html>
<html>

<head>
    <title>Add Session</title>
    <link rel="stylesheet" href="light.css" id="theme-style">
    <script>
        function toggleTheme() {
            var themeStyle = document.getElementById("theme-style");
            if (themeStyle.getAttribute("href") == "light.css") {
                themeStyle.setAttribute("href", "dark.css");
                localStorage.setItem('theme', 'dark');
            } else {
                themeStyle.setAttribute("href", "light.css");
                localStorage.setItem('theme', 'light');
            }
        }

        window.onload = function() {
            var theme = localStorage.getItem('theme');
            if (theme == 'dark') {
                document.getElementById("theme-style").setAttribute("href", "dark.css");
            } else {
                document.getElementById("theme-style").setAttribute("href", "light.css");
            }
        }
    </script>
</head>

<body>
    <h1>Add New Client</h1>
    <div class="menu" id="myMenu">
        <a href="javascript:void(0)" class="toggle-button" onclick="toggleMenu()">&#9776;</a>
        <a href="add_session.php">Add New Session</a>
        <a href="view_sessions.php">View Sessions</a>
        <a href="add_client.php">Add New Client</a>
        <a href="view_clients.php">View Clients</a>
        <a href="add_insurance.php">Add New Insurance</a>
        <a href="view_insurances.php">View Insurances</a>
    </div>
    <script>
        // Function to toggle the menu
        function toggleMenu() {
            var menu = document.getElementById("myMenu");
            if (menu.style.width === "200px") {
                menu.style.width = "0";
            } else {
                menu.style.width = "200px";
            }
        }
    </script>
    <form method="post">
        <?php require('db.php') ?>
        <input type="hidden" name="action" value="addClient">
        <label for="name">Name:</label>
        <input type="text" name="name" required><br>
        <label for="insurance">Insurance:</label>
        <select name="insurance" id="insurance-select">
            <?php
            foreach (selectAllInsurances($conn) as $insurance) {
                echo '<option value="' . $insurance['name'] . '">' . $insurance['name'] . '</option>';
            }
            ?>
        </select>
        <input type="submit" value="Add Client">
    </form>
    <div class="theme-switch">
        <input type="checkbox" id="theme-switch-checkbox" onclick="toggleTheme()">
        <label for="theme-switch-checkbox"></label>
    </div>
</body>

</html>