<?php
// Start output buffering
ob_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include_once("db_conn.php");



// User registration function
function userRegistration($userName, $userEmail, $userPass, $userPhone, $userNic) {
    // Establish database connection
    $db_conn = Connection();

    // Sanitize inputs to prevent SQL injection
    $userName = mysqli_real_escape_string($db_conn, $userName);
    $userEmail = mysqli_real_escape_string($db_conn, $userEmail);
    $userPhone = mysqli_real_escape_string($db_conn, $userPhone);
    $userNic = mysqli_real_escape_string($db_conn, $userNic);
    $userPass = mysqli_real_escape_string($db_conn, $userPass);

    // Check if the email already exists
    $checkEmailSql = "SELECT * FROM user_tbl WHERE user_email = '$userEmail';";
    $checkEmailResult = mysqli_query($db_conn, $checkEmailSql);

    if (mysqli_num_rows($checkEmailResult) > 0) {
        return "Email already exists!";
    }

    // Insert into user_tbl
    $insertUserSql = "INSERT INTO user_tbl (user_name, user_email, user_phone, user_nic, user_status) 
                      VALUES ('$userName', '$userEmail', '$userPhone', '$userNic', 1);";
    $userResult = mysqli_query($db_conn, $insertUserSql);

    // Check if the query was successful
    if (!$userResult) {
        return "Error inserting user: " . mysqli_error($db_conn);
    }

    // Hash the password using MD5
    $hashedPassword = md5($userPass);

    // Insert into login_tbl
    $insertLoginSql = "INSERT INTO login_tbl (login_email, login_pwd, login_role, login_status)
                       VALUES ('$userEmail', '$hashedPassword', 'user', 1);";
    $loginResult = mysqli_query($db_conn, $insertLoginSql);

    // Check if the query was successful
    if (!$loginResult) {
        return "Error inserting login details: " . mysqli_error($db_conn);
    }

    return "Your registration was successful!";
}

// User login (authentication) function
function Authentication($userName, $userPass) {
    // Establish database connection
    $db_conn = Connection();

    // Check if the connection was successful
    if (!$db_conn) {
        return "Database connection failed!";
    }

    // Fetch the user record from the login table
    $sqlFetchUser = "SELECT * FROM login_tbl WHERE login_email = '$userName';";
    $sqlResult = mysqli_query($db_conn, $sqlFetchUser);

    // Check if the query failed
    if (!$sqlResult) {
        return "Error fetching user: " . mysqli_error($db_conn);
    }

    // Convert user password into hashed value using MD5
    $hashedPassword = md5($userPass);

    // Check if the user exists
    if (mysqli_num_rows($sqlResult) > 0) {
        // Fetch user record
        $userRecord = mysqli_fetch_assoc($sqlResult);

        // Validate password
        if ($userRecord['login_pwd'] == $hashedPassword) {
            // Check if the account is active
            if ($userRecord['login_status'] == 1) {
                // Check if the user is admin or a regular user
                if ($userRecord['login_role'] == "admin") {
                    // Redirect to the admin dashboard
                    header('Location: lib/views/dashboards/admin.php');
                    exit(); // Stop further execution after redirect
                } else {
                    // Redirect to the user dashboard
                    header('Location: lib/views/dashboards/user.php');
                    exit(); // Stop further execution after redirect
                }
            } else {
                return "Your account has been deactivated!";
            }
        } else {
            return "Incorrect password! Please try again.";
        }
    } else {
        return "No records found!";
    }
}


// End output buffering and flush output
ob_end_flush();
?>
