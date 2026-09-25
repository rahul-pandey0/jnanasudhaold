<?php
// Include header
require_once APPPATH . 'views/layout/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('payment'); ?>">Payments</a></li>
        <li class="breadcrumb-item active">Add Payment</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Add New Payment Record</h5>
            </div>
            <div class="card-body">
                <form id="addPaymentForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="merchant_id">Merchant ID</label>
                            <input type="text" name="merchant_id" id="merchant_id" class="form-control" placeholder="e.g., MERCHANT001">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="order_no">Order Number <span class="text-danger">*</span></label>
                            <input type="text" name="order_no" id="order_no" class="form-control" required placeholder="e.g., ORD-12345">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="cust_name">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" name="cust_name" id="cust_name" class="form-control" required placeholder="Enter customer name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="email_id">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email_id" id="email_id" class="form-control" required placeholder="Enter email">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="mobile_no">Mobile Number <span class="text-danger">*</span></label>
                            <input type="tel" name="mobile_no" id="mobile_no" class="form-control" required placeholder="Enter mobile number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="amount">Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="amount" class="form-control" step="0.01" required placeholder="0.00">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="currency">Currency</label>
                            <select name="currency" id="currency" class="form-control">
                                <option value="INR">INR - Indian Rupee</option>
                                <option value="USD">USD - US Dollar</option>
                                <option value="EUR">EUR - Euro</option>
                                <option value="GBP">GBP - British Pound</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="status">Payment Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="Success">Success</option>
                                <option value="Failed">Failed</option>
                                <option value="Pending">Pending</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="packageid">Package ID</label>
                            <input type="text" name="packageid" id="packageid" class="form-control" placeholder="Enter package ID">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="packagename">Package Name</label>
                            <input type="text" name="packagename" id="packagename" class="form-control" placeholder="Enter package name">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="package_type">Package Type</label>
                            <input type="text" name="package_type" id="package_type" class="form-control" placeholder="e.g., Monthly, Annual">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="udf_1">UDF 1 (Custom Field)</label>
                            <input type="text" name="udf_1" id="udf_1" class="form-control" placeholder="Custom user data">
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label" for="status_reason">Status Reason</label>
                            <textarea name="status_reason" id="status_reason" class="form-control" rows="2" placeholder="Enter reason for the payment status..."></textarea>
                        </div>
                    </div>

                    <div class="alert alert-info small" style="font-size: 0.75rem;">
                        <i class="bi bi-info-circle"></i> Fields marked with <span class="text-danger">*</span> are required
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary btn-sm" id="submitBtn">
                    <i class="bi bi-save"></i> Add Payment Record
                </button>
                <a href="<?php echo base_url('payment'); ?>" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Cancel
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('submitBtn').addEventListener('click', function() {
    const form = document.getElementById('addPaymentForm');
    const formData = new FormData(form);
    
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Payment record added successfully!');
            window.location.href = '<?php echo base_url('payment'); ?>';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the payment');
    });
});
</script>

<?php
// Include footer
require_once APPPATH . 'views/layout/footer.php';
?>
