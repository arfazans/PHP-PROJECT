<?php
$pageTitle = "Summary";
include 'includes/header.php';

$file     = "expenses.json";
$expenses = [];

if (file_exists($file)) {
    $content = file_get_contents($file);
    if (!empty($content)) {
        $expenses = json_decode($content, true);
    }
}

$totalAllTime = 0;
$totalThisMonth = 0;
$categories = [];

$currentMonth = date('Y-m');

foreach ($expenses as $exp) {
    if (!isset($exp["amount"])) continue;
    
    $amt = $exp["amount"];
    $totalAllTime += $amt;
    
    if (strpos($exp["date"], $currentMonth) === 0) {
        $totalThisMonth += $amt;
    }

    $cat = $exp["category"];
    if (!isset($categories[$cat])) {
        $categories[$cat] = 0;
    }
    $categories[$cat] += $amt;
}

// Prepare Data for Chart
$chartLabels = array_keys($categories);
$chartData = array_values($categories);
?>

<h2>Expense Summary</h2>

<?php if (count($expenses) == 0): ?>
    <div class="empty-msg">No data to summarize yet. <a href="add_expense.php">Add an expense.</a></div>
<?php else: ?>

    <div class="summary-grid">
        <div class="card highlight">
            <div class="label">Total (All Time)</div>
            <div class="amount">₹<?php echo number_format($totalAllTime, 2); ?></div>
        </div>
        <div class="card">
            <div class="label">This Month</div>
            <div class="amount">₹<?php echo number_format($totalThisMonth, 2); ?></div>
        </div>
        <div class="card">
            <div class="label">Total Entries</div>
            <div class="amount"><?php echo count($expenses); ?></div>
        </div>
    </div>

    <div style="display:flex; flex-wrap:wrap; gap:30px; margin-top:20px;">
        
        <!-- Category Table -->
        <div style="flex:1; min-width:300px;">
            <h2 style="font-size:18px; border-bottom:none; margin-bottom:15px; padding-bottom:0;">Category Breakdown</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Total (₹)</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat => $amt): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cat); ?></td>
                            <td style="font-weight: 600;"><?php echo number_format($amt, 2); ?></td>
                            <td>
                                <?php
                                if ($totalAllTime > 0) {
                                    echo number_format(($amt / $totalAllTime) * 100, 1) . "%";
                                } else {
                                    echo "0%";
                                }
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <tr class="total-row">
                            <td>Total</td>
                            <td>₹<?php echo number_format($totalAllTime, 2); ?></td>
                            <td>100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Chart.js Canvas -->
        <div style="flex:1; min-width:300px;">
            <div class="chart-container" style="margin-bottom:0; max-height: 400px; padding: 20px;">
                <canvas id="expenseChart" width="300" height="300"></canvas>
            </div>
        </div>

    </div>

    <!-- Render Chart -->
    <script>
        const ctx = document.getElementById('expenseChart').getContext('2d');
        const labels = <?php echo json_encode($chartLabels); ?>;
        const data = <?php echo json_encode($chartData); ?>;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                        '#2a5298', '#e74c3c', '#f1c40f', '#2ecc71', '#9b59b6', '#34495e', '#e67e22'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: { family: "'Inter', sans-serif" }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    </script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>