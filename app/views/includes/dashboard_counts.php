<?php
// dashboard_counts.php
$pdo = Database::getInstance()->getConnection();

try {
    // Students count
    $stmt = $pdo->query("SELECT COUNT(*) AS total_students FROM students");
    $totalStudentsCount = $stmt->fetch(PDO::FETCH_ASSOC)['total_students'];

    // Teachers count
    $stmt = $pdo->query("SELECT COUNT(*) AS total_teachers FROM teachers");
    $totalTeachersCount = $stmt->fetch(PDO::FETCH_ASSOC)['total_teachers'];

    // Parents count
    $stmt = $pdo->query("SELECT COUNT(*) AS total_parents FROM parents");
    $totalParentsCount = $stmt->fetch(PDO::FETCH_ASSOC)['total_parents'];

    // Grades count
    $stmt = $pdo->query("
        SELECT 
            MIN(CAST(SUBSTRING_INDEX(grade_name, ' ', -1) AS UNSIGNED)) AS min_grade,
            MAX(CAST(SUBSTRING_INDEX(grade_name, ' ', -1) AS UNSIGNED)) AS max_grade
        FROM grades
    ");
    $gradeRange = $stmt->fetch(PDO::FETCH_ASSOC);

    // Create display string like "1-4"
    $gradesDisplay = $gradeRange['min_grade'] . '-' . $gradeRange['max_grade'];

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>
