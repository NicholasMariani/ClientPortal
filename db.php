<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "************";
$dbname = "database";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form submitted for adding new data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST["action"] == "addSession") {
        $name = $_POST["name"];
        $type = $_POST['type'];
        $status = $_POST['status'];
        $date = $_POST['date'];
        $sql = "INSERT INTO sessions (name, type, status, date) VALUES ('$name', '$type', '$status', '$date')";
        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        header('Location: view_sessions.php'); // Redirect to clear form after submission
    } elseif ($_POST["action"] == "addClient") {
        $name = $_POST['name'];
        $insurance = $_POST['insurance'];
        $insurance_id = convertInsuranceNameToId($conn, $insurance)['id'];
        $sql = "INSERT INTO clients (name, insurance_id, balance) VALUES ('$name', '$insurance_id', '0')";
        echo $sql;
        if (mysqli_query($conn, $sql)) {
            echo "New record updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        header('Location: view_clients.php'); // Redirect to clear form after submission
    } elseif ($_POST["action"] == "addInsurance") {
        $name = $_POST["name"];
        $intake = $_POST['intake'];
        $session = $_POST['session'];
        $sql = "INSERT INTO insurances (name, intake, session) VALUES ('$name', '$intake', '$session')";
        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        header('Location: view_insurances.php'); // Redirect to clear form after submission
    } elseif ($_POST["action"] == "update") {
        $id = $_POST["id"];
        $name = $_POST['name'];
        $type = $_POST['type'];
        $status = $_POST['status'];
        $date = $_POST['date'];
        $sql = "UPDATE sessions SET name='$name', type='$type', status='$status', date='$date' WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            echo "New record updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        header('Location: view_sessions.php'); // Redirect to clear form after submission
    } elseif ($_POST["action"] == "updateClient") {
        $id = $_POST["id"];
        $name = $_POST['name'];
        $insurance = $_POST['insurance'];
        $balance = $_POST['balance'];
        $insurance_id = convertInsuranceNameToId($conn, $insurance)['id'];
        $sql = "UPDATE clients SET name='$name', insurance_id='$insurance_id', balance='$balance' WHERE id='$id'";
        echo $sql;
        if (mysqli_query($conn, $sql)) {
            echo "New record updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        header('Location: view_clients.php'); // Redirect to clear form after submission
    } elseif ($_POST["action"] == "updateInsurance") {
        $id = $_POST["id"];
        $name = $_POST['name'];
        $intake = $_POST['intake'];
        $session = $_POST['session'];
        $sql = "UPDATE insurances SET name='$name', intake='$intake', session='$session' WHERE id='$id'";
        if (mysqli_query($conn, $sql)) {
            echo "New record updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        header('Location: view_insurances.php'); // Redirect to clear form after submission
    } elseif ($_POST["action"] == "delete") {
        $id = $_POST["id"];
        $sql = "DELETE FROM sessions WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }
    } elseif ($_POST["action"] == "edit") {
        $id = $_POST["id"];
        $sql = "SELECT * FROM sessions WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $name = $row['name'];
        $type = $row['type'];
        $insurance = $row['insurance'];
        $status = $row['status'];
        $date = $row['date'];
        $rate = $row['rate'];
    } elseif ($_POST["action"] == "getClientInsurance") {
        $client_name = $_POST['client_name'];
        $result = convertInsuranceIdToName($conn, getClientInsurance($conn, $client_name));
        echo $result['name'];
    } elseif ($_POST["action"] == "checkClientSessions") {
        $client_name = $_POST['client_name'];
        checkClientSessions($conn, $client_name);
    }
}

function selectAllSessions($conn)
{
    // Select all data from the sessions table
    $sql = "SELECT * FROM sessions";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
function selectAllClients($conn)
{
    // Select all data from the sessions table
    $sql = "SELECT * FROM clients";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
function selectAllInsurances($conn)
{
    // Select all data from the sessions table
    $sql = "SELECT * FROM insurances";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
function convertInsuranceIdToName($conn, $id)
{
    // Select all data from the sessions table
    $sql = "SELECT name FROM insurances where id=$id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $row;
}
function convertInsuranceNameToId($conn, $name)
{
    // Select all data from the sessions table
    $sql = "SELECT id FROM insurances where name='$name'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $row;
}
function editSession($conn, $id)
{
    $sql = "SELECT * FROM sessions WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $row;
}
function editClient($conn, $id)
{
    $sql = "SELECT * FROM clients WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $row;
}
function editInsurance($conn, $id)
{
    $sql = "SELECT * FROM insurances WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $row;
}

function getClientInsurance($conn, $name)
{
    $sql = "SELECT insurance_id FROM clients WHERE name='$name'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $row['insurance_id'];
}
function checkClientSessions($conn, $client_name) {
    $sql = "SELECT COUNT(*) FROM sessions WHERE name = '$client_name'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    echo $row['COUNT(*)'];
}

function close($conn)
{
    // Close the database connection
    mysqli_close($conn);
}