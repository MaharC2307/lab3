<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($email) && !empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $userFile = 'users.json';
        $users = json_decode(file_get_contents($userFile), true) ?: [];
        $users[] = ['username' => $username, 'email' => $email, 'password' => $hashedPassword];
        file_put_contents($userFile, json_encode($users, JSON_PRETTY_PRINT));
        echo "<p style='color: green; text-align: center;'>Registration Successful!</p>";
    } else {
        echo "<p style='color: red; text-align: center;'>Please fill in all fields</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        h2 {
            color: #4CAF50;
            margin-bottom: 20px;
        }
        form {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
        }
        table {
            width: 100%;
            margin: 0 auto;
            border-collapse: collapse;
        }
        td, th {
            padding: 10px;
            border: 1px solid #ccc;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-top: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }
        button:hover {
            background-color: #45a049;
        }
        .register-link {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Register Yourself Here</h2>
    <form method="POST" action="register.php">
        <table>
            <tr>
                <th><label for="username">Username:</label></th>
                <td><input type="text" id="username" name="username" required></td>
            </tr>
            <tr>
                <th><label for="email">Email:</label></th>
                <td><input type="email" id="email" name="email" required></td>
            </tr>
            <tr>
                <th><label for="password">Password:</label></th>
                <td><input type="password" id="password" name="password" required></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <button type="submit">Register</button>
                </td>
            </tr>
        </table>
    </form>
    <p class="register-link">Already Registered? <a href="login.php">Click here</a>.</p>
</body>
</html>
