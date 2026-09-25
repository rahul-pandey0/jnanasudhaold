<?php
/**
 * Payment Gateway Controller
 * Handles payment receipts and transactions
 */

// Polyfill for str_ends_with for PHP < 8.0
if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle) {
        $len = strlen($needle);
        if ($len === 0) {
            return true;
        }
        return (substr($haystack, -$len) === $needle);
    }
}

class Payment extends CI_ProtectedController {

    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . 'helpers/common_helper.php';
        $this->load->model('Payment_model', 'payment_model');
    }

    /**
     * List all payments
     */
    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        
        // Get data
        $total = $this->payment_model->get_payments_count($search, $status);
        $payments = $this->payment_model->get_payments($limit, $offset, $search, $status);
        
        // Calculate pagination
        $total_pages = ceil($total / $limit);
        
        $data['title'] = 'Payment Gateway Receipts';
        $data['user_email'] = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin';
        $data['user_role'] = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $data['user_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
        
        $data['payments'] = $payments;
        $data['current_page'] = $page;
        $data['total_pages'] = $total_pages;
        $data['total_records'] = $total;
        $data['search'] = $search;
        $data['limit'] = $limit;
        $data['status'] = $status;
        
        // Load menu data
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        if ($role_id) {
            $this->load->model('Menu_model', 'menu_model');
            $data['top_menus'] = $this->menu_model->get_top_menus($role_id);
        } else {
            $data['top_menus'] = array();
        }
        
        $this->load->view('payment/list', $data);
    }

    /**
     * View payment details
     */
    public function view($id = null)
    {
        if (!$id) {
            redirect(base_url('payment'));
        }
        
        $payment = $this->payment_model->get_payment_details($id);
        
        if (!$payment) {
            redirect(base_url('payment'));
        }
        
        $data['title'] = 'Payment Receipt #' . $payment['receipt_no'];
        $data['user_email'] = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin';
        $data['user_role'] = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $data['user_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
        $data['payment'] = $payment;
        
        // Load menu data
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        if ($role_id) {
            $this->load->model('Menu_model', 'menu_model');
            $data['top_menus'] = $this->menu_model->get_top_menus($role_id);
        } else {
            $data['top_menus'] = array();
        }
        
        $this->load->view('payment/view', $data);
    }

    /**
     * Search payments
     */
    public function search()
    {
        $search = isset($_POST['search']) ? trim($_POST['search']) : '';
        
        if (empty($search)) {
            redirect(base_url('payment'));
        }
        
        redirect(base_url('payment?search=' . urlencode($search)));
    }

    /**
     * Download receipt PDF by file name (expects order_no.pdf)
     * URL examples:
     *  - payment/download?file=ORD123.pdf
     *  - payment/download/ORD123.pdf
     */
    public function download($file = null)
    {
        if ($file === null && isset($_GET['file'])) {
            $file = $_GET['file'];
        }
         if (!str_ends_with($file, '.pdf')) {
        $file .= '.pdf';
        }

        // Basic validation: only allow [A-Za-z0-9_-]+.pdf
        if (empty($file) || !preg_match('/^[A-Za-z0-9_-]+\.pdf$/', $file)) {
            http_response_code(400);
            echo 'Invalid file name';
            exit;
        }

        // Build path to public/pdf/invoices/<file>
        $root = dirname(dirname(dirname(__FILE__))); // project root
       // $path = $root . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . 'invoices' . DIRECTORY_SEPARATOR . $file;
        $path = $root . '/public/pdf/invoices/' . $file;
        if (!file_exists($path) || !is_file($path)) {
        $order_no = str_replace('.pdf', '', $file);
        $this->load->model('Payment_model', 'payment_model');
        $payment = $this->payment_model->get_payment_by_order($order_no);
        if ($payment && strpos($payment['status'], 'Success') === 0) {
            $printDataRow = $this->payment_model->get_print_data_by_order($order_no);
            if ($printDataRow) {
                $this->create_pdf(array($printDataRow));
            }
        }
    }
       /* if (!file_exists($path) || !is_file($path)) {
            http_response_code(404);
            echo 'File not found';
            exit;
        }*/
        if (!file_exists($path) || !is_file($path)) {

        $order_no = str_replace('.pdf', '', $file);

        if (!isset($_SESSION)) { session_start(); }

        $_SESSION['error'] = 'No receipt yet. Receipt will be downloaded once payment is successful.';

        // Redirect back to payment view if possible
        $payment = $this->payment_model->get_payment_by_order($order_no);
        if ($payment && isset($payment['id'])) {
            redirect(base_url('payment/view/' . $payment['id']));
        } else {
            redirect(base_url('payment'));
        }
        return;
        } 
        // Stream download
        $filename = basename($path);
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($path));
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        readfile($path);
        exit;
    }

    /**
     * Generate invoices for a set of successful orders (single receipt per order)
     * Uses existing payment_gateway_status data. Saves to public/pdf/invoices/<order_no>.pdf
     */
    public function create_allInvoices()
    {
        $orders = array('17501561723633056');
        $this->load->model('Payment_model', 'payment_model');

        foreach ($orders as $order_no) {
            $payment = $this->payment_model->get_payment_by_order($order_no);
            if (!$payment) { continue; }
            if (strpos($payment['status'], 'Success') !== 0) { continue; }
            $this->create_pdf(array($payment));
        }

        echo 'Invoices generated';
    }

    /**
     * Create a PDF receipt (FPDF fallback for legacy PHP 5.2 environments)
     */
    public function create_pdf($printdata)
    {

        if (empty($printdata) || !isset($printdata[0])) {
            show_error('No data to generate PDF', 400);
        }

        $data = $printdata[0];
        $amtNumber = isset($data['price']) ? (float)$data['price'] : (isset($data['amount']) ? (float)$data['amount'] : 0);
        $order_no = isset($data['order_no']) ? $data['order_no'] : 'UNKNOWN';
        $receipt_no = isset($data['receipt_no']) ? $data['receipt_no'] : '';
        $cust_name = isset($data['cust_name']) ? $data['cust_name'] : '';
        $email_id = isset($data['email_id']) ? $data['email_id'] : '';
        $mobile_no = isset($data['mobile_no']) ? $data['mobile_no'] : '';
        $packagename = isset($data['packagename']) ? $data['packagename'] : 'Subscription';
        $pg_refno = isset($data['pg_refno']) ? $data['pg_refno'] : '';
        $currency = isset($data['currency']) ? $data['currency'] : 'INR';
        $datetime = isset($data['datetime']) ? $data['datetime'] : date('Y-m-d H:i:s');

        // Direct package taxation fields from joined package_info / package_info_offline
        // Hardcoded SAC code per requirement (ignore any joined value)
        $sac_code       = '999299';
        $package_amount = isset($data['package_amount']) ? floatval($data['package_amount']) : $amtNumber; // taxable value
        $sgst_amount    = isset($data['sgst_amount']) ? floatval($data['sgst_amount']) : 0.0;
        $cgst_amount    = isset($data['cgst_amount']) ? floatval($data['cgst_amount']) : 0.0;
        $total_amount   = isset($data['total_amount']) ? floatval($data['total_amount']) : ($package_amount + $sgst_amount + $cgst_amount);
        // Optional stored percents (informational only)
        $sgst_percent   = isset($data['sgst_percent']) ? floatval($data['sgst_percent']) : (($sgst_amount > 0 && $package_amount > 0) ? ($sgst_amount * 100 / $package_amount) : 0);
        $cgst_percent   = isset($data['cgst_percent']) ? floatval($data['cgst_percent']) : (($cgst_amount > 0 && $package_amount > 0) ? ($cgst_amount * 100 / $package_amount) : 0);
        $gst_percent    = $sgst_percent + $cgst_percent; // retained for backward display, not for computation
        $gstin = '29AARFJ8177G1ZK';

        // FPDF-first strategy for backend generation
        $root = dirname(dirname(dirname(__FILE__)));
        $outDir = $root . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . 'invoices';
        if (!is_dir($outDir)) { @mkdir($outDir, 0777, true); }
        $outfile = $outDir . DIRECTORY_SEPARATOR . $order_no . '.pdf';

        $fpdfPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . 'fpdf' . DIRECTORY_SEPARATOR . 'fpdf.php';
        if (!file_exists($fpdfPath)) {
            show_error('FPDF library missing. Expecting file at application/third_party/fpdf/fpdf.php. Download from http://www.fpdf.org/ and copy fpdf.php there.', 500);
        }
        require_once $fpdfPath;
        if (!class_exists('FPDF')) {
            show_error('FPDF class not found after including fpdf.php. Verify the file is a valid FPDF library.', 500);
        }
        // Ensure font definitions are available (core fonts live in font/ directory in full FPDF package)
        $fontDir = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . 'fpdf' . DIRECTORY_SEPARATOR . 'font' . DIRECTORY_SEPARATOR;
        if (!defined('FPDF_FONTPATH') && is_dir($fontDir)) {
            define('FPDF_FONTPATH', $fontDir);
        }
        if (!is_dir($fontDir)) {
            show_error('FPDF font directory missing. Copy the entire "font" folder from the FPDF download into application/third_party/fpdf/font/.', 500);
        }
        $neededFonts = array('helvetica.php','helveticab.php','helveticai.php','helveticabi.php');
        $missing = array();
        foreach ($neededFonts as $nf) { if (!file_exists($fontDir.$nf)) { $missing[] = $nf; } }
        if (!empty($missing)) {
            show_error('Missing FPDF font definition files: '.implode(', ', $missing).'. Ensure you copied the full font directory from the official package.', 500);
        }

        // Begin PDF (FPDF instantiation)
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();

        // Base font selection
        $baseFont = file_exists($fontDir . 'helvetica.php') ? 'helvetica' : (file_exists($fontDir . 'times.php') ? 'times' : 'courier');

        // Brand/logo (optional)
        // Check branding config first (tolerant loader for legacy CI)
        $logoConfigPath = '';
        $brandingConfigFile = APPPATH . 'config' . DIRECTORY_SEPARATOR . 'branding.php';
        if (file_exists($brandingConfigFile)) {
            // Attempt CI config component, else fallback to direct include
            if (isset($this->config) && is_object($this->config) && method_exists($this->config, 'load')) {
                $this->config->load('branding', true);
                if (method_exists($this->config, 'item')) {
                    $logoConfigPath = $this->config->item('logo_path', 'branding');
                }
            }
            if (empty($logoConfigPath)) {
                // Directly include config file to read $config
                $config = array();
                include $brandingConfigFile;
                if (isset($config['logo_path'])) { $logoConfigPath = $config['logo_path']; }
            }
        }
        $logoPathCandidates = array(
            $logoConfigPath,
            $root . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'logo.png',
            $root . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'logo.png',
            APPPATH . 'third_party' . DIRECTORY_SEPARATOR . 'fpdf' . DIRECTORY_SEPARATOR . 'logo.png'
        );
        $logoPath = '';
        foreach ($logoPathCandidates as $cand) { if (file_exists($cand)) { $logoPath = $cand; break; } }
       // Header with logo and company details
       // Top border line
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Line(10, 10, 200, 10);

        // Place logo at top-left; width ~30mm keeps aspect reasonable
        if (!empty($logoPath)) {
            $pdf->Image($logoPath,10, 20, 50);
        }

        // Academy Name (CENTER)
        $pdf->SetFont($baseFont, 'B', 18);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(25, 20);
        $pdf->Cell(190, 8, 'JNANSUDHA ENTRANCE ACADEMY LLP', 0, 1, 'C');

        // Address (CENTER)
        $pdf->SetFont($baseFont, '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(190, 9, 'D.NO 4-408/1, PADMAGOPAL, JODURASTHE', 0, 1, 'C');
        $pdf->Cell(190, 9, 'KUKKUNDOOR VILLAGE AND POST, KARKALA, Udupi', 0, 1, 'C');
        $pdf->Cell(190, 9, 'Karnataka, 576117.', 0, 1, 'C');

        // GSTIN (CENTER)
        $pdf->Ln(2);
        $pdf->SetFont($baseFont, 'B', 11);

        $pdf->Cell(190, 10, 'GSTIN: 29AARFJ8177G1ZK', 0, 1, 'C');

        // RECEIPT title
        $pdf->Ln(6);
        $pdf->SetFont($baseFont, 'B', 16);
        $pdf->Cell(190, 10, 'RECEIPT', 0, 1, 'C');
       
        // Customer info box
        $pdf->SetFont($baseFont, 'B', 10);
        $pdf->SetFillColor(245, 245, 245);
        $pdf->Cell(190, 8, 'Customer Details', 0, 1, 'L');
        $pdf->SetFont($baseFont, '', 9);
        $pdf->Cell(30, 7, 'Name', 1, 0, 'L', true);
        $pdf->Cell(160, 7, $cust_name, 1, 1);
        $pdf->Cell(30, 7, 'Email', 1, 0, 'L', true);
        $pdf->Cell(160, 7, $email_id, 1, 1);
        $pdf->Cell(30, 7, 'Mobile', 1, 0, 'L', true);
        $pdf->Cell(160, 7, $mobile_no, 1, 1);
        $pdf->Ln(3);
         
        // Order / Receipt info box
        $pdf->SetFont($baseFont, 'B', 10);
        $pdf->SetFillColor(245, 245, 245);
        $pdf->Cell(190, 8, 'Receipt Details', 0, 1, 'L');
        $pdf->SetFont($baseFont, '', 9);
        $pdf->Cell(40, 7, 'Order No', 1, 0, 'L', true);
        $pdf->Cell(60, 7, $order_no, 1, 0);
        $pdf->Cell(40, 7, 'Receipt No', 1, 0, 'L', true);
        $pdf->Cell(50, 7, $receipt_no, 1, 1);
        $pdf->Cell(40, 7, 'Date', 1, 0, 'L', true);
        $pdf->Cell(60, 7, $datetime, 1, 0);
        $pdf->Cell(40, 7, 'Currency', 1, 0, 'L', true);
        $pdf->Cell(50, 7, $currency, 1, 1);
        $pdf->Ln(3);

        // Line items table (adjusted widths to avoid overlap)
        $pdf->SetFont($baseFont, 'B', 10);
        $pdf->Cell(190, 8, 'Transaction', 0, 1, 'L');
        $pdf->SetFont($baseFont, 'B', 9);
        $pdf->SetFillColor(230, 243, 255);
        // Widths: 15 + 78 + 27 + 35 + 35 = 190
        $wSr = 15; $wPart = 78; $wSac = 27; $wPg = 35; $wAmt = 35;
        $rowH = 8;
        $pdf->Cell($wSr, $rowH, 'Sr', 1, 0, 'C', true);
        $pdf->Cell($wPart, $rowH, 'Particulars', 1, 0, 'C', true);
        $pdf->Cell($wSac, $rowH, 'SAC', 1, 0, 'C', true);
        $pdf->Cell($wPg,  $rowH, 'PG Ref No', 1, 0, 'C', true);
        $pdf->Cell($wAmt, $rowH, 'Amount (Rs.)', 1, 1, 'C', true);
        $pdf->SetFont($baseFont, '', 9);
        // Data row with wrapped particulars
           
        $rowH = 8;  // Minimum row height
        $yStart = $pdf->GetY();

        // Measure the height of Particulars text WITHOUT border
        $xPart = 10 + $wSr; // Sr column offset
        $pdf->SetXY($xPart, $yStart);
        $pdf->MultiCell($wPart, 6, $packagename, 0, 'L');

        // Calculate actual height used
        $partHeight = $pdf->GetY() - $yStart;
        $maxH = max($rowH, $partHeight);

        // Draw the full row with SAME height (borders only)
        $pdf->SetXY(10, $yStart); // Reset X to page margin
        $pdf->Cell($wSr,  $maxH, '1', 1, 0, 'C');
        $pdf->Cell($wPart,$maxH, '', 1, 0);   // empty cell for border
        $pdf->Cell($wSac, $maxH, $sac_code, 1, 0, 'C');
        $pdf->Cell($wPg,  $maxH, $pg_refno, 1, 0, 'C');
        $pdf->Cell($wAmt, $maxH, number_format($package_amount, 2), 1, 1, 'R');

        // Write Particulars text again inside its cell
        $pdf->SetXY($xPart, $yStart);
        $pdf->MultiCell($wPart, 6, $packagename, 0, 'L');

        $pdf->Ln(2);

        // Totals section (package amount, tax components, grand total)
        $pdf->SetFont($baseFont, 'B', 10);
        $pdf->SetX(10);
        if ($sgst_amount > 0) { $pdf->Cell(155, 8, 'SGST (@ 9%)', 1, 0, 'L'); $pdf->Cell(35, 8, number_format($sgst_amount, 2), 1, 1, 'R'); }
        if ($cgst_amount > 0) { $pdf->Cell(155, 8, 'CGST (@ 9%) ', 1, 0, 'L'); $pdf->Cell(35, 8, number_format($cgst_amount, 2), 1, 1, 'R'); }
        $pdf->SetX(10);
        $pdf->Cell(155, 8, 'Grand Total', 1, 0, 'L');
        $pdf->Cell(35, 8, number_format($total_amount, 2), 1, 1, 'R');
        $pdf->Ln(4);

        // Amount in words (Indian numbering system) based on total amount
        $pdf->SetFont($baseFont, 'B', 10);
        $pdf->Cell(190, 8, 'Amount in Words', 0, 1, 'L');
        $pdf->SetFont($baseFont, '', 9);
        $pdf->MultiCell(190, 7, $this->amount_in_words_india($total_amount) . ' only');
        $pdf->Ln(2);

        // (Removed separate GST section per requirement)
        $pdf->Ln(2);

        // Footer note
        $pdf->SetFont($baseFont, 'I', 8);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->MultiCell(0, 5, "This is an electronic copy; signature is not required.\nFee once paid will not be refunded.");
        $pageHeight = $pdf->GetPageHeight();  
        $bottomMargin = 15;                  // distance from bottom
        $y = $pageHeight - $bottomMargin;

        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Line(10, $y, 200, $y);

        $pdf->Output($outfile, 'F');
        if (file_exists($outfile)) { return $outfile; }

        // Fallback: wkhtmltopdf if installed and FPDF output missing
        $wkPath = 'C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe';
        if (file_exists($wkPath)) {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost:8000';
            $base = $scheme . '://' . $host;
            $viewUrl = $base . '/index.php?controller=Payment&method=view_receipt&order_no=' . urlencode($order_no);
            $cmd = '"' . $wkPath . '" --margin-top 10mm --margin-bottom 12mm --margin-left 10mm --margin-right 10mm "' . $viewUrl . '" "' . $outfile . '"';
            @exec($cmd, $execOut, $execRet);
            if ($execRet === 0 && file_exists($outfile)) {
                return $outfile; }
        }
        show_error('PDF generation failed (FPDF primary + wkhtmltopdf fallback).', 500);
    }

    // Convert numeric amount to words (Indian numbering system)
    private function amount_in_words_india($num)
    {
        $num = round($num);
        if ($num == 0) return 'Zero Rupees';
        $ones = array('', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen');
        $tens = array('', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety');
        $segments = array();

        $get2 = function($n) use ($ones, $tens) {
            if ($n < 20) return $ones[$n];
            $t = intval($n / 10);
            $o = $n % 10;
            return trim($tens[$t] . ' ' . $ones[$o]);
        };

        $crores = intval($num / 10000000);
        $num %= 10000000;
        $lakhs = intval($num / 100000);
        $num %= 100000;
        $thousands = intval($num / 1000);
        $num %= 1000;
        $hundreds = intval($num / 100);
        $num %= 100;
        $last = $num;

        if ($crores) $segments[] = $get2($crores) . ' Crore';
        if ($lakhs) $segments[] = $get2($lakhs) . ' Lakh';
        if ($thousands) $segments[] = $get2($thousands) . ' Thousand';
        if ($hundreds) $segments[] = $ones[$hundreds] . ' Hundred';
        if ($last) $segments[] = $get2($last);

        return trim(implode(' ', $segments)) . ' Rupees';
    }

    /**
     * Render HTML receipt view for wkhtmltopdf to fetch
     */
    public function view_receipt($order_no = null)
    {
        if ($order_no === null && isset($_GET['order_no'])) { $order_no = trim($_GET['order_no']); }
        if (empty($order_no)) { show_error('order_no is required', 400); }
        $this->load->model('Payment_model', 'payment_model');
        $row = $this->payment_model->get_print_data_by_order($order_no);
        if (!$row) { show_error('Receipt data not found', 404); }
        // Reuse existing payment/view.php template if compatible, else output minimal HTML
        if (file_exists(APPPATH . 'views' . DIRECTORY_SEPARATOR . 'payment' . DIRECTORY_SEPARATOR . 'view.php')) {
            $data = array('payment' => $row);
            $this->load->view('payment/view', $data);
            return;
        }
        header('Content-Type: text/html; charset=UTF-8');
        echo '<html><head><title>Receipt</title></head><body>';
        echo '<h2 style="text-align:center;">RECEIPT</h2>';
        echo '<p><strong>Order No:</strong> ' . htmlspecialchars($row['order_no']) . '</p>';
        echo '<p><strong>Receipt No:</strong> ' . htmlspecialchars(isset($row['receipt_no']) ? $row['receipt_no'] : '') . '</p>';
        echo '<p><strong>Date:</strong> ' . htmlspecialchars(isset($row['datetime']) ? $row['datetime'] : '') . '</p>';
        echo '<p><strong>Name:</strong> ' . htmlspecialchars(isset($row['cust_name']) ? $row['cust_name'] : '') . '</p>';
        echo '<p><strong>Email:</strong> ' . htmlspecialchars(isset($row['email_id']) ? $row['email_id'] : '') . '</p>';
        echo '<p><strong>Mobile:</strong> ' . htmlspecialchars(isset($row['mobile_no']) ? $row['mobile_no'] : '') . '</p>';
        echo '<hr />';
        echo '<p><strong>Particulars:</strong> ' . htmlspecialchars(isset($row['packagename']) ? $row['packagename'] : 'Subscription') . '</p>';
        echo '<p><strong>PG Ref No:</strong> ' . htmlspecialchars(isset($row['pg_refno']) ? $row['pg_refno'] : '') . '</p>';
        $amtNumber = isset($row['price']) ? (float)$row['price'] : (isset($row['amount']) ? (float)$row['amount'] : 0);
        echo '<p><strong>Amount (Rs.):</strong> ' . number_format($amtNumber, 2) . '</p>';
        echo '<p style="font-style:italic; font-size:12px;">This is an electronic copy, signature is not required. Fee once paid will not be refunded.</p>';
        echo '</body></html>';
    }

    /**
     * Finalize an initialized payment: set status=Success, update PG ref, assign receipt_no, and generate PDF.
     */
    public function finalize_payment()
    {
        $order_no = isset($_POST['order_no']) ? trim($_POST['order_no']) : (isset($_GET['order_no']) ? trim($_GET['order_no']) : '');
        $pg_refno = isset($_POST['pg_refno']) ? trim($_POST['pg_refno']) : (isset($_GET['pg_refno']) ? trim($_GET['pg_refno']) : '');
        if ($order_no === '') { show_error('order_no is required', 400); }
        if ($pg_refno === '') { show_error('pg_refno is required', 400); }

        $this->load->model('Payment_model', 'payment_model');
        $payment = $this->payment_model->get_payment_by_order($order_no);
        if (!$payment) { show_error('Order not found', 404); }

        // Update status to Success and set PG Ref No
        $update = array('status' => 'Success', 'pg_refno' => $pg_refno);
        $this->payment_model->update_payment($payment['id'], $update);

        // Ensure receipt number exists and is synced
        $this->payment_model->ensure_receipt_no_for_order($order_no);

        // Generate the PDF
        $row = $this->payment_model->get_print_data_by_order($order_no);
        if ($row) {
            $outfile = $this->create_pdf(array($row));
            if (!isset($_SESSION)) { session_start(); }
            $_SESSION['success'] = 'Payment finalized. Receipt #' . htmlspecialchars(isset($row['receipt_no']) ? $row['receipt_no'] : '') . ' PDF generated.';
            redirect(base_url('payment/view/' . $payment['id']));
            return; // safety
        }
        if (!isset($_SESSION)) { session_start(); }
        $_SESSION['error'] = 'Payment finalized but PDF generation skipped (no data)';
        redirect(base_url('payment'));
    }

    /**
     * Explicitly regenerate receipt PDF for a given order number.
     * For offline payments, also updates the offline_assigned_package status to 'paid'
     * and records the receipt generation time.
     */
    public function regenerate_receipt($order_no = null)
    {
        if ($order_no === null && isset($_GET['order_no'])) { $order_no = trim($_GET['order_no']); }
        if (empty($order_no)) { show_error('order_no is required', 400); }

        $this->load->model('Payment_model', 'payment_model');
        $payment = $this->payment_model->get_payment_by_order($order_no);
        if (!$payment) { show_error('Order not found', 404); }
        if (strpos($payment['status'], 'Success') !== 0) {
        if (!isset($_SESSION)) { session_start(); }
        $_SESSION['error'] = 'No receipt yet. Receipt will be generated once payment is successful.';
        redirect(base_url('payment/view/' . $payment['id']));
        return;
        }
        // Ensure receipt number is present before generating PDF
        $this->payment_model->ensure_receipt_no_for_order($order_no);

        // Update offline package status if this is an offline payment
        // This sets status = 'paid' and records receipt_time
        // Note: package_type field from payment_gateway_status table (can be 'online' or 'offline')
        $package_type = isset($payment['package_type']) ? strtolower($payment['package_type']) : 'online';
        if ($package_type === 'offline') {
            $this->payment_model->update_offline_package_receipt($order_no);
        }
        print_r($package_type);
        $row = $this->payment_model->get_print_data_by_order($order_no);
        print_r($row);
        if (!$row) { show_error('No data to generate PDF', 404); }
        $outfile = $this->create_pdf(array($row));
        if (!isset($_SESSION)) { session_start(); }
        $_SESSION['success'] = 'Receipt regenerated successfully.';
        redirect(base_url('payment/view/' . $payment['id']));
    }

    /**
     * Explicitly generate receipt number for a non-success order with null receipt_no
     * Inserts into receipt_no and updates payment_gateway_status.receipt_no
     */
    public function generate_receipt_no($order_no = null)
    {
        if ($order_no === null && isset($_GET['order_no'])) { $order_no = trim($_GET['order_no']); }
        if (empty($order_no)) { show_error('order_no is required', 400); }

        $this->load->model('Payment_model', 'payment_model');
        $result = $this->payment_model->ensure_receipt_no_for_order($order_no);
        if ($result === false) {
            show_error('Could not generate receipt number (check status is not Success and receipt_no is empty)', 400);
        }

        // Fetch payment for redirect context
        $payment = $this->payment_model->get_payment_by_order($order_no);

        // generate the PDF receipt after assigning receipt_no
        $row = $this->payment_model->get_print_data_by_order($order_no);
        if ($row) {
            $outfile = $this->create_pdf(array($row));
            if (!isset($_SESSION)) { session_start(); }
            $_SESSION['success'] = 'Receipt number created: ' . (int)$result . ' and PDF generated.';
            if ($payment && isset($payment['id'])) {
                redirect(base_url('payment/view/' . $payment['id']));
            } else {
                redirect(base_url('payment'));
            }
            return; 
        }
        if (!isset($_SESSION)) { session_start(); }
        $_SESSION['error'] = 'Receipt number created: ' . (int)$result . ' (PDF generation skipped: no data)';
        if ($payment && isset($payment['id'])) {
            redirect(base_url('payment/view/' . $payment['id']));
        } else {
            redirect(base_url('payment'));
        }
    }











    /**
     * Edit payment record
     */
    public function edit($id = null)
    {
        if (!$id) {
            redirect(base_url('payment'));
        }
        // Ensure model loaded
        if (!isset($this->payment_model)) {
            $this->load->model('Payment_model', 'payment_model');
        }
        $payment = $this->payment_model->get_payment($id);
        
        if (!$payment) {
            redirect(base_url('payment'));
        }
        // Business rule adjustment:
        // Allow editing a Success record ONLY if receipt_no is still empty (to auto-generate it).
        // Block editing if already Success AND receipt_no present.
        if (isset($payment['status']) && $payment['status'] === 'Success' && !empty($payment['receipt_no'])) {
            show_error('Successful receipt already finalized. Use Download/Regenerate only.', 403);
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Allow updating pg_refno (gateway reference), payment_refno (internal) and status for non-success records
            $update_data = array(
                'payment_refno' => isset($_POST['payment_refno']) ? trim($_POST['payment_refno']) : (isset($payment['payment_refno']) ? $payment['payment_refno'] : ''),
                'pg_refno' => isset($_POST['pg_refno']) ? trim($_POST['pg_refno']) : (isset($payment['pg_refno']) ? $payment['pg_refno'] : ''),
                'status' => isset($_POST['status']) ? trim($_POST['status']) : $payment['status']
            );
            
            // Capture old status
            $old = $this->payment_model->get_payment($id);
            $oldStatus = $old ? $old['status'] : null;

            if ($this->payment_model->update_payment($id, $update_data)) {
                // If status changed to Success, generate receipt for this order_no
                $newStatus = $update_data['status'];
                if (!empty($old['order_no'])) {
                    if ($newStatus === 'Success') {
                        // If transitioned to Success OR already Success but missing receipt_no, ensure receipt number
                        if ($oldStatus !== 'Success' || empty($old['receipt_no'])) {
                            $this->payment_model->ensure_receipt_no_for_order($old['order_no']);
                            // Refresh print data and generate PDF
                            $printDataRow = $this->payment_model->get_print_data_by_order($old['order_no']);
                            if ($printDataRow) { $this->create_pdf(array($printDataRow)); }
                        }
                    }
                    // No action needed for non-Success statuses
                }
                json_response(true, 'Payment record updated successfully');
            } else {
                json_response(false, 'Failed to update payment record');
            }
            return; // Prevent view rendering after JSON response
        }
        // Prepare data for view (single render)
        $data = array();
        $data['title'] = 'Edit Payment';
        $data['user_email'] = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin';
        $data['user_role'] = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $data['user_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
        $data['payment'] = $payment;
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        if ($role_id) {
            $this->load->model('Menu_model', 'menu_model');
            $data['top_menus'] = $this->menu_model->get_top_menus($role_id);
        } else {
            $data['top_menus'] = array();
        }
        $this->load->view('payment/edit', $data);
    }
    public function update_name()
{
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('payment');
        return;
    }

    $id   = isset($_POST['payment_id']) ? trim($_POST['payment_id']) : null;
    $name = isset($_POST['cust_name']) ? trim($_POST['cust_name']) : '';

    if (!$id) {
        $_SESSION['error'] = "Invalid Payment ID";
        redirect('payment');
        return;
    }

    if ($name === '') {
        $_SESSION['error'] = "Customer name cannot be empty";
        redirect('payment');
        return;
    }

    // Ensure model loaded
    if (!isset($this->payment_model)) {
        $this->load->model('Payment_model', 'payment_model');
    }

    $updated = $this->payment_model->update_customer_name($id, $name);

    if ($updated) {
        $_SESSION['success'] = "Customer name updated successfully";
    } else {
        $_SESSION['error'] = "Failed to update customer name";
    }

    redirect('index');
}
}
?>
