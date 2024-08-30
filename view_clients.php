<!DOCTYPE html>
<html>

<head>
    <title>View Sessions</title>
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
    <h1>View Clients</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Insurance</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php require('db.php') ?>
            <?php foreach (selectAllClients($conn) as $client) : ?>
                <tr onclick="window.location='edit_client.php?id=<?php echo $client['id']; ?>'">
                    <td><?php echo $client['name']; ?></td>
                    <td>
                        <?php
                        $row = convertInsuranceIdToName($conn, $client['insurance_id']);
                        echo $row['name'];
                        ?>
                    </td>
                    <td><?php echo $client['balance']; ?></td>
                </tr>
            <?php endforeach;
            ?>
        </tbody>
    </table>
    <div class="theme-switch">
        <input type="checkbox" id="theme-switch-checkbox" onclick="toggleTheme()">
        <label for="theme-switch-checkbox"></label>
    </div>
</body>

</html>