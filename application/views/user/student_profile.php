<?php
$student = isset($student) && is_array($student) ? $student : [];
$payment = isset($payment) && is_array($payment) ? $payment : [];
$payments = isset($payments) && is_array($payments) ? $payments : []; 
$title = isset($title) ? $title : 'Student Profile';

require_once APPPATH . 'views/layout/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('students'); ?>">Students</a></li>
        <li class="breadcrumb-item active">
            <?php
            echo htmlspecialchars(
                ($student['first_name'] ?? 'Unknown') . ' ' . ($student['last_name'] ?? '')
            );
            ?>
        </li>
    </ol>
</nav>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="bi bi-person-circle"></i> user Profile</h5>
         <div>
            <a href="<?php echo base_url('users/view/' . $student['user_id']) ?>" class="btn btn-secondary btn-sm">Back</a>
            <button onclick="window.print()" class="btn btn-primary btn-sm">
            <i class="bi bi-printer"></i> Print
            </button>
        </div>
    </div>

    <div class="card-body">

        <!-- PROFILE DETAILS -->

            <div id="print-area" style="padding:0; margin:0;">
                <!-- Add print title -->
                <div class="print-title" style="text-align:center; margin-bottom:10px;">
                    <h2><?php echo htmlspecialchars($title); ?></h2>
                </div>
               <div style="display:flex; gap:10px; background:#f8f9fa; padding:10px; border-radius:4px;">


                  <?php if (!empty($student['profile_photo'])): ?>
                    <img src="<?php echo base_url('uploads/profile_photos/' . $student['profile_photo']); ?>"
                         style="width:110px; height:150px; object-fit:cover; border-radius:4px;">
                <?php else: ?>
                    <div style="width:110px; height:150px; background:#ddd; border-radius:4px;">No Photo</div>
                <?php endif; ?>

                <div>
                    <div><strong>User ID:</strong> <?php echo $student['phone'] ?? 'N/A'; ?></div>
                    <div><strong>Name:</strong>
                        <?php echo htmlspecialchars(($student['first_name'] ?? 'N/A') . ' ' . ($student['last_name'] ?? '')); ?>
                    </div>
                    <div><strong>Email:</strong> <?php echo htmlspecialchars($student['email'] ?? 'N/A'); ?></div>
                    <div><strong>Phone:</strong> <?php echo htmlspecialchars($student['phone'] ?? 'N/A'); ?></div>
                    <div><strong>College:</strong> <?php echo htmlspecialchars($student['college_name'] ?? 'N/A'); ?></div>
                    <div><strong>Department:</strong> <?php echo htmlspecialchars($student['department'] ?? 'N/A'); ?></div>
                    <div><strong>Year:</strong> <?php echo htmlspecialchars($student['year'] ?? 'N/A'); ?></div>
                </div>
       
</div>

             <!--package details-->
              
            <h6 style="color:#667eea;">Package Details</h6>

            <table class="table table-bordered table-striped print-table">
                <thead style="background:#f8f9fa;">
                    <tr>
                        <th>Package Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Amount</th>
                        <th class="no-print">view details</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($packages)): ?>
                        <?php foreach ($packages as $pkg): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pkg['package_name'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($pkg['subscribed_on'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($pkg['end_date'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($pkg['package_amount'] ?? '0.00'); ?></td>
                                <td class="no-print">
                                    <?php
                                        $student_phone = $student['phone'] ?? null;
                                        $package_id = $pkg['package_id'] ?? null;

                                        if (!empty($pkg['url'])) {
                                            echo '<a href="'.htmlspecialchars($pkg['url']).'" target="_blank">View</a>';
                                        }
                                        else if ($student_phone && $package_id) {
                                            $url = base_url("users/package/$student_phone/$package_id");
                                            echo '<a href="'.$url.'" target="_blank">View</a>';
                                        }
                                        else {
                                            echo 'N/A';
                                        }
                                    ?>
                                </td>
                            </tr>
                        
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No packages found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
                   
                 
        <!--  PAYMENT TABLE  -->
        <div class="col-md-12">
             
            
            <h6 style="color:#667eea;">Payment Details</h6>
            <table class="table table-bordered table-striped print-table">
                <thead style="background:#f8f9fa;">
                    <tr>
                        <th>Order No</th>
                        <th>Receipt No</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="no-print">download receipts</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($payments)): ?>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($payment['order_no'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($payment['receipt_no'] ?? 'N/A'); ?></td>
                                <td>
                                    <?php echo htmlspecialchars(($payment['currency'] ?? '').' '.($payment['amount'] ?? '0.00')); ?>
                                </td>
                                <td>
                                    <?php echo !empty($payment['datetime']) ? date('d M Y H:i', strtotime($payment['datetime'])) : 'N/A'; ?>
                                </td>
                                <td><?php echo htmlspecialchars($payment['status'] ?? 'N/A'); ?></td>
                                <td class="no-print">
                                    <a href="<?php echo site_url('payment/download/'.urlencode($payment['order_no'].'.pdf')); ?>"
                                    class="btn btn-success btn-xs" title="Download Receipt">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <a href="<?php echo base_url('payment/regenerate_receipt?order_no=' . urlencode($payment['order_no'])); ?>"
                                                           class="btn btn-outline-success btn-xs" title="Regenerate Receipt">
                                                            <i class="bi bi-arrow-repeat"></i>
                                                        </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No payment record found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>
 
   <style>
@media print {
    body, html {
        margin: 0;
        padding: 0;
    }

    /* Hide everything else */
    nav, .breadcrumb, .btn, .card-header, footer { display: none !important; }
    body * { visibility: hidden; }

    /* Show only print-area */
    #print-area, #print-area * {
        visibility: visible;
    }

    /* Force #print-area to the top of the page */
    #print-area {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        margin: 0;
        padding: 0;
        page-break-inside: avoid;
    }

    table { 
        width: 100%; 
        border-collapse: collapse; 
        table-layout: fixed; 
        font-size: 10px; 
        page-break-inside: avoid; 
    }
    table th, table td { border:1px solid #000; padding:3px; word-wrap:break-word; }
    tr { page-break-inside: avoid; }
    h5, h6 { color:#000 !important; page-break-after: avoid; }
    img { max-width:70px; max-height:100px; object-fit:cover; }
    .no-print { display: none !important; }

    @page { margin: 5mm; size: auto; }
}

</style>

<?php require_once APPPATH . 'views/layout/footer.php'; ?>