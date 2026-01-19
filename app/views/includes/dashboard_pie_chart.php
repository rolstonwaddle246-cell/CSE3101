<?php
require_once __DIR__ . '/../../../config/Database.php';
$pdo = Database::getInstance()->getConnection();

$gradeLabels = [];
$gradeCounts = [];
$totalStudents = 0;

$stmt = $pdo->query("
    SELECT g.grade_name, COUNT(*) AS count
    FROM students s
    JOIN grades g ON s.grade_id = g.grade_id
    GROUP BY g.grade_name
    ORDER BY g.level_order
");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $count = (int)$row['count'];
    $gradeLabels[] = "{$row['grade_name']} - {$row['count']} student" . ($row['count'] > 1 ? 's' : '');
    $gradeCounts[] = (int)$row['count'];
    $totalStudents += $count;
}

$gradeLabelsJSON = json_encode($gradeLabels);
$gradeCountsJSON = json_encode($gradeCounts);
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxPie = document.getElementById('myPieChart').getContext('2d');
const myPieChart = new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: <?= json_encode($gradeLabels) ?>,
        datasets: [{
            data: <?= json_encode($gradeCounts) ?>,
            backgroundColor: ['#4e73df','#1cc88a','#36b9cc','#f6c23e','#e74a3b'],
        }]
    },
    options: { responsive: true }
});
</script>