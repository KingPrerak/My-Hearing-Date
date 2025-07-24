<?php
include 'config.php';

$message = "";

// Developer PIN (hardcoded)
$developer_pin = "@Prerak45";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dev_pin = trim($_POST['developer_pin']);
    $name = trim($_POST['name']);
    $mobile = trim($_POST['mobile']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic checks
    if ($dev_pin !== $developer_pin) {
        $message = '<div class="alert alert-danger">Invalid Developer PIN!</div>';
    } else {
        // Check if mobile already exists
        $stmt = $pdo->prepare("SELECT * FROM advocates WHERE mobile = ?");
        $stmt->execute([$mobile]);
        if ($stmt->rowCount() > 0) {
            $message = '<div class="alert alert-warning">Mobile number already registered.</div>';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO advocates (name, mobile, email, password) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$name, $mobile, $email, $hashed_password])) {
                $message = '<div class="alert alert-success">Registered successfully! <a href="login.php">Login here</a>.</div>';
            } else {
                $message = '<div class="alert alert-danger">Something went wrong. Please try again.</div>';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register as Advocate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">

            <div class="card shadow-lg">
                <div class="card-header text-center bg-primary text-white">
                    <h4>Register as Advocate</h4>
                </div>
                <div class="card-body">
                    <?php echo $message; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="developer_pin" class="form-label">Developer PIN</label>
                            <input type="text" class="form-control" name="developer_pin" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Advocate Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" name="mobile" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Create Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                </div>
            </div>

            <p class="text-center mt-3">
                Already registered? <a href="login.php">Login here</a>.
            </p>

        </div>
    </div>
</div>

</body>
</html>
