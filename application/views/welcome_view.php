<?php 
// Get the global controller instance
$controller = &$GLOBALS['CI'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'CodeIgniter App'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --bs-body-font-size: 0.75rem;
        }
        * {
            margin: 0;
            padding: 0;
        }
        body {
            font-size: 0.75rem;
            background-color: #f8f9fa;
        }
        .navbar {
            padding: 0.25rem 0.5rem;
            min-height: auto;
        }
        .navbar-brand {
            font-size: 0.9rem;
            font-weight: 600;
        }
        .nav-link {
            padding: 0.15rem 0.3rem !important;
            font-size: 0.7rem;
        }
        .card {
            box-shadow: 0 0.05rem 0.1rem rgba(0, 0, 0, 0.075);
            border: 0.5px solid rgba(0, 0, 0, 0.06);
            margin-bottom: 0.5rem;
        }
        .card-header {
            padding: 0.35rem 0.5rem;
            background-color: #f8f9fa;
            border-bottom: 0.5px solid rgba(0, 0, 0, 0.06);
        }
        .card-header h5, .card-header h6 {
            font-size: 0.75rem;
            margin-bottom: 0;
        }
        .card-body {
            padding: 0.5rem;
        }
        .card-body h6 {
            font-size: 0.65rem;
        }
        .card-body h3 {
            font-size: 1.2rem;
            margin: 0;
        }
        .table {
            font-size: 0.7rem;
            margin-bottom: 0;
        }
        .table th, .table td {
            padding: 0.25rem 0.3rem;
        }
        .table thead th {
            background-color: #e9ecef;
            font-weight: 600;
            border-bottom: 0.5px solid #dee2e6;
        }
        .btn {
            padding: 0.2rem 0.4rem;
            font-size: 0.65rem;
        }
        .btn-sm {
            padding: 0.1rem 0.25rem;
            font-size: 0.6rem;
        }
        .form-control, .form-select {
            padding: 0.2rem 0.3rem;
            font-size: 0.75rem;
            height: auto;
        }
        .container-fluid {
            padding: 0.5rem;
        }
        .sidebar {
            background-color: #fff;
            border-right: 0.5px solid #dee2e6;
            padding: 0.3rem;
            min-height: calc(100vh - 35px);
        }
        .sidebar .list-group-item {
            padding: 0.3rem 0.5rem;
            font-size: 0.7rem;
        }
        .main-content {
            padding: 0.5rem;
        }
        .badge {
            padding: 0.2rem 0.3rem;
            font-size: 0.6rem;
        }
        .breadcrumb {
            padding: 0.25rem 0;
            margin-bottom: 0.3rem;
            font-size: 0.7rem;
        }
        .row {
            margin: 0;
        }
        .row > * {
            padding: 0.2rem;
        }
        .alert {
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.7rem;
        }
        .alert strong {
            font-size: 0.75rem;
        }
        .border-primary, .border-success, .border-warning, .border-danger {
            border-width: 2px !important;
        }
        .text-muted {
            font-size: 0.65rem;
            color: #6c757d !important;
        }
        ul.small li {
            font-size: 0.7rem;
            line-height: 1.3;
        }
        footer {
            padding: 0.3rem 0 !important;
            font-size: 0.65rem;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo base_url(); ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-person-circle"></i> Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-box-arrow-right"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar d-none d-md-block">
                <div class="list-group list-group-flush">
                    <a href="<?php echo base_url(); ?>" class="list-group-item list-group-item-action active border-0">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                    <a href="#" class="list-group-item list-group-item-action border-0">
                        <i class="bi bi-table"></i> Tables
                    </a>
                    <a href="#" class="list-group-item list-group-item-action border-0">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo isset($title) ? $title : 'Dashboard'; ?></li>
                    </ol>
                </nav>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-speedometer2"></i> Dashboard</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card border-primary">
                                            <div class="card-body">
                                                <h6 class="text-muted mb-1">Total Users</h6>
                                                <h3 class="mb-0">0</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-success">
                                            <div class="card-body">
                                                <h6 class="text-muted mb-1">Active</h6>
                                                <h3 class="mb-0">0</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-warning">
                                            <div class="card-body">
                                                <h6 class="text-muted mb-1">Pending</h6>
                                                <h3 class="mb-0">0</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-danger">
                                            <div class="card-body">
                                                <h6 class="text-muted mb-1">Inactive</h6>
                                                <h3 class="mb-0">0</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <strong><i class="bi bi-info-circle"></i> Welcome!</strong> 
                                    Ready to start? Provide your table structure and data to begin setting up.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="bi bi-list-check"></i> Quick Start</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="small mb-0">
                                            <li>Define your table structure (columns, data types)</li>
                                            <li>Provide sample data or import from CSV</li>
                                            <li>System will auto-generate CRUD pages</li>
                                            <li>Customize styling and functionality</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="border-top bg-white mt-3 py-2 text-center text-muted">
        <small>&copy; 2025 CodeIgniter Dashboard. All rights reserved.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
