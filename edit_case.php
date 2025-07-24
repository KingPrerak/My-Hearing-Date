<?php
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['advocate_id'])) {
    header("Location: login.php");
    exit;
}

$advocate_id = $_SESSION['advocate_id'];
$message = "";

// Get case ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the case
$stmt = $pdo->prepare("SELECT * FROM cases WHERE id = ? AND advocate_id = ?");
$stmt->execute([$id, $advocate_id]);
$case = $stmt->fetch();

if (!$case) {
    die("Case not found or unauthorized access.");
}

// Update logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $case_number = trim($_POST['case_number']);
    $case_name = trim($_POST['case_name']);
    $next_hearing_date = trim($_POST['next_hearing_date']);

    $stmt = $pdo->prepare("UPDATE cases SET case_number = ?, case_name = ?, next_hearing_date = ? WHERE id = ? AND advocate_id = ?");
    if ($stmt->execute([$case_number, $case_name, $next_hearing_date, $id, $advocate_id])) {
        header("Location: dashboard.php");
        exit;
    } else {
        $message = '<div class="alert alert-danger">Could not update case. Try again.</div>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Case</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h5>Edit Case</h5>
                </div>
                <div class="card-body">
                    <?php echo $message; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Case Number</label>
                            <input type="text" name="case_number" class="form-control" value="<?php echo htmlspecialchars($case['case_number']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Case Name</label>
                            <input type="text" name="case_name" class="form-control" value="<?php echo htmlspecialchars($case['case_name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Next Hearing Date</label>
                            <input type="date" name="next_hearing_date" class="form-control" value="<?php echo htmlspecialchars($case['next_hearing_date']); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-warning">Update</button>
                        <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
