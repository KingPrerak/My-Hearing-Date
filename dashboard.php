<?php
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['advocate_id'])) {
    header("Location: login.php");
    exit;
}

$advocate_id = $_SESSION['advocate_id'];
$advocate_name = $_SESSION['advocate_name'];
$message = "";

// Handle new case form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_case'])) {
    $case_number = trim($_POST['case_number']);
    $case_name = trim($_POST['case_name']);
    $next_hearing_date = trim($_POST['next_hearing_date']);

    $stmt = $pdo->prepare("INSERT INTO cases (advocate_id, case_number, case_name, next_hearing_date) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$advocate_id, $case_number, $case_name, $next_hearing_date])) {
        $message = '<div class="alert alert-success mt-3">Case added successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger mt-3">Could not add case. Try again.</div>';
    }
}

// Fetch all cases for this advocate
$stmt = $pdo->prepare("SELECT * FROM cases WHERE advocate_id = ? ORDER BY next_hearing_date ASC");
$stmt->execute([$advocate_id]);
$cases = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MyHearingDate | Advocate Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Responsive meta -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: url('assets/images/background.png') no-repeat center center fixed;
            background-size: cover;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
        }
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">MyHearingDate</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <span class="navbar-text text-white me-3">
        Welcome, <?php echo htmlspecialchars($advocate_name); ?>
      </span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container my-4">

    <?php echo $message; ?>

    <div class="card mb-4 shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Add New Case</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="case_number" class="form-label">Case Number</label>
                    <input type="text" class="form-control" name="case_number" required>
                </div>
                <div class="mb-3">
                    <label for="case_name" class="form-label">Case Name</label>
                    <input type="text" class="form-control" name="case_name" required>
                </div>
                <div class="mb-3">
                    <label for="next_hearing_date" class="form-label">Next Hearing Date</label>
                    <input type="date" class="form-control" name="next_hearing_date" required>
                </div>
                <button type="submit" name="add_case" class="btn btn-primary">Add Case</button>
            </form>
        </div>
    </div>

    <div class="card shadow mb-5">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Your Case Logs</h5>
        </div>
        <div class="card-body">
            <?php if (count($cases) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Case Number</th>
                                <th>Case Name</th>
                                <th>Next Hearing Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cases as $case): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($case['case_number']); ?></td>
                                    <td><?php echo htmlspecialchars($case['case_name']); ?></td>
                                    <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($case['next_hearing_date']))); ?></td>
                                    <td>
                                        <a href="edit_case.php?id=<?php echo $case['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="delete_case.php?id=<?php echo $case['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No cases added yet.</p>
            <?php endif; ?>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
