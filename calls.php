<?php 
require_once "./includes/header.php";
?>

<?php
function create_call($callDateTime, $notes, $clientID) {
    $conn = pg_connect("host=" . DB_HOST . " user=" . DB_USER . " password=" . DB_PASSWORD . " dbname=" . DB_NAME);

    if (!$conn) {
        die("Connection failed: " . pg_last_error());
    }

    $sql = "INSERT INTO calls (CallDateTime, Notes, ClientID)
            VALUES ('$callDateTime', '$notes', '$clientID')";

    $result = pg_query($conn, $sql);

    if ($result) {
        echo "New call created successfully";
    } else {
        echo "Error: " . pg_last_error($conn);
    }

    pg_close($conn);
}

// Check if the user is logged in
is_logged_in();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = array();

    // Validate form fields
    $callDateTime = $_POST['CallDateTime'];
    $notes = $_POST['Notes'];
    $clientID = $_POST['ClientID'];

    // Check if fields are not empty
    if (empty($callDateTime) || empty($clientID)) {
        $errors[] = "All fields are required.";
    }

    if (empty($errors)) {
        // Create a new call
        create_call($callDateTime, $notes, $clientID);

        // Show success message or redirect to a success page
        $_SESSION['message'] = "Call created successfully!";
        redirect('calls.php');
    } else {
        // If there are validation errors, display them
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}
?>

<form method="POST" action="" class="form-signin">
    <h1 class="h3 mb-3 font-weight-normal">Create Call</h1>
    <?php echo display_form_calls(); ?>
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