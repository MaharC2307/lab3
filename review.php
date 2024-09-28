<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit();
}

$successmsg = '';

if (isset($_POST['submit'])) {
    $applicationData = [
        'Full Name' => $_SESSION['name'] ?? '',
        'Email' => $_SESSION['email'] ?? '',
        'Phone Number' => $_SESSION['number'] ?? '',
        'Highest Degree' => $_SESSION['h_degree'] ?? '',
        'Field of Study' => $_SESSION['f_study'] ?? '',
        'Institution Name' => $_SESSION['name_institution'] ?? '',
        'Year of Graduation' => $_SESSION['year_graduation'] ?? '',
        'Previous Job Title' => $_SESSION['p_j_title'] ?? '',
        'Company Name' => $_SESSION['c_name'] ?? '',
        'Years of Experience' => $_SESSION['y_exp'] ?? '',
        'Key Responsibilities' => $_SESSION['key_respo'] ?? '',
    ];

    $filename = 'applications.json';
    $applications = [];

    if (file_exists($filename)) {
        $applications = json_decode(file_get_contents($filename), true) ?: [];
    }

    $applications[] = $applicationData;

    file_put_contents($filename, json_encode($applications, JSON_PRETTY_PRINT));

    $successmsg = "Your application has been submitted successfully!";
    $confirmationEmail = "A confirmation email has been sent to " . $_SESSION['email'] . " with the following details:\n" . json_encode($applicationData, JSON_PRETTY_PRINT);

    error_log($confirmationEmail);
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    if (isset($_COOKIE['remember_me'])) {
        unset($_COOKIE['remember_me']);
        setcookie('remember_me', '', time() - 3600, '/');
    }

    header("location:login.php");
    exit();
}

function display_data($label, $data) {
    return "<tr><th>{$label}:</th><td>{$data}</td></tr>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #4CAF50;
        }
        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 80%;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .message {
            text-align: center;
            color: green;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Review Your Application</h2>

<?php if ($successmsg): ?>
    <p class="message"><?php echo $successmsg; ?></p>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>Field</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
        <?php
        echo display_data("Full Name", $_SESSION['name'] ?? '');
        echo display_data("Email", $_SESSION['email'] ?? '');
        echo display_data("Phone Number", $_SESSION['number'] ?? '');
        echo display_data("Highest Degree", $_SESSION['h_degree'] ?? '');
        echo display_data("Field of Study", $_SESSION['f_study'] ?? '');
        echo display_data("Institution Name", $_SESSION['name_institution'] ?? '');
        echo display_data("Year of Graduation", $_SESSION['year_graduation'] ?? '');
        echo display_data("Previous Job Title", $_SESSION['p_j_title'] ?? '');
        echo display_data("Company Name", $_SESSION['c_name'] ?? '');
        echo display_data("Years of Experience", $_SESSION['y_exp'] ?? '');
        echo display_data("Key Responsibilities", nl2br($_SESSION['key_respo'] ?? ''));
        ?>
    </tbody>
</table>

<div class="button-container">
    <form method="POST" action="job_application.php?step=1" style="display: inline;">
        <button type="submit">Edit Personal Information</button>
    </form>
    <form method="POST" action="job_application.php?step=2" style="display: inline;">
        <button type="submit">Edit Education Information</button>
    </form>
    <form method="POST" action="job_application.php?step=3" style="display: inline;">
        <button type="submit">Edit Work Experience</button>
    </form>
    <form method="POST" action="review.php" style="display: inline;">
        <button type="submit" name="submit">Submit Application</button>
    </form>
    <form method="POST" action="review.php" style="display: inline;">
        <button type="submit" name="logout">Logout</button>
    </form>
</div>
</body>
</html>
