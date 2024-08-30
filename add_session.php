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

<body onload="document.forms[0].reset();">
    <h1>Add New Session</h1>
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
        <input type="hidden" name="action" value="addSession">
        <label for="name">Name:</label>
        <select name="name" id="name-select" required>
            <?php
            echo '<option value="">-- Select Client Name --</option>';
            foreach (selectAllClients($conn) as $client) {
                if ($client != '') {
                    echo '<option value="' . $client['name'] . '">' . $client['name'] . '</option>';
                }
            }
            ?>
        </select>
        <label for="type">Type:</label>
        <select name="type" id="type" required>
            <option value="">-- Select Session Type --</option>
            <option value="intake">Intake</option>
            <option value="session">Session</option>
            <option value="no show">No Show</option>
        </select><br>
        <label for="insurance">Insurance:</label>
        <input id="insurance" type="text" readonly>
        <label for="status">Status:</label>
        <select name="status" id="status" required>
            <option value="not paid" selected>Not Paid</option>
            <option value="paid">Paid</option>
        </select><br>
        <label for="date">Date:</label>
        <input type="date" name="date" id="date" required><br>
        <script>
            const today = new Date().toISOString().substr(0, 10);
            document.getElementById("date").defaultValue = today;
        </script>
        <input type="submit" value="Add Session">
    </form>
    <script>
        // listen for changes to the client select element
        document.getElementById('name-select').addEventListener('change', function() {
            // get the selected client name
            var clientName = this.value;

            // send an AJAX request to the PHP script
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'db.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // update the insurance input field with the response
                    if (clientName !== "") {
                        document.getElementById('insurance').value = xhr.responseText;
                    } else {
                        document.forms[0].reset();
                        document.getElementById('type').selectedIndex = 0;
                        document.getElementById('status').selectedIndex = 0;
                    }
                } else {
                    console.log('Request failed.  Returned status of ' + xhr.status);
                }
            };
            xhr.send('client_name=' + clientName + '&action=getClientInsurance');


            // send another AJAX request to the PHP script to check if any sessions exist for the selected client
            var xhr2 = new XMLHttpRequest();
            xhr2.open('POST', 'db.php');
            xhr2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr2.onload = function() {
                if (xhr2.status === 200) {
                    // if no sessions exist, select intake in the type dropdown
                    if (clientName !== "") {
                        if (xhr2.responseText === '0') {
                            document.querySelector('select[name="type"] option[value="intake"]').selected = true;
                        } else {
                            // otherwise, select session in the type dropdown
                            document.querySelector('select[name="type"] option[value="session"]').selected = true;
                        }
                    }
                } else {
                    console.log('Request failed.  Returned status of ' + xhr2.status);
                }
            };
            xhr2.send('client_name=' + clientName + '&action=checkClientSessions');
        });
    </script>
    <div class="theme-switch">
        <input type="checkbox" id="theme-switch-checkbox" onclick="toggleTheme()">
        <label for="theme-switch-checkbox"></label>
    </div>
</body>

</html>