<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Set default title if not provided
$pageTitle = isset($pageTitle) ? $pageTitle : "Expense Tracker";
// Identify current active page
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <!-- Link to our stylesheet -->
    <link rel="stylesheet" href="style.css">
    <!-- Include Chart.js for summary plotting -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<header>
    <div class="header-container">
        <h1>💰 Expense Tracker</h1>
    </div>
</header>

<nav>
    <div class="nav-container">
        <a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">🏠 Home</a>
        <a href="add_expense.php" class="<?php echo ($current_page == 'add_expense.php') ? 'active' : ''; ?>">➕ Add Expense</a>
        <a href="view_expense.php" class="<?php echo ($current_page == 'view_expense.php' || $current_page == 'edit_expense.php') ? 'active' : ''; ?>">📋 View Expenses</a>
        <a href="summary.php" class="<?php echo ($current_page == 'summary.php') ? 'active' : ''; ?>">📊 Summary</a>
    </div>
</nav>

<div class="container">
