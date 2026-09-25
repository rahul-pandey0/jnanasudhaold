<?php
// Include header
require_once APPPATH . 'views/layout/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('payment'); ?>">Payments</a></li>
        <li class="breadcrumb-item active">Statistics</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <h4 class="mb-3"><i class="bi bi-graph-up"></i> Payment Statistics</h4>
    </div>
</div>

<?php if ($stats): ?>
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Transactions</h6>
                    <h3><?php echo number_format($stats['total_transactions']); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Amount</h6>
                    <h3>₹ <?php echo number_format($stats['total_amount'], 2); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Successful</h6>
                    <h3 class="text-success"><?php echo number_format($stats['successful']); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">Failed</h6>
                    <h3 class="text-danger"><?php echo number_format($stats['failed']); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Payment Status Breakdown</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><span class="badge bg-success">Success</span></td>
                            <td class="text-end"><strong><?php echo number_format($stats['successful']); ?></strong></td>
                            <td class="text-end small text-muted">
                                <?php echo $stats['total_transactions'] > 0 ? round(($stats['successful'] / $stats['total_transactions']) * 100, 1) . '%' : '0%'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-danger">Failed</span></td>
                            <td class="text-end"><strong><?php echo number_format($stats['failed']); ?></strong></td>
                            <td class="text-end small text-muted">
                                <?php echo $stats['total_transactions'] > 0 ? round(($stats['failed'] / $stats['total_transactions']) * 100, 1) . '%' : '0%'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td class="text-end"><strong><?php echo number_format($stats['pending']); ?></strong></td>
                            <td class="text-end small text-muted">
                                <?php echo $stats['total_transactions'] > 0 ? round(($stats['pending'] / $stats['total_transactions']) * 100, 1) . '%' : '0%'; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Revenue Summary</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Total Amount Collected</small>
                        <h4 class="text-success">₹ <?php echo number_format($stats['total_amount'], 2); ?></h4>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Average Transaction Amount</small>
                        <h5>₹ <?php echo $stats['total_transactions'] > 0 ? number_format(($stats['total_amount'] / $stats['total_transactions']), 2) : '0.00'; ?></h5>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Total Transactions</small>
                        <p><?php echo number_format($stats['total_transactions']); ?> transactions</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <a href="<?php echo base_url('payment'); ?>" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Back to Payments
            </a>
            <a href="<?php echo base_url('payment/revenue_report'); ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-file-earmark"></i> View Revenue Report
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> No payment data available
    </div>
<?php endif; ?>

<?php
// Include footer
require_once APPPATH . 'views/layout/footer.php';
?>
