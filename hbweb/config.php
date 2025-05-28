<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// DB connection
$host = 'localhost';
$db = 'hotelbooking';
$user = 'root';
$pass = ''; // Replace with your DB password if set

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// -------------------------
// Register
// -------------------------
if (isset($_POST['registerBtn'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $username = $fullname;
    $usertype_id = 2;

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT id FROM login WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        echo "<script>alert('Email is already registered. Please use another.'); window.location.href='index.php';</script>";
        $checkEmail->close();
        exit();
    }
    $checkEmail->close();

    // Insert into login table
    $stmt = $conn->prepare("INSERT INTO login (username, email, password, usertype_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $username, $email, $hashedPassword, $usertype_id);

    if ($stmt->execute()) {
        $login_id = $stmt->insert_id;

        // Insert into profile
        $stmt2 = $conn->prepare("INSERT INTO profile (fullname, contact_number, address, birth_date, password, login_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("sisssi", $fullname, $phone, $address, $dob, $hashedPassword, $login_id);
        $stmt2->execute();
        echo "<script>alert('Registration successful'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Registration failed'); window.location.href='index.php';</script>";
    }

    $stmt->close();
    $stmt2->close();
}


// -------------------------
// Login
// -------------------------
if (isset($_POST['loginBtn'])) {
    $identifier = $_POST['identifier'];
    $password = $_POST['password'];

    // Check if identifier matches admin (by username)
    $stmt = $conn->prepare("SELECT id, password, usertype_id FROM login WHERE username = ? AND usertype_id = 1");
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        // Admin login
        $stmt->bind_result($login_id, $hashedPassword, $usertype_id);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['login_id'] = $login_id;
            $_SESSION['usertype_id'] = $usertype_id;
            echo "<script>alert('Welcome Admin'); window.location.href='admin/index.php';</script>";
        } else {
            echo "<script>alert('Invalid password'); window.location.href='index.php';</script>";
        }
    } else {
        // If not admin, try client login via email
        $stmt->close();
        $stmt = $conn->prepare("SELECT id, password, usertype_id FROM login WHERE email = ? AND usertype_id = 2");
        $stmt->bind_param("s", $identifier);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($login_id, $hashedPassword, $usertype_id);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['login_id'] = $login_id;
                $_SESSION['usertype_id'] = $usertype_id;
                echo "<script>alert('Login successful'); window.location.href='client/index.php';</script>";
            } else {
                echo "<script>alert('Invalid password'); window.location.href='index.php';</script>";
            }
        } else {
            echo "<script>alert('Invalid login credentials'); window.location.href='index.php';</script>";
        }
    }

    $stmt->close();
}

?>
