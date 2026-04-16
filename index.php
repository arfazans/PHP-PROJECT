<?php
$pageTitle = "Home - Dashboard";
include 'includes/header.php';

$file = "expenses.json";
$expenses = [];
if (file_exists($file)) {
    $content = file_get_contents($file);
    if (!empty($content)) {
        $expenses = json_decode($content, true);
    }
}

$total = 0;
$recent = [];
if (is_array($expenses) && !empty($expenses)) {
    foreach ($expenses as $exp) {
        if(isset($exp["amount"])) {
            $total += $exp["amount"];
        }
    }
    // Sort array by id (timestamp) descending to get recent first
    usort($expenses, function($a, $b) {
        return $b['id'] <=> $a['id'];
    });
    $recent = array_slice($expenses, 0, 3);
}
?>

<h2>Dashboard Overview</h2>

<div class="summary-grid">
    <div class="card highlight">
        <div class="label">Total Spent</div>
        <div class="amount">₹<?php echo number_format($total, 2); ?></div>
    </div>
    <div class="card">
        <div class="label">Total Entries</div>
        <div class="amount"><?php echo is_array($expenses) ? count($expenses) : 0; ?></div>
    </div>
</div>

<h2>Recent Expenses</h2>
<?php if (count($recent) == 0): ?>
    <div class="empty-msg">No expenses found yet. <a href="add_expense.php">Add one?</a></div>
<?php else: ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent as $exp): ?>
                <tr>
                    <td><?php echo htmlspecialchars($exp["date"]); ?></td>
                    <td><?php echo htmlspecialchars($exp["category"]); ?></td>
                    <td style="font-weight: 600;">₹<?php echo number_format($exp["amount"], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<div class="home-links" style="margin-top: 40px;">
    <a href="add_expense.php">➕ Quick Add Expense</a>
    <a href="view_expense.php">📋 See All</a>
</div>

<?php include 'includes/footer.php'; ?>