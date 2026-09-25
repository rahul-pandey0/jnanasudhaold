<?php
// Include header
require_once APPPATH . 'views/layout/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('payment'); ?>">Payments</a></li>
        <li class="breadcrumb-item active">Edit Payment</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-pencil"></i> Edit Payment Record</h5>
            </div>
            <div class="card-body">
                <form id="editPaymentForm" method="POST">
                    <!-- Read-only fields -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Order Number</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($payment['order_no']); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Receipt Number</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($payment['receipt_no']); ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Customer Name</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($payment['cust_name']); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?php echo htmlspecialchars($payment['email_id']); ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($payment['mobile_no']); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($payment['currency']) . ' ' . number_format($payment['amount'], 2); ?>" readonly>
                        </div>
                    </div>

                    <hr>

                    <!-- Editable fields -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="status">Payment Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="Success" <?php echo ($payment['status'] == 'Success') ? 'selected' : ''; ?>>Success</option>
                                <option value="Failed" <?php echo ($payment['status'] == 'Failed') ? 'selected' : ''; ?>>Failed</option>
                                <option value="Pending" <?php echo ($payment['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Cancelled" <?php echo ($payment['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label" for="status_reason">Status Reason</label>
                            <textarea name="status_reason" id="status_reason" class="form-control" rows="3" placeholder="Enter reason for payment status..."><?php echo htmlspecialchars($payment['status_reason']); ?></textarea>
                        </div>
                    </div>

                    <hr>

                    <!-- Gateway Info (editable for non-success) -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="payment_refno">Payment Reference (internal)</label>
                            <input type="text" name="payment_refno" id="payment_refno" class="form-control" value="<?php echo htmlspecialchars($payment['payment_refno']); ?>" placeholder="Enter internal payment reference">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="pg_refno">Gateway Reference (PG Ref No)</label>
                            <input type="text" name="pg_refno" id="pg_refno" class="form-control" value="<?php echo htmlspecialchars($payment['pg_refno']); ?>" placeholder="Enter gateway reference (if available)">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Transaction Date & Time</label>
                            <input type="text" class="form-control" value="<?php echo date('d M Y H:i:s', strtotime($payment['datetime'])); ?>" readonly>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary btn-sm" id="submitBtn">
                    <i class="bi bi-save"></i> Save Changes
                </button>
                <a href="<?php echo base_url('payment/view/' . $payment['id']); ?>" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Cancel
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('submitBtn').addEventListener('click', function() {
    const form = document.getElementById('editPaymentForm');
    const formData = new FormData(form);
    
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Payment updated successfully!');
            window.location.href = '<?php echo base_url('payment/view/' . $payment['id']); ?>';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the payment');
    });
});
</script>

<?php
// Include footer
require_once APPPATH . 'views/layout/footer.php';
?>
