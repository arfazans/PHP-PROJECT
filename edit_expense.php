<?php
$pageTitle = "Edit Expense";

$file = "expenses.json";
$expenses = [];
$expenseToEdit = null;
$message = "";
$msgType = "";

if (file_exists($file)) {
    $content = file_get_contents($file);
    if (!empty($content)) {
        $expenses = json_decode($content, true);
    }
}

// Handle fetch
if (isset($_GET['id'])) {
    $editId = (int)$_GET['id'];
    foreach ($expenses as $exp) {
        if ($exp['id'] == $editId) {
            $expenseToEdit = $exp;
            break;
        }
    }
    if (!$expenseToEdit) {
        $message = "Expense not found.";
        $msgType = "error";
    }
}

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $editId      = (int)$_POST["id"];
    $date        = trim($_POST["date"]);
    $category    = trim($_POST["category"]);
    $amount      = trim($_POST["amount"]);
    $description = trim($_POST["description"]);

    if ($date == "" || $category == "" || $amount == "" || !is_numeric($amount)) {
        $message = "Please fill all required fields with valid data.";
        $msgType = "error";
    } else {
        $updated = false;
        foreach ($expenses as $key => $exp) {
            if ($exp['id'] == $editId) {
                $expenses[$key]['date'] = $date;
                $expenses[$key]['category'] = $category;
                $expenses[$key]['amount'] = (float)$amount;
                $expenses[$key]['description'] = $description;
                $expenseToEdit = $expenses[$key];
                $updated = true;
                break;
            }
        }
        
        if ($updated) {
            file_put_contents($file, json_encode($expenses, JSON_PRETTY_PRINT));
            $message = "Expense updated successfully!";
            $msgType = "success";
        }
    }
}

include 'includes/header.php';
?>

<h2>Edit Expense</h2>

<?php if ($message != ""): ?>
    <div class="alert alert-<?php echo $msgType; ?>">
        <?php echo ($msgType == 'success') ? '✅' : '❌'; ?> &nbsp; <?php echo $message; ?>
    </div>
<?php endif; ?>

<?php if ($expenseToEdit): ?>
<form method="POST" action="edit_expense.php?id=<?php echo $expenseToEdit['id']; ?>" style="background:#fff; padding:30px; border-radius:12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
    <input type="hidden" name="id" value="<?php echo $expenseToEdit['id']; ?>">
    
    <div class="form-group">
        <label>Date *</label>
        <input type="date" name="date" value="<?php echo htmlspecialchars($expenseToEdit['date']); ?>" required>
    </div>

    <div class="form-group">
        <label>Category *</label>
        <select name="category" required>
            <option value="">-- Select --</option>
            <option value="Food" <?php echo ($expenseToEdit['category'] == 'Food') ? 'selected' : ''; ?>>Food</option>
            <option value="Travel" <?php echo ($expenseToEdit['category'] == 'Travel') ? 'selected' : ''; ?>>Travel</option>
            <option value="Bills" <?php echo ($expenseToEdit['category'] == 'Bills') ? 'selected' : ''; ?>>Bills</option>
            <option value="Shopping" <?php echo ($expenseToEdit['category'] == 'Shopping') ? 'selected' : ''; ?>>Shopping</option>
            <option value="Other" <?php echo ($expenseToEdit['category'] == 'Other') ? 'selected' : ''; ?>>Other</option>
        </select>
    </div>

    <div class="form-group">
        <label>Amount (₹) *</label>
        <input type="number" name="amount" step="0.01" min="0" value="<?php echo htmlspecialchars($expenseToEdit['amount']); ?>" required>
    </div>

    <div class="form-group">
        <label>Description</label>
        <textarea name="description"><?php echo htmlspecialchars($expenseToEdit['description']); ?></textarea>
    </div>

    <button type="submit">Update Expense</button>
    <a href="view_expense.php" style="display:inline-block; margin-left:15px; color:#555; text-decoration:none; font-weight:500;">Cancel</a>
</form>
<?php else: ?>
    <div class="empty-msg">
        <a href="view_expense.php">Return to view expenses</a>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
