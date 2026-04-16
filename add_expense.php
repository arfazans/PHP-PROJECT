<?php
$pageTitle = "Add Expense";

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
            $content = file_get_contents($file);
            if (!empty($content)) {
                $expenses = json_decode($content, true);
            }
        }

        $expenses[] = $expense;
        file_put_contents($file, json_encode($expenses, JSON_PRETTY_PRINT));

        $message = "Expense added successfully!";
        $msgType = "success";
    }
}
include 'includes/header.php';
?>

<h2>Add New Expense</h2>

<?php if ($message != ""): ?>
    <div class="alert alert-<?php echo $msgType; ?>">
        <?php echo ($msgType == 'success') ? '✅' : '❌'; ?> &nbsp; <?php echo $message; ?>
    </div>
<?php endif; ?>

<form method="POST" action="add_expense.php" style="background:#fff; padding:30px; border-radius:12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
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
            <option value="Shopping">Shopping</option>
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

<?php include 'includes/footer.php'; ?>