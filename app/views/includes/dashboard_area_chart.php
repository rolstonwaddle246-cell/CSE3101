<?php
require_once __DIR__ . '/../../../config/Database.php';
try {
    $pdo = Database::getInstance()->getConnection();

    // Query to get average scores per month
    $stmt = $pdo->prepare("
        SELECT MONTH(rc.created_at) AS month, AVG(rcd.marks_obtained) AS avg_score
        FROM report_card_details rcd
        JOIN report_cards rc ON rcd.report_id = rc.report_id
        GROUP BY MONTH(rc.created_at)
        ORDER BY MONTH(rc.created_at)
    ");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare array of 12 months with default 0
    $monthlyScores = array_fill(0, 12, 0);

    // Fill in the actual averages
    foreach ($rows as $row) {
        $monthIndex = $row['month'] - 1; // array index 0-11
        $monthlyScores[$monthIndex] = round($row['avg_score'], 2);
    }

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    $monthlyScores = array_fill(0, 12, 0);
}

// Optional: labels for chart
$months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('myAreaChart').getContext('2d');
const myAreaChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($months) ?>,
        datasets: [{
            label: "Average Score (%)",
            data: <?= json_encode($monthlyScores) ?>,
            backgroundColor: "rgba(78, 115, 223, 0.05)",
            borderColor: "rgba(78, 115, 223, 1)",
            fill: true,
            tension: 0.3,
            pointRadius: 3,
            pointHoverRadius: 4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return "Average Score: " + context.parsed.y + "%";
                    }
                }
            }
        },
        scales: {
            y: {
                min: 0,
                max: 100,
                ticks: { stepSize: 20, callback: v => v + "%" },
                grid: { drawBorder: false, color: "rgba(234, 236, 244, 1)" }
            },
            x: { grid: { drawBorder: false, color: "rgba(234, 236, 244, 0.5)" } }
        }
    }
});
</script>
