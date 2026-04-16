<?php
session_start();

$file     = "expenses.json";
$expenses = [];

if (file_exists($file)) {
    $expenses = json_decode(file_get_contents($file), true);
}

$total      = 0;
$categories = ["Food" => 0, "Travel" => 0, "Bills" => 0, "Other" => 0];

foreach ($expenses as $exp) {
    $total += $exp["amount"];

    if (isset($categories[$exp["category"]])) {
        $categories[$exp["category"]] += $exp["amount"];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Summary</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header><h1>💰 Expense Tracker</h1></header>

<nav>
    <a href="index.php">Home</a>
    <a href="add_expense.php">Add Expense</a>
    <a href="view_expense.php">View Expenses</a>
    <a href="summary.php" class="active">Summary</a>
</nav>

<div class="container">
    <h2>Expense Summary</h2>

    <?php if (count($expenses) == 0): ?>
        <p class="empty-msg">No data to summarize yet. <a href="add_expense.php">Add an expense.</a></p>
    <?php else: ?>

        <div class="summary-grid">
            <div class="card">
                <div class="label">Total Expenses</div>
                <div class="amount">₹<?php echo number_format($total, 2); ?></div>
            </div>
            <div class="card">
                <div class="label">No. of Entries</div>
                <div class="amount"><?php echo count($expenses); ?></div>
            </div>
        </div>

        <h2>Category-wise Breakdown</h2>

        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Total (₹)</th>
                    <th>% of Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat => $amt): ?>
                <tr>
                    <td><?php echo $cat; ?></td>
                    <td><?php echo number_format($amt, 2); ?></td>
                    <td>
                        <?php
                        if ($total > 0) {
                            echo number_format(($amt / $total) * 100, 1) . "%";
                        } else {
                            echo "0%";
                        }
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>

                <tr class="total-row">
                    <td>Total</td>
                    <td>₹<?php echo number_format($total, 2); ?></td>
                    <td>100%</td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>