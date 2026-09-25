<?php
$title = 'Quiz Report';
$active_menu = 'quiz_report7';
require_once APPPATH . 'views/layout/header.php';

$selected_quiztype = isset($_POST['quiztype']) ? $_POST['quiztype'] : '';
$selected_quizid   = isset($_POST['id']) ? $_POST['id'] : '';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="<?php echo base_url('dashboard'); ?>">Home</a>
        </li>
        <li class="breadcrumb-item active">Quiz Report</li>
    </ol>
</nav>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Select Quiz</h5>
    </div>
    <div class="card-body">

        <form method="POST" action="<?php echo base_url('Admin/quiz_report7'); ?>">

            <div class="row">

                <!-- Quiz Type -->
                <div class="col-md-6">
                    <label class="form-label">Quiz Type</label>

                    
                <select name="quiztype" class="form-control" required onchange="this.form.submit()">
                     <option value="">Select Quiz Type</option>
                        <?php foreach ($quiz_types as $type): ?>
                            <option value="<?php echo htmlspecialchars($type['quiztype']); ?>"
                                <?php echo ($selected_quiztype == $type['quiztype']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($type['quiztype']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Quiz Name -->
                <div class="col-md-6">
                    <label class="form-label">Quiz Name</label>

                    
                    <select name="id"
                            class="form-control"
                            <?php echo empty($quiz_names) ? 'disabled' : ''; ?>
                            required>
                        <option value="">
                            <?php echo empty($quiz_names)
                                ? 'Select Quiz Type First'
                                : 'Select Quiz'; ?>
                        </option>

                        <?php foreach ($quiz_names as $quiz): ?>
                            <option value="<?php echo $quiz['id']; ?>"
                                <?php echo ($selected_quizid == $quiz['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($quiz['Quiz_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

            <!-- ADDED: Submit button to fetch table data -->
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">
                    Submit
                </button>
            </div>

        </form>
    </div>
</div>

<!-- table always visible -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Quiz Report Preview</h5>
    </div>

    <div class="card-body"> 
     <div class= "table-responsive">

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                     <?php if (!empty($report['data'])): ?>
                        <th>S.No</th>
                    <?php endif; ?>

                    <?php if (!empty($report['header'])): ?>

                        <?php foreach ($report['header'] as $col): ?>
                            <th><?php echo htmlspecialchars($col); ?></th>
                        <?php endforeach; ?>
                    <?php else: ?>
                        
                <?php endif; ?>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($report['data'])): ?>
                    <?php $serial = 1; ?>
                    <?php foreach ($report['data'] as $row): ?>
                        <tr>
                           <?php if (!empty($report['data'])): ?>
                                <td><?php echo $serial++; ?></td>
                            <?php endif; ?>
                            
                            <?php foreach ($report['header'] as $key): ?>
                                <td><?php echo htmlspecialchars(isset($row[$key]) ? $row[$key] : ''); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!--  idle table row -->
                    <tr>
                        <td class="text-center" colspan="<?php echo !empty($report['header']) ? count($report['header']): 1; ?>">
                           no data found...
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
      </div>
        <!-- Download button only when data exists  -->
        <?php if (!empty($report) && !empty($report['data'])): ?>
            <div class="text-end mt-3">
                <form method="POST" action="<?php echo base_url('Admin/downloadquizreport'); ?>" style="display: inline-block; margin-right: 10px;">
                    <input type="hidden" name="quiztype"
                           value="<?php echo htmlspecialchars($selected_quiztype); ?>">
                    <input type="hidden" name="id"
                           value="<?php echo htmlspecialchars($selected_quizid); ?>">

                    <button type="submit" class="btn btn-success">
                        Download CSV Report
                    </button>
                </form>

                <form method="POST" action="<?php echo base_url('Admin/downloadquizexcel'); ?>" style="display: inline-block;">
                    <input type="hidden" name="quiztype"
                           value="<?php echo htmlspecialchars($selected_quiztype); ?>">
                    <input type="hidden" name="id"
                           value="<?php echo htmlspecialchars($selected_quizid); ?>">

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-download"></i> Download Excel Report
                    </button>
                </form>
            </div>
        <?php endif; ?>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php require_once APPPATH . 'views/layout/footer.php'; ?>
