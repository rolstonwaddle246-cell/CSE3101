<?php
// dashboard.php — used for both admin & teacher
if (session_status() === PHP_SESSION_NONE) session_start();

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    header("Location: index.php?action=login");
    exit();
}

// Fetch session info
$userId   = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? '';
$role     = strtolower($_SESSION['role_name'] ?? '');

// Redirect teacher/admin to correct page if this file is accessed incorrectly
if ($role === 'admin' && ($_GET['action'] ?? '') !== 'admin_dashboard') {
    header("Location: index.php?action=admin_dashboard");
    exit();
} elseif ($role === 'teacher' && ($_GET['action'] ?? '') !== 'teacher_dashboard') {
    header("Location: index.php?action=teacher_dashboard");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
        $pageTitle = "SMS - Dashboard"; 
        include 'includes/header.php'; 
        require_once __DIR__ . '/includes/dashboard_counts.php';

        require_once __DIR__ . '/../controllers/SettingController.php';
        $settingController = new SettingController();
        // Fetch current values from DB
        $schoolYear = $settingController->get('school_year') ?? '2025/2026';
        $activeTerm = $settingController->get('active_term') ?? 'Term 2';

        //SLIDERS
        require_once __DIR__ . '/../controllers/SyllabusProgressController.php';
        $syllabusController = new SyllabusProgressController();
        $defaultValues = [
            'math' => 50,
            'english' => 40,
            'science' => 60,
            'social' => 80
        ];

        // Fetch values from DB or use default
        $sliderValues = [];
        foreach ($defaultValues as $subject => $default) {
            $value = $syllabusController->get($subject);
            
            if ($value === null) {
                // No row exists in DB yet, insert default
                $syllabusController->update($subject, $default);
                $value = $default;
            }
            
            $sliderValues[$subject] = $value;
        }
    ?>

<style>

.editable-text {
    position: relative;
}
.editable-text:hover {
    cursor: pointer;
    text-decoration: underline dotted;
}
.editable-text[contenteditable="true"] {
    cursor: text;
    background: #f8f9fc;
    outline: none;
}
/* Hover hint */
.editable-text:hover::after {
    content: attr(data-hint);
    position: absolute;
    bottom: -22px;
    left: 0;
    font-size: 11px;
    color: #6c757d;
    white-space: nowrap;
}

.syllabus-range {
    width: 100%;
    margin-top: -12px;
    opacity: 0;
    cursor: pointer;
}
/* Make the track thicker */
.syllabus-range {
    appearance: none;           /* standard property */
    -webkit-appearance: none;   /* Chrome, Safari */
    -moz-appearance: none;      /* Firefox */
    width: 100%;
    height: 16px;               /* thicker track */
    background: #e9ecef;
    border-radius: 8px;
    outline: none;
    margin-top: -12px;
    cursor: pointer;
}
/* Slider handle for WebKit browsers (Chrome, Safari) */
.syllabus-range::-webkit-slider-thumb {
    appearance: none;           
    -webkit-appearance: none;
    width: 28px;  
    height: 28px;
    background: #4e73df;
    border-radius: 50%;
    border: 2px solid #fff;
    cursor: pointer;
    margin-top: -6px; /* centers handle vertically */
}
/* Slider handle for Firefox */
.syllabus-range::-moz-range-thumb {
    appearance: none;
    -moz-appearance: none;
    width: 28px;
    height: 28px;
    background: #4e73df;
    border-radius: 50%;
    border: 2px solid #fff;
    cursor: pointer;
}
/* Optional hover hint */
.syllabus-range:hover::after {
    content: "Slide anywhere on the bar";
    position: absolute;
    top: -22px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 11px;
    color: #6c757d;
    white-space: nowrap;
}

</style>
</head>


<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include 'includes/sidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">


                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <?php include 'includes/navbar.php'; ?>
                </nav>
                <!-- End of Topbar -->

                
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Admin Dashboard</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Students Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2 card-bg card-bg-overlay" style="background-image: url('assets/images/blue.jpg');">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Students</div>
                                            <div class="h5 mb-0 font-weight-bold text-white-800"><?= number_format($totalStudentsCount) ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Teachers Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2 card-bg card-bg-overlay" style="background-image: url('assets/images/green.jpg');">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Teachers</div>
                                            <div class="h5 mb-0 font-weight-bold text-white-800"><?= number_format($totalTeachersCount) ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Parents Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2 card-bg card-bg-overlay" style="background-image: url('assets/images/lightb.png');">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Parents</div>
                                            <div class="h5 mb-0 font-weight-bold text-white-800"><?= number_format($totalParentsCount) ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Grades Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2 card-bg card-bg-overlay" style="background-image: url('assets/images/yellow.png');">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Grades</div>
                                            <div class="h5 mb-0 font-weight-bold text-white-800"><?= htmlspecialchars($gradesDisplay) ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Academic Performance</h6>
                                </div>

                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                        <?php require_once __DIR__ . '/includes/dashboard_area_chart.php'; ?>
        
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Students</h6>
                                </div>

                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie" style="display: flex; justify-content: center; align-items: center;">
                                        <canvas id="myPieChart" style="max-width: 250px; max-height: 250px;"></canvas>
                                    </div>
                                    <?php require_once __DIR__ . '/includes/dashboard_pie_chart.php'; ?>
                                    <div style="text-align:center; margin-bottom:10px;">
                                        <strong>Total Students: <?= $totalStudents ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Syllabus Coverage Column -->
                        <div class="col-lg-6 mb-4">
                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Syllabus Coverage</h6>
                                    <button id="undoSyllabus" class="btn btn-sm btn-outline-secondary ">Undo</button>
                                </div>

                                <div class="card-body">
                                    <?php
                                    $subjects = ['math','english','science','social'];
                                    $colors = ['math'=>'bg-danger','english'=>'bg-warning','science'=>'bg-success','social'=>'bg-info'];

                                    foreach ($subjects as $subject):
                                        $value = $sliderValues[$subject];
                                    ?>
                                    <div class="mb-4 syllabus-item">
                                        <h4 class="small font-weight-bold">
                                            <?= ucfirst($subject) ?>
                                            <span class="float-right percent"><?= $value ?>%</span>
                                        </h4>

                                        <div class="progress">
                                            <div class="progress-bar <?= $colors[$subject] ?>" style="width:<?= $value ?>%"></div>
                                        </div>

                                        <input type="range" class="syllabus-range"
                                            min="0" max="100" data-key="<?= $subject ?>"
                                            value="<?= $value ?>">
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                        <!-- post new announcement -->
                            <div class="col-lg-12 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Post New Announcement
                                    </div>

                                    <form method="POST" action="index.php?action=admin_dashboard">
                                        <textarea class="form-control mb-2 " name="announcement" rows="3" placeholder="Write your announcement..."></textarea>
                                        <button class="btn btn-warning btn-sm btn-block" name="post_announcement">Post</button>
                                    </form>

                                    </div>
                                </div>
                            </div>
                    
                        </div>

                        <div class="col-lg-6 mb-4">
                            <!-- Current School Year Card -->
                            <div class="col-xl-12 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Current School Year
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 editable-text" data-key="school_year"
                                                data-hint="Double-click to edit"
                                                ondblclick="enableInlineEdit(this)"><?= htmlspecialchars($schoolYear) ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Active Term Card -->
                            <div class="col-xl-12 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Active Term
                                                </div>

                                                <!-- text editable; do this later -->
                                                <div class="h5 mb-0 font-weight-bold text-gray-800 editable-text"
                                                data-key="active_term"
                                                data-hint="Double-click to edit"
                                                ondblclick="enableInlineEdit(this)"><?= htmlspecialchars($activeTerm) ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-book-reader fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Announcements Panel  -->
                            <div class="col-lg-12 mb-4">
                                <div class="card shadow-lg border-left-primary">
                                    <!-- Card Header -->
                                    <div class="card-header py-3 bg-primary text-white d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold">Recent Announcements</h6>
                                    </div>
                                    <!-- Card Body -->
                                    <div class="card-body" style="max-height: 270px; overflow-y: auto;">
                                        <ul class="list-group list-group-flush">
                                            <?php if (!empty($announcements)): ?>
                                            <?php foreach($announcements as $announcement): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center hover-shadow">
                                                <div>
                                                    <strong><?= htmlspecialchars($announcement['text']) ?></strong> 
                                                    <div class="text-muted"><?= htmlspecialchars($announcement['created_at']) ?></div>
                                                </div>
                                                <div class="d-flex gap-1">
                                                    <form method="POST" style="display:inline;" action="index.php?action=delete_announcement">
                                                        <input type="hidden" name="id" value="<?= $announcement['id'] ?>">
                                                        <button type="submit" class="icon-btn text-danger" title="Delete">
                                                            <i class="fas fa-trash-alt" style="opacity: 0.9;"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </li>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                                <li class="list-group-item">No announcements yet.</li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            </div>

                            

                            <style>
                            /* Hover shadow for list items */
                            .hover-shadow:hover {
                                background-color: #f8f9fc;
                                box-shadow: 0 2px 6px rgba(0,0,0,0.1);
                                transition: all 0.2s;
                            }
                            /* Icon-only buttons */
                            .icon-btn {
                                border: none;
                                background: none;
                                padding: 6px;
                                border-radius: 50%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                cursor: pointer;
                                transition: background 0.2s;
                            }
                            /* Hover effect: light grey circle */
                            .icon-btn:hover {
                                background-color: #e2e6ea;
                            }
                            .announcement-text {
                                max-width: 80%;
                            }
                            </style>

                        
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->


            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <?php include 'includes/footer.php'; ?>
            </footer>
            <!-- End of Footer -->


        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <?php include 'includes/logout_modal.php'; ?>

<script>
//editable cards
function enableInlineEdit(el) {
    el.contentEditable = "true";
    el.focus();

    // place cursor at end
    const range = document.createRange();
    const sel = window.getSelection();
    range.selectNodeContents(el);
    range.collapse(false);
    sel.removeAllRanges();
    sel.addRange(range);

    // disable editing when clicking away and save to DB
    el.addEventListener("blur", () => {
    el.contentEditable = "false";

    // get key from data-key attribute
    const key = el.dataset.key;
    const value = el.textContent.trim();

    // send update to server
    fetch('index.php?action=update_setting', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ key, value })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            alert('Failed to save. Try again.');
        }
    })
    .catch(err => console.error(err));
}, { once: true });
}


