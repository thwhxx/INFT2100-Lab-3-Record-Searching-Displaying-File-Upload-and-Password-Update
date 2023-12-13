<?php
include "./includes/header.php";

// Check if user is already signed in 
// if (isset($_SESSION['user_email'])) {
//     header("Location: dashboard.php"); 
//     exit();
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve input data
    $email = trim($_POST["inputEmail"]);
    $password = trim($_POST["inputPassword"]);
    // Verify both fields were entered

    $result = getuser($email);

    if($user = pg_fetch_assoc($result)) {
        // Authenticate user
        $userSelected = user_select($user['email']);
        // $result = user_authenticate($email, $password);
        if ($userSelected) {
            if(password_verify($password, $userSelected['password_hash'])) {
                $current_time = date('Y-m-d H:i:s');
                $query = "UPDATE users SET last_login_time = $1 WHERE id = $2";
                $result = pg_prepare($connection, "user_update_login_time", $query);
                $result = pg_execute($connection, "user_update_login_time", array($current_time, $user['id']));
                //Redirect to dashboard.php after successful login
                $_SESSION['message'] = "Login successful. Welcome, {$user['email']}!";
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = $user['user_type'];
                log_activity($userSelected);
                redirect("dashboard.php");
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Invalid ID or password. Please try again.";
        }
    }
    else {
        $_SESSION['error_message'] = "User does not exist. Please try again.";
    }
}
?>

<form method="post" action="sign-in.php" class="form-signin">
    <h1 class="h3 mb-3 font-weight-normal">Sign In</h1>
    <label for="inputEmail" class="sr-only">Email Address</label>
    <input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="Email address" required autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign In</button>

    <?php
    if(isset($_SESSION['error_message'])) {
        echo $_SESSION['error_message'];
        unset($_SESSION['error_message']); // Clear the error message
    }
    if(isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']); // Clear the success message
    }
    ?>
    
</form>

<?php
include "./includes/footer.php";
?>    