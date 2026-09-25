<?php
// Include header
require_once APPPATH . 'views/layout/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item active"><?php echo isset($title) ? $title : 'Payments'; ?></li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0"><i class="bi bi-credit-card"></i> <?php echo $title; ?></h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <!-- Extra actions removed per simplified module scope -->
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
                <!-- Search + Filters Form -->
                <form method="GET" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control form-control-sm" 
                                   placeholder="Search by name, email, mobile, or order no..." 
                                   value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All Status</option>
                                <option value="Success" <?php echo (!empty($status) && $status === 'Success') ? 'selected' : ''; ?>>Success</option>
                                <option value="Failed" <?php echo (!empty($status) && $status === 'Failed') ? 'selected' : ''; ?>>Failed</option>
                                <option value="Pending" <?php echo (!empty($status) && $status === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-sliders"></i> Apply
                                </button>
                            </div>
                            <?php if (!empty($search) || !empty($status)): ?>
                                <div class="d-grid mt-1">
                                    <a href="<?php echo base_url('payment'); ?>" class="btn btn-secondary btn-sm">Clear</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>

                <!-- Payments Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr style="background-color: #f8f9fa;">
                                <th>Order No</th>
                                <th>Customer Name</th>
                                <th>Mobile</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($payments)): ?>
                                <?php foreach ($payments as $payment): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($payment['order_no']); ?></strong></td>
                                        <td>
                                            <form method="POST" action="<?php echo base_url('payment/update_name'); ?>" class="name-form d-flex align-items-center">
                                                <input type="hidden" name="payment_id" value="<?php echo (int)$payment['id']; ?>">
                                                <?php $nameVal = isset($payment['cust_name']) ? trim($payment['cust_name']) : ''; ?>
                                                
                                                <input type="text" name="cust_name" value="<?php echo htmlspecialchars($nameVal); ?>" class="form-control form-control-sm" placeholder="Enter customer name" style="max-width: 12rem;"title="Edit Customer Name">
                                                <button type="submit" class="btn btn-sm btn-outline-primary ms-1" title="Save">
                                                    <i class="bi bi-check2"></i>
                                                </button>
                                            </form>
                                        </td>

                                        <td><?php echo htmlspecialchars($payment['mobile_no']); ?></td>
                                        <td><?php echo htmlspecialchars($payment['currency']) . ' ' . number_format($payment['amount'], 2); ?></td>
                                        <td>
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
                                        </td>
                                        <td><?php echo date('d M Y H:i', strtotime($payment['datetime'])); ?></td>
                                        <td>
                                            <a href="<?php echo base_url('payment/view/' . $payment['id']); ?>" 
                                               class="btn btn-primary btn-xs" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <?php if ($payment['status'] !== 'Success'): ?>
                                                <a href="<?php echo base_url('payment/edit/' . $payment['id']); ?>" class="btn btn-warning btn-xs" title="Edit / Finalize">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (!empty($payment['order_no'])): ?>
                                                <?php if (!empty($payment['receipt_no'])): ?>
                                                    <a href="<?php echo base_url('payment/download?file=' . urlencode($payment['order_no'] . '.pdf')); ?>"
                                                       class="btn btn-success btn-xs" title="Download Receipt">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                    <?php if (isset($payment['status']) && $payment['status'] == 'Success'): ?>
                                                        <a href="<?php echo base_url('payment/regenerate_receipt?order_no=' . urlencode($payment['order_no'])); ?>"
                                                           class="btn btn-outline-success btn-xs" title="Regenerate Receipt">
                                                            <i class="bi bi-arrow-repeat"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <?php if ($payment['status'] === 'Success'): ?>
                                                        <!-- Success but missing receipt: allow immediate generation -->
                                                        <a href="<?php echo base_url('payment/regenerate_receipt?order_no=' . urlencode($payment['order_no'])); ?>"
                                                           class="btn btn-success btn-xs" title="Generate Receipt">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">No Receipt Yet</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">
                                        No payment records found
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm">
                            <?php if ($current_page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo base_url('payment?page=1' 
                                        . (!empty($search) ? '&search=' . urlencode($search) : '')
                                        . (!empty($status) ? '&status=' . urlencode($status) : '')); ?>">First</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo base_url('payment?page=' . ($current_page - 1)
                                        . (!empty($search) ? '&search=' . urlencode($search) : '')
                                        . (!empty($status) ? '&status=' . urlencode($status) : '')); ?>">Previous</a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                                <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo base_url('payment?page=' . $i 
                                        . (!empty($search) ? '&search=' . urlencode($search) : '')
                    					. (!empty($status) ? '&status=' . urlencode($status) : '')); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($current_page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo base_url('payment?page=' . ($current_page + 1)
                                        . (!empty($search) ? '&search=' . urlencode($search) : '')
                                        . (!empty($status) ? '&status=' . urlencode($status) : '')); ?>">Next</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo base_url('payment?page=' . $total_pages 
                                        . (!empty($search) ? '&search=' . urlencode($search) : '')
                                        . (!empty($status) ? '&status=' . urlencode($status) : '')); ?>">Last</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <p class="text-muted small">Showing page <?php echo $current_page; ?> of <?php echo $total_pages; ?> (<?php echo $total_records; ?> total records)</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
require_once APPPATH . 'views/layout/footer.php';
?>