// SLIDERS
const sliderHistory = {};
const changeStack = []; // stack of changes

// Initialize sliders
document.querySelectorAll('.syllabus-range').forEach(slider => {
    const key = slider.dataset.key;
    const container = slider.closest('.syllabus-item');
    const bar = container.querySelector('.progress-bar');
    const percent = container.querySelector('.percent');

    // Initialize history array
    sliderHistory[key] = [slider.value];

    // On input: update bar & text
    slider.addEventListener('input', () => {
        bar.style.width = slider.value + '%';
        percent.textContent = slider.value + '%';
    });

    // On change: push to stack & save
    slider.addEventListener('change', () => {
        const oldValue = sliderHistory[key][sliderHistory[key].length - 1];
        const newValue = slider.value;

        // push to global change stack
        changeStack.push({ key, oldValue });

        // update per-slider history
        sliderHistory[key].push(newValue);

        // send update to DB
        fetch('index.php?action=update_syllabus', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ subject: key, value: newValue })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) alert('Failed to save slider value. Try again.');
        });

        // show undo button
        document.getElementById('undoSyllabus').classList.remove('d-none');
    });
});

// Undo button: revert **last change only**
document.getElementById('undoSyllabus').addEventListener('click', () => {
    if (changeStack.length === 0) return;

    const lastChange = changeStack.pop(); // last change
    const { key, oldValue } = lastChange;

    const slider = document.querySelector(`.syllabus-range[data-key="${key}"]`);
    const container = slider.closest('.syllabus-item');
    const bar = container.querySelector('.progress-bar');
    const percent = container.querySelector('.percent');

    // restore previous value in UI
    slider.value = oldValue;
    bar.style.width = oldValue + '%';
    percent.textContent = oldValue + '%';

    // update per-slider history
    sliderHistory[key].push(oldValue);

    // send undone value back to DB
    fetch('index.php?action=update_syllabus', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ subject: key, value: oldValue })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) alert('Failed to save undone slider value.');
    });

    // hide undo button if stack empty
    if (changeStack.length === 0) {
        document.getElementById('undoSyllabus').classList.add('d-none');
    }
});
</script>

<?php include 'assets/js/scripts.php'; ?>
</body>
</html>
