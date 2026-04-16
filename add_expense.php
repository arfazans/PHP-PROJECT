<?php
session_start();

$message = "";
$msgType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date        = trim($_POST["date"]);
    $category    = trim($_POST["category"]);
    $amount      = trim($_POST["amount"]);
    $description = trim($_POST["description"]);

    if ($date == "" || $category == "" || $amount == "" || !is_numeric($amount)) {
        $message = "Please fill all required fields with valid data.";
        $msgType = "error";
    } else {
        $expense = [
            "id"          => time(),
            "date"        => $date,
            "category"    => $category,
            "amount"      => (float)$amount,
            "description" => $description
        ];

        $file = "expenses.json";
        $expenses = [];

        if (file_exists($file)) {
            $expenses = json_decode(file_get_contents($file), true);
        }

        $expenses[] = $expense;
        file_put_contents($file, json_encode($expenses, JSON_PRETTY_PRINT));

        $message = "Expense added successfully!";
        $msgType = "success";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Expense</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header><h1>💰 Expense Tracker</h1></header>

<nav>
    <a href="index.php">Home</a>
    <a href="add_expense.php" class="active">Add Expense</a>
    <a href="view_expense.php">View Expenses</a>
    <a href="summary.php">Summary</a>
</nav>

<div class="container">
    <h2>Add New Expense</h2>

    <?php if ($message != ""): ?>
        <div class="alert alert-<?php echo $msgType; ?>"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" action="add_expense.php">

        <div class="form-group">
            <label>Date *</label>
            <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
        </div>

        <div class="form-group">
            <label>Category *</label>
            <select name="category" required>
                <option value="">-- Select --</option>
                <option value="Food">Food</option>
                <option value="Travel">Travel</option>
                <option value="Bills">Bills</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label>Amount (₹) *</label>
            <input type="number" name="amount" step="0.01" min="0" placeholder="e.g. 250" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" placeholder="Optional note..."></textarea>
        </div>

        <button type="submit">Save Expense</button>
    </form>
</div>

</body>
</html>