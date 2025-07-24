<?php
include 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mobile = trim($_POST['mobile']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM advocates WHERE mobile = ?");
    $stmt->execute([$mobile]);
    $advocate = $stmt->fetch();

    if ($advocate && password_verify($password, $advocate['password'])) {
        $_SESSION['advocate_id'] = $advocate['id'];
        $_SESSION['advocate_name'] = $advocate['name'];
        header("Location: dashboard.php");
        exit;
    } else {
        $message = '<div class="alert alert-danger">Invalid mobile or password.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Advocate Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">

            <div class="card shadow-lg">
                <div class="card-header text-center bg-success text-white">
                    <h4>Advocate Login</h4>
                </div>
                <div class="card-body">
                    <?php echo $message; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" name="mobile" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Login</button>
                    </form>
                </div>
            </div>

            <p class="text-center mt-3">
                New advocate? <a href="register.php">Register here</a>.
            </p>

        </div>
    </div>
</div>

</body>
</html>
