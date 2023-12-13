<?php
require ('./includes/header.php');

// Update user password
// update_password($userId, $hashedPassword);

// Add logo field to clients table
// add_logo_field();

// Define upload directory and file path
// $uploadDir = '\css\images\logo.png';
// $uploadFile = $uploadDir . basename($_FILES['logo']['name']);


$errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = array();

        // Validate form fields 
        $phoneNumber = $_POST['PhoneNumber'];
        $extension = $_POST['extension'];
        $emailaddress = $_POST['emailaddress'];
        $firstName = $_POST['FirstName'];
        $lastName = $_POST['LastName'];
        $logoPath = $_FILES['logopath']['name'];

        // Check if fields are not empty
        if (empty($phoneNumber) || empty($emailaddress) || empty($firstName) || empty($lastName)) {
            $errors[] = "All fields are required.";
        }

        // Validate email format
        if (!filter_var($emailaddress, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (isset($_FILES['logopath']) && $_FILES['logopath']['error'] === 0) {
            // Define upload directory and file path
            $uploadDir = './includes/images/';

            $allowedTypes = array('jpg', 'jpeg', 'png');

            $fileType = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));


            // Validate file type
            if (!in_array($fileType, $allowedTypes)) {
                $errors[] = "Invalid file type. Allowed file types are JPG, JPEG, and PNG.";
                echo "Invalid File";
                echo '<br>';
            }

            // Validate file size
            $maxFileSize = 5 * 1024 * 1024; // 5 MB
            if ($_FILES['logopath']['size'] > $maxFileSize) {
                $errors[] = "File size exceeds the limit. Maximum size is 5 MB.";
            }

            if (empty($errors)) {
                // Create a new client
                move_uploaded_file($_FILES['logopath']['tmp_name'], './includes/images/' . $logoPath);
                create_client($emailaddress, $firstName, $lastName, $phoneNumber, $logoPath, $extension);
                            // echo $emailaddress;
                            // echo '<br>';
                            // echo $firstName;
                            // echo '<br>';
                            // echo $lastName;
                            // echo '<br>';
                            // echo $phoneNumber;
                            // echo '<br>';
                            // echo $extension;
                            // echo '<br>';
                            // echo $logoPath;
                            // echo '<br>';
                // $_SESSION['message'] = "Client created successfully!";
                // redirect('clients.php');
            } else {
                $errors[] = "Error uploading file.";
            }
        } else {
            $errors[] = "Error uploading file.";
        }
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error . "<br>";
            }
    }           

?>
<form method="POST" action="clients.php" class="form-signin" enctype="multipart/form-data">
    <h1 class="h3 mb-3 font-weight-normal">Create Clients</h1>
    <div class="form-group">
        <?php echo display_form_clients(); ?>
    </div>

    <button class="btn btn-lg btn-primary btn-block" type="submit">Create</button>
</form>

<div class="col-lg-9">
<?php
    // Get the total number of records
    $page = isset($_GET['page']) ? $_GET['page'] : 1;

    $total_records = client_count();
    echo "<p>Total records: $total_records</p>";

    // Calculate the total number of pages
    $total_pages = ceil($total_records / RECORDS_PER_PAGE);

    // Calculate the start and end record numbers
    $start = ($page - 1) * RECORDS_PER_PAGE + 1;
    $end = $page * RECORDS_PER_PAGE;

    // Fetch records for the current page
    $records = get_paged_clients($page, $total_pages);

    // Call the display_table function to render the table
    display_table(
        array(
            "logopath" => "Logo",
            "firstname" => "First Name",
            "lastname" => "Last Name",
            "emailaddress" => "Email",
            "phonenumber" => "Phone Number",
            "extension" => "Extension"
        ),
        $records,
        $total_records,
        $page
    );
?>
</div>

<?php
if(isset($_SESSION['message'])) {
    echo $_SESSION['message'];
    unset($_SESSION['message']); // Clear the error message
}
if(isset($_SESSION['message'])) {
    echo $_SESSION['message'];
    unset($_SESSION['message']); // Clear the success message
}

require_once "./includes/footer.php";
?>