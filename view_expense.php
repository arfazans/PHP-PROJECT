<?php
session_start();

$file     = "expenses.json";
$expenses = [];
$message  = "";

if (isset($_GET["delete"])) {
    $deleteId = (int)$_GET["delete"];

    if (file_exists($file)) {
        $expenses = json_decode(file_get_contents($file), true);

        foreach ($expenses as $key => $exp) {
            if ($exp["id"] == $deleteId) {
                unset($expenses[$key]);
                break;
            }
        }

        $expenses = array_values($expenses);
        file_put_contents($file, json_encode($expenses, JSON_PRETTY_PRINT));
        $message = "Expense deleted.";
    }
}

if (file_exists($file)) {
    $expenses = json_decode(file_get_contents($file), true);
}

$total = 0;
foreach ($expenses as $exp) {
    $total += $exp["amount"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Expenses</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header><h1>💰 Expense Tracker</h1></header>

<nav>
    <a href="index.php">Home</a>
    <a href="add_expense.php">Add Expense</a>
    <a href="view_expense.php" class="active">View Expenses</a>
    <a href="summary.php">Summary</a>
</nav>

<div class="container">
    <h2>All Expenses</h2>

    <?php if ($message != ""): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (count($expenses) == 0): ?>
        <p class="empty-msg">No expenses recorded yet. <a href="add_expense.php">Add one?</a></p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Amount (₹)</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($expenses as $exp): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($exp["date"]); ?></td>
                    <td><?php echo htmlspecialchars($exp["category"]); ?></td>
                    <td><?php echo number_format($exp["amount"], 2); ?></td>
                    <td><?php echo htmlspecialchars($exp["description"]); ?></td>
                    <td>
                        <a class="delete-btn"
                           href="view_expense.php?delete=<?php echo $exp['id']; ?>"
                           onclick="return confirm('Delete this expense?')">
                           Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>

                <tr class="total-row">
                    <td colspan="3">Total</td>
                    <td>₹<?php echo number_format($total, 2); ?></td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
