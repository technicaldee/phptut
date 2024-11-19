<?php
session_start();

// Database connection (update with your DB credentials)
$dsn = 'mysql:host=localhost;dbname=facebook;charset=utf8mb4';
$username = 'root';
$password = '';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle Signup
if (isset($_POST['signup'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $phone = $_POST['number'];

    if ($password !== $confirmPassword) {
        echo "Passwords do not match!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (email, password, first_name, last_name, dob, gender, phone) VALUES (:email, :password, :first_name, :last_name, :dob, :gender, :phone)");
        try {
            $stmt->execute([
                'email' => $email,
                'password' => $hashedPassword,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'dob' => $dob,
                'gender' => $gender,
                'phone' => $phone
            ]);
            echo "Signup successful! You can now log in.";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

// Handle Login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['name'];
        header("Location: welcome.php"); // Redirect to a welcome page or dashboard
        exit;
    } else {
        echo "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup & Login</title>
</head>

<body>
    <h1>Signup</h1>
    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="email">First Name:</label>
        <input type="text" id="first_name" name="first_name" required>
        <br>
        <label for="email">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required>
        <br>
        <label for="email">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required>
        <br>
        <label for="gender">Gender:</label>
        <input type="radio" id="gender_male" name="gender" value="male" required>
        <label for="gender_male">Male</label>
        <input type="radio" id="gender_female" name="gender" value="female" required>
        <label for="gender_female">Female</label>
        <br>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="number" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <br>
        <button type="submit" name="signup">Signup</button>
    </form>

    <h1>Login</h1>
    <form method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit" name="login">Login</button>
    </form>
</body>

</html>