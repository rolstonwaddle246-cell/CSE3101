<!-- sidebar -->
<?php
// Determine the current page and action
$current_page = basename($_SERVER['PHP_SELF']); // e.g., index.php
$current_action = isset($_GET['action']) ? $_GET['action'] : '';
?>

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-start" href="index.html">
                <div class="sidebar-brand-icon">
                    <i class='bx  bx-fan'></i> 
                </div>
                <div class="sidebar-brand-text mx-3">SMS</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?php if($current_page == 'index.php' && $current_action == 'admin_dashboard') echo 'active'; ?>">
                <a class="nav-link" href="admin_dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                User Management
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item <?php if($current_page == 'index.php' && $current_action == 'manage_users') echo 'active'; ?>">
                <a class="nav-link" href="index.php?action=manage_users">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Users</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Academics
            </div>

            <li class="nav-item <?php if($current_page == 'index.php' && $current_action == '') echo 'active'; ?>">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-graduation-cap"></i>
                    <span>Grades</span></a>
            </li>

            <li class="nav-item <?php if($current_page == 'index.php' && $current_action == '') echo 'active'; ?>">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chalkboard"></i>
                    <span>Classes</span></a>
            </li>

            <li class="nav-item <?php if($current_page == 'index.php' && $current_action == '') echo 'active'; ?>">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Subjects</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Student Management 
            </div>

            <li class="nav-item <?php if($current_page == 'index.php' && $current_action == '') echo 'active'; ?>">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-user-graduate"></i>
                    <span>Students</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Teacher Management
            </div>

            <li class="nav-item <?php if($current_page == 'index.php' && $current_action == '') echo 'active'; ?>">
                <a class="nav-link" href="">
                    <i class="fas fa-fw fa-chalkboard-teacher"></i>
                    <span>Teachers</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="index.php?action=assign_teachers">
                    <i class="fas fa-fw fa-chalkboard-teacher"></i>
                    <span>Assign Teachers</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">


            <!-- Heading -->
            <div class="sidebar-heading">
                Academic Periods
            </div>

            <li class="nav-item <?php if($current_page == 'index.php' && $current_action == 'school_years') echo 'active'; ?>">
                <a class="nav-link" href="index.php?action=school_years">
                    <i class="fas fa-fw fa-calendar"></i>
                    <span>School Years & Terms</span></a>
            </li>

            <hr class="sidebar-divider">


            <!-- Heading -->
            <div class="sidebar-heading">
                Scores
            </div>

            <li class="nav-item <?php if($current_page == 'index.php' && $current_action == '') echo 'active'; ?>">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-list-alt"></i>
                    <span>Score Summary</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Reports
            </div>

            <li class="nav-item <?php if($current_page == 'index.php' && $current_action == 'student_report_card') echo 'active'; ?>">
                <a class="nav-link" href="index.php?action=student_report_card">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Student Report Cards</span></a>
            </li>
            <li class="nav-item <?php if($current_page == 'index.php' && $current_action == 'average_performance') echo 'active'; ?>">
                <a class="nav-link" href="index.php?action=average_performance">
                    <i class="fas fa-fw fa-chart-line"></i>
                    <span>Average Performance</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>


        </ul>