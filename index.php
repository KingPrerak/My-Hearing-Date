<?php
include 'config.php';

$message = "";
$results = [];

// Get all advocates for the dropdown
$stmt = $pdo->query("SELECT id, name FROM advocates ORDER BY name ASC");
$advocates = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $advocate_id = trim($_POST['advocate_id']);
    $case_number = trim($_POST['case_number']);
    $case_name = trim($_POST['case_name']);

    $query = "
        SELECT cases.*, advocates.name AS advocate_name 
        FROM cases 
        JOIN advocates ON cases.advocate_id = advocates.id
        WHERE advocates.id = ?
    ";

    $params = [$advocate_id];

    if (!empty($case_number)) {
        $query .= " AND cases.case_number = ?";
        $params[] = $case_number;
    }

    if (!empty($case_name)) {
        $query .= " AND cases.case_name LIKE ?";
        $params[] = "%" . $case_name . "%";
    }

    if (empty($case_number) && empty($case_name)) {
        $message = '<div class="alert alert-warning mt-3">Please enter either Case Number or Case Name.</div>';
    } else {
        $query .= " ORDER BY cases.next_hearing_date DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $results = $stmt->fetchAll();

        if (!$results) {
            $message = '<div class="alert alert-danger mt-3">No matching records found.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Case Status Portal</title>
    <!-- Bootstrap CSS -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        body {
            background: url('assets/images/background.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
        .card {
            background: rgba(255,255,255,0.95);
        }
        .select2-container .select2-selection--single {
            height: 38px;
            padding: 6px 12px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center my-5">
        <div class="col-12 col-md-8 col-lg-6">

            <div class="card shadow-lg">
                <div class="card-header bg-dark text-white text-center">
                    <h4>Check Your Case Status</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Select Your Advocate</label>
                            <select name="advocate_id" class="form-select select2" required>
                                <option value="">-- Select Advocate --</option>
                                <?php foreach ($advocates as $advocate): ?>
                                    <option value="<?php echo $advocate['id']; ?>">
                                        <?php echo htmlspecialchars($advocate['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Case Number</label>
                            <input type="text" name="case_number" class="form-control" placeholder="Optional">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">OR Case Name</label>
                            <input type="text" name="case_name" class="form-control" placeholder="Optional, partial name works">
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Check Status</button>
                    </form>

                    <?php echo $message; ?>

                    <?php if (count($results) > 0): ?>
                        <div class="mt-4 p-3 border rounded bg-success text-white">
                            <h5>Case Details:</h5>
                            <p><strong>Advocate:</strong> <?php echo htmlspecialchars($results[0]['advocate_name']); ?></p>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-light text-dark">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Case Number</th>
                                            <th>Case Name</th>
                                            <th>Next Hearing Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($results as $row): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['case_number']); ?></td>
                                                <td><?php echo htmlspecialchars($row['case_name']); ?></td>
                                                <td>
                                                    <?php
                                                        $formatted_date = date("d/m/Y", strtotime($row['next_hearing_date']));
                                                        echo htmlspecialchars($formatted_date);
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <div class="text-center mt-3">
                <a href="register.php" class="btn btn-outline-primary btn-sm me-2 bg-white text-primary border-primary">Register as Advocate</a>
                <a href="login.php" class="btn btn-outline-secondary btn-sm bg-white text-secondary border-secondary">Advocate Login</a>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery (needed for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select Your Advocate",
            allowClear: true,
            width: '100%'
        });
    });
</script>

</body>
</html>
