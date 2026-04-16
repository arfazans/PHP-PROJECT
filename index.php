<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expense Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header><h1>💰 Expense Tracker</h1></header>

<nav>
    <a href="index.php" class="active">Home</a>
    <a href="add_expense.php">Add Expense</a>
    <a href="view_expense.php">View Expenses</a>
    <a href="summary.php">Summary</a>
</nav>

<div class="container">
    <h2>Welcome</h2>
    <p style="margin-bottom:24px; font-size:15px;">
        Use this tool to track your daily expenses by category.
    </p>

    <div class="home-links">
        <a href="add_expense.php">➕ Add Expense</a>
        <a href="view_expense.php">📋 View Expenses</a>
        <a href="summary.php">📊 Summary</a>
    </div>
</div>

</body>
</html>