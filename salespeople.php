<?php 
require_once "./includes/header.php";
?>


<?php
// Check if user is logged in and is an ADMIN
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== ADMIN) {
    header("Location: sign-in.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = array();

    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $extension = $_POST['phone_extension'];

    // Check if fields are not empty
    if (empty($firstName) || empty($lastName) || empty($email) || empty($extension)) {
        $errors[] = "All fields are required.";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($errors)) {
        // Create a new salesperson (user)
        create_salesperson($firstName, $lastName, $email, $extension, $password);

        // Show success message or redirect to a success page
        $_SESSION['message'] = "Salesperson created successfully!";
        redirect('salespeople.php');
    } else {
        // If there are validation errors, display them
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}
?>

<form method="POST" action="" class="form-signin">
    <h1 class="h3 mb-3 font-weight-normal">Register Sales Person</h1>
    <?php echo display_form(); ?>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
</form>

<?php
    if(isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']); // Clear the error message
    }
    if(isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']); // Clear the success message
    }
?>

<?php 
require_once "./includes/footer.php";
?>