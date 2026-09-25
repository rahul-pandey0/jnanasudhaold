<?php
// Include header
require_once APPPATH . 'views/layout/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('payment'); ?>">Payments</a></li>
        <li class="breadcrumb-item active">Revenue Report</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-file-earmark"></i> Revenue Report</h5>
            </div>
            <div class="card-body">
                <!-- Date Range Filter -->
                <form method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label" style="font-size: 0.75rem;">From Date</label>
                            <input type="date" name="from_date" class="form-control form-control-sm" 
                                   value="<?php echo htmlspecialchars($from_date); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" style="font-size: 0.75rem;">To Date</label>
                            <input type="date" name="to_date" class="form-control form-control-sm" 
                                   value="<?php echo htmlspecialchars($to_date); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" style="font-size: 0.75rem;">&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-filter"></i> Filter
                            </button>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" style="font-size: 0.75rem;">&nbsp;</label>
                            <a href="<?php echo base_url('payment/revenue_report'); ?>" class="btn btn-secondary btn-sm w-100">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Revenue Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr style="background-color: #f8f9fa;">
                                <th>Date</th>
                                <th class="text-end">Transactions</th>
                                <th class="text-end">Revenue (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_revenue = 0;
                            $total_transactions = 0;
                            
                            if (!empty($revenue_data)): 
                                foreach ($revenue_data as $row):
                                    $total_revenue += $row['daily_revenue'];
                                    $total_transactions += $row['transaction_count'];
                            ?>
                                <tr>
                                    <td><?php echo date('d M Y', strtotime($row['payment_date'])); ?></td>
                                    <td class="text-end"><?php echo number_format($row['transaction_count']); ?></td>
                                    <td class="text-end"><strong>₹ <?php echo number_format($row['daily_revenue'], 2); ?></strong></td>
                                </tr>
                            <?php 
                                endforeach;
                            ?>
                                <tr style="background-color: #f0f0f0; border-top: 2px solid #dee2e6;">
                                    <td><strong>Total</strong></td>
                                    <td class="text-end"><strong><?php echo number_format($total_transactions); ?></strong></td>
                                    <td class="text-end"><strong>₹ <?php echo number_format($total_revenue, 2); ?></strong></td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        No revenue data found for the selected period
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Summary Stats -->
                <?php if (!empty($revenue_data)): ?>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Total Revenue</h6>
                                    <h4 class="text-success">₹ <?php echo number_format($total_revenue, 2); ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Total Transactions</h6>
                                    <h4><?php echo number_format($total_transactions); ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Average per Transaction</h6>
                                    <h4>₹ <?php echo number_format(($total_revenue / $total_transactions), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <a href="<?php echo base_url('payment'); ?>" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back to Payments
                </a>
                <a href="<?php echo base_url('payment/export'); ?>" class="btn btn-warning btn-sm">
                    <i class="bi bi-download"></i> Export as CSV
                </a>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
require_once APPPATH . 'views/layout/footer.php';
?>
