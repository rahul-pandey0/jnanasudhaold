<?php
// Include header
require_once APPPATH . 'views/layout/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('payment'); ?>">Payments</a></li>
        <li class="breadcrumb-item active">Receipt #<?php echo htmlspecialchars($payment['receipt_no']); ?></li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0"><i class="bi bi-receipt"></i> Payment Receipt Details</h5>
                    <div class="btn-toolbar receipt-actions" role="toolbar" aria-label="Receipt actions">
                        <div class="btn-group btn-group-sm me-2" role="group">
                            <a href="<?php echo base_url('payment'); ?>" class="btn btn-light text-secondary fw-semibold" title="Back to Payments">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                        <div class="btn-group btn-group-sm me-2" role="group">
                            <?php if (!empty($payment['order_no'])): ?>
                                <a href="<?php echo base_url('payment/download?file=' . urlencode($payment['order_no'] . '.pdf')); ?>" class="btn btn-success fw-semibold" title="Download Receipt PDF">
                                    <i class="bi bi-download"></i> PDF
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <?php if (!empty($payment['status']) && $payment['status'] === 'Success'): ?>
                                <a href="<?php echo base_url('payment/regenerate_receipt?order_no=' . urlencode($payment['order_no'])); ?>" class="btn btn-primary fw-semibold" title="Regenerate Receipt PDF">
                                    <i class="bi bi-arrow-repeat"></i> Regenerate
                                </a>
                            <?php elseif (empty($payment['receipt_no']) && !empty($payment['order_no'])): ?>
                                <a href="<?php echo base_url('payment/regenerate_receipt?order_no=' . urlencode($payment['order_no'])); ?>" class="btn btn-primary fw-semibold" title="Generate Receipt PDF">
                                    <i class="bi bi-file-earmark-plus"></i> Generate
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                        <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                        <i class="bi bi-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Order Number</h6>
                        <p><?php echo htmlspecialchars($payment['order_no']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Receipt Number</h6>
                        <p><?php echo htmlspecialchars($payment['receipt_no']); ?></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Customer Name</h6>
                        <p><?php echo htmlspecialchars($payment['cust_name']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Email Address</h6>
                        <p><?php echo htmlspecialchars($payment['email_id']); ?></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Mobile Number</h6>
                        <p><?php echo htmlspecialchars($payment['mobile_no']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Merchant ID</h6>
                        <p><?php echo htmlspecialchars($payment['merchant_id']); ?></p>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Amount</h6>
                        <p><strong><?php echo htmlspecialchars($payment['currency']) . ' ' . number_format($payment['amount'], 2); ?></strong></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Status</h6>
                        <p>
                            <?php
                            $status = $payment['status'];
                            if ($status == 'Success') {
                                echo '<span class="badge bg-success">Success</span>';
                            } elseif ($status == 'Failed') {
                                echo '<span class="badge bg-danger">Failed</span>';
                            } elseif ($status == 'Pending') {
                                echo '<span class="badge bg-warning">Pending</span>';
                            } else {
                                echo '<span class="badge bg-secondary">' . htmlspecialchars($status) . '</span>';
                            }
                            ?>
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <h6 class="text-muted">Status Reason</h6>
                        <p><?php echo !empty($payment['status_reason']) ? htmlspecialchars($payment['status_reason']) : '<em class="text-muted">N/A</em>'; ?></p>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Package ID</h6>
                        <p><?php echo htmlspecialchars($payment['packageid']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Package Name</h6>
                        <p><?php echo htmlspecialchars($payment['packagename']); ?></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Package Type</h6>
                        <p><?php echo !empty($payment['package_type']) ? htmlspecialchars($payment['package_type']) : '<em class="text-muted">N/A</em>'; ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">UDF 1</h6>
                        <p><?php echo !empty($payment['udf_1']) ? htmlspecialchars($payment['udf_1']) : '<em class="text-muted">N/A</em>'; ?></p>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted">Payment Reference</h6>
                        <p><?php echo htmlspecialchars($payment['payment_refno']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Gateway Reference</h6>
                        <p><?php echo htmlspecialchars($payment['pg_refno']); ?></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <h6 class="text-muted">Transaction Date & Time</h6>
                        <p><?php echo date('d M Y H:i:s', strtotime($payment['datetime'])); ?></p>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?php echo base_url('payment'); ?>" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back to Payments
                </a>
                <?php if (!empty($payment['order_no'])): ?>
                    <?php if (!empty($payment['receipt_no'])): ?>
                        <a href="<?php echo base_url('payment/download?file=' . urlencode($payment['order_no'] . '.pdf')); ?>" class="btn btn-success btn-sm">
                            <i class="bi bi-download"></i> Download Receipt
                        </a>
                        <?php if (isset($payment['status']) && $payment['status'] === 'Success'): ?>
                            <a href="<?php echo base_url('payment/regenerate_receipt?order_no=' . urlencode($payment['order_no'])); ?>" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-arrow-repeat"></i> Regenerate Receipt
                            </a>
                        <?php else: ?>
                            <a href="<?php echo base_url('payment/edit/' . $payment['id']); ?>" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit / Finalize
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if ($payment['status'] !== 'Success'): ?>
                            <a href="<?php echo base_url('payment/edit/' . $payment['id']); ?>" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit / Finalize
                            </a>
                            <span class="badge bg-secondary">No Receipt Yet</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Receipt Pending</span>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-info-circle"></i> Quick Info</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Total Amount</small>
                    <h5><?php echo htmlspecialchars($payment['currency']) . ' ' . number_format($payment['amount'], 2); ?></h5>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Payment Status</small>
                    <h6>
                        <?php
                        $status = $payment['status'];
                        if ($status == 'Success') {
                            echo '<span class="badge bg-success">Successful</span>';
                        } elseif ($status == 'Failed') {
                            echo '<span class="badge bg-danger">Failed</span>';
                        } elseif ($status == 'Pending') {
                            echo '<span class="badge bg-warning">Pending</span>';
                        } else {
                            echo '<span class="badge bg-secondary">' . htmlspecialchars($status) . '</span>';
                        }
                        ?>
                    </h6>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Transaction Date</small>
                    <p><?php echo date('d M Y', strtotime($payment['datetime'])); ?></p>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Transaction Time</small>
                    <p><?php echo date('H:i:s', strtotime($payment['datetime'])); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
require_once APPPATH . 'views/layout/footer.php';
?>
