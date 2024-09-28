<?php
session_start();
$error = '';

$username = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    if (!empty($username) && !empty($password)) {
        $userFile = 'users.json';
        $users = json_decode(file_get_contents($userFile), true) ?: [];

        $userFound = false;
        foreach ($users as $user) {
            if ($user['username'] === $username && password_verify($password, $user['password'])) {
                $userFound = true;
                $_SESSION['username'] = $username;

                if ($remember) {
                    setcookie('username', $username, time() + (86400 * 7), "/");
                } else {
                    setcookie('username', '', time() - 3600, "/");
                }

                header('Location: job_application.php');
                exit();
            }
        }

        if (!$userFound) {
            $error = "Username or Password is invalid!!";
        }
    } else {
        $error = "Some fields are empty!!!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
        .error-message {
            color: red;
            text-align: center;
            margin: 10px 0;
        }
        .register-link {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Login Here</h2>
    <?php if ($error): ?>
        <p class="error-message"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <table>
            <tr>
                <th><label for="username">Username:</label></th>
                <td><input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required></td>
            </tr>
            <tr>
                <th><label for="password">Password:</label></th>
                <td><input type="password" id="password" name="password" required></td>
            </tr>
            <tr>
                <td colspan="2">
                    <label>
                        <input type="checkbox" name="remember" <?php echo isset($_COOKIE['username']) ? 'checked' : ''; ?>>
                        Remember Me
                    </label>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit">Login</button>
                </td>
            </tr>
        </table>
    </form>
    <p class="register-link">Don't have an account? <a href="register.php">Register here</a>.</p>
</body>
</html>
