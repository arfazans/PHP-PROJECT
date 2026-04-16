<?php
$pageTitle = "View Expenses";

$file     = "expenses.json";
$expenses = [];
$message  = "";

if (isset($_GET["delete"])) {
    $deleteId = (int)$_GET["delete"];

    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (!empty($content)) {
            $expenses = json_decode($content, true);
        }

        foreach ($expenses as $key => $exp) {
            if ($exp["id"] == $deleteId) {
                unset($expenses[$key]);
                break;
            }
        }

        $expenses = array_values($expenses);
        file_put_contents($file, json_encode($expenses, JSON_PRETTY_PRINT));
        $message = "Expense deleted successfully.";
    }
}

if (file_exists($file)) {
    $content = file_get_contents($file);
    if (!empty($content)) {
        $expenses = json_decode($content, true);
    }
}

// Sort by date descending
usort($expenses, function($a, $b) {
    return strtotime($b['date']) <=> strtotime($a['date']);
});

$total = 0;
foreach ($expenses as $exp) {
    if(isset($exp["amount"])) $total += $exp["amount"];
}

include 'includes/header.php';
?>

<h2>All Expenses</h2>

<?php if ($message != ""): ?>
    <div class="alert alert-success">✅ &nbsp; <?php echo $message; ?></div>
<?php endif; ?>

<?php if (count($expenses) == 0): ?>
    <div class="empty-msg">No expenses recorded yet. <a href="add_expense.php">Add one?</a></div>
<?php else: ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Amount (₹)</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($expenses as $exp): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($exp["date"]); ?></td>
                    <td><?php echo htmlspecialchars($exp["category"]); ?></td>
                    <td style="font-weight: 600;"><?php echo number_format($exp["amount"], 2); ?></td>
                    <td><?php echo htmlspecialchars($exp["description"]); ?></td>
                    <td>
                        <div class="action-links">
                            <a class="edit-btn" href="edit_expense.php?id=<?php echo $exp['id']; ?>">✏️ Edit</a>
                            <a class="delete-btn"
                               href="view_expense.php?delete=<?php echo $exp['id']; ?>"
                               onclick="return confirm('Are you sure you want to delete this expense?')">
                               🗑️ Delete
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>

                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Total</td>
                    <td>₹<?php echo number_format($total, 2); ?></td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
