<?php 
$controller = &$GLOBALS['CI'];
if (method_exists($controller, 'load')) {
    $controller->load->view('header_content', isset($data) ? $data : array());
}
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
            --bs-body-font-size: 0.875rem;
        }
        body {
            font-size: 0.875rem;
            background-color: #f8f9fa;
        }
        .navbar {
            padding: 0.5rem 1rem;
        }
        .navbar-brand {
            font-size: 1.1rem;
            font-weight: 600;
        }
        .nav-link {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.85rem;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.06);
            margin-bottom: 1rem;
        }
        .card-header {
            padding: 0.5rem 1rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }
        .card-body {
            padding: 0.75rem;
        }
        .table {
            font-size: 0.8rem;
            margin-bottom: 0;
        }
        .table th, .table td {
            padding: 0.35rem 0.5rem;
        }
        .table thead th {
            background-color: #e9ecef;
            font-weight: 600;
            border-bottom: 1px solid #dee2e6;
        }
        .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        .btn-sm {
            padding: 0.15rem 0.3rem;
            font-size: 0.7rem;
        }
        .form-control, .form-select {
            padding: 0.35rem 0.5rem;
            font-size: 0.85rem;
        }
        .container-fluid {
            padding: 0.75rem;
        }
        .sidebar {
            background-color: #fff;
            border-right: 1px solid #dee2e6;
            padding: 0.5rem;
            min-height: calc(100vh - 50px);
        }
        .main-content {
            padding: 0.75rem;
        }
        .badge {
            padding: 0.25rem 0.4rem;
            font-size: 0.7rem;
        }
        .breadcrumb {
            padding: 0.5rem 0;
            margin-bottom: 0.5rem;
            font-size: 0.8rem;
        }
        .modal-body {
            padding: 1rem;
        }
        .modal-header {
            padding: 0.5rem 1rem;
            border-bottom: 1px solid #dee2e6;
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
                        <li class="breadcrumb-item active"><?php echo isset($title) ? $title : 'Page'; ?></li>
                    </ol>
                </nav>
