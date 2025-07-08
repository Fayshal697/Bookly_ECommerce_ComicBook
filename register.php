<?php
include 'config.php';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    $cpass = mysqli_real_escape_string($conn, $_POST['cpassword']);

    // Check if the email already exists
    $select_users = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' AND password = '$pass'") or die('Query failed');

    if (mysqli_num_rows($select_users) > 0) {
        $message[] = 'User already exists!';
    } else {
        if ($pass !== $cpass) {
            $message[] = 'Confirm password does not match!';
        } else {
            // Hash the password for security
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

            // Insert new user into the database
            $insert_query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')" or die('Query failed');
            if (mysqli_query($conn, $insert_query)) {
                $message[] = 'Registered successfully!';
            } else {
                $message[] = 'Registration failed!';
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- link font awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- link file css -->
     <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo '
        <div>
            <span>' . htmlspecialchars($msg) . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}?>
    
<div class="form-container">
    <form action="" method="post">
        <h3>Register Now</h3>
        <input type="text" name="name" placeholder="Enter your name" required class="box">
        <input type="email" name="email" placeholder="Enter your email" required class="box">
        <input type="password" name="password" placeholder="Enter your password" required class="box">
        <input type="password" name="cpassword" placeholder="Confirm your password" required class="box">
        <input type="submit" name="submit" value="Register Now" class="btn">
        <p>Already have an account? <a href="login.php">Login Now</a></p>
    </form>
</div>
</body>
</html>