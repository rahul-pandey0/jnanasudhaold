# Payment Gateway Module Documentation

## Overview
The Payment Gateway module manages payment receipts and transactions from the `payment_gateway_status` table. It provides complete CRUD operations, reporting, and analytics functionality.

## Table Structure
```sql
CREATE TABLE `payment_gateway_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` varchar(20) DEFAULT NULL,
  `order_no` varchar(20) DEFAULT NULL,
  `amount` decimal(11,2) DEFAULT NULL,
  `currency` varchar(3) DEFAULT NULL,
  `udf_1` varchar(30) DEFAULT NULL,
  `cust_name` varchar(100) DEFAULT NULL,
  `email_id` varchar(50) DEFAULT NULL,
  `mobile_no` varchar(12) DEFAULT NULL,
  `packageid` varchar(45) DEFAULT NULL,
  `packagename` varchar(100) DEFAULT NULL,
  `pg_refno` varchar(20) DEFAULT NULL,
  `payment_refno` varchar(20) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `status_reason` varchar(100) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `receipt_no` int(11) DEFAULT NULL,
  `package_type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index` (`mobile_no`)
)
```

## Module Features

### 1. Payment Listing
- **URL**: `/payment/`
- **Description**: View all payment records with pagination
- **Features**:
  - Search by customer name, email, mobile, or order number
  - Pagination (20 records per page)
  - Quick links to view/edit payments
  - Export to CSV
  - View statistics and revenue reports

### 2. View Payment Details
- **URL**: `/payment/view/{id}`
- **Description**: View complete details of a specific payment
- **Information Displayed**:
  - Order details (Order No, Receipt No)
  - Customer information (Name, Email, Mobile)
  - Payment details (Amount, Status, Status Reason)
  - Package information (Package ID, Name, Type)
  - Gateway references (Payment Ref, Gateway Ref)
  - Transaction timestamp
  - Quick info sidebar

### 3. Edit Payment
- **URL**: `/payment/edit/{id}`
- **Description**: Update payment status and reason
- **Editable Fields**:
  - Payment Status (Success, Failed, Pending, Cancelled)
  - Status Reason

### 4. Add Payment (Manual Entry)
- **URL**: `/payment/add`
- **Description**: Manually add a payment record
- **Fields**:
  - Merchant ID
  - Order Number (required)
  - Customer information (Name, Email, Mobile - required)
  - Amount (required)
  - Currency (INR, USD, EUR, GBP)
  - Package details
  - Payment Status
  - Status Reason

### 5. Statistics Dashboard
- **URL**: `/payment/statistics`
- **Description**: View payment statistics and breakdown
- **Statistics**:
  - Total transactions
  - Total amount collected
  - Successful transactions
  - Failed transactions
  - Pending transactions
  - Success/Failure/Pending percentages
  - Average transaction amount

### 6. Revenue Report
- **URL**: `/payment/revenue_report`
- **Description**: View revenue by date range
- **Features**:
  - Filter by date range
  - Daily revenue breakdown
  - Total revenue summary
  - Transaction count
  - Average per transaction

### 7. Export to CSV
- **URL**: `/payment/export`
- **Description**: Download all payments as CSV file
- **Includes**:
  - ID, Order No, Customer Name, Mobile, Email
  - Amount, Currency, Status, Date, Receipt No

## Model Methods

### Payment_model.php

#### `get_payments($limit, $offset, $search)`
Get paginated list of payments with optional search
```php
$payments = $this->payment_model->get_payments(20, 0, 'john');
```

#### `get_payments_count($search)`
Get total count of payments (for pagination)
```php
$total = $this->payment_model->get_payments_count('john');
```

#### `get_payment($id)`
Get single payment by ID
```php
$payment = $this->payment_model->get_payment(1);
```

#### `get_payment_by_order($order_no)`
Get payment by order number
```php
$payment = $this->payment_model->get_payment_by_order('ORD-12345');
```

#### `get_payments_by_mobile($mobile_no, $limit)`
Get all payments by customer mobile number
```php
$payments = $this->payment_model->get_payments_by_mobile('9876543210', 10);
```

#### `get_payment_stats()`
Get overall payment statistics
```php
$stats = $this->payment_model->get_payment_stats();
// Returns: total_transactions, total_amount, successful, failed, pending
```

#### `get_payments_by_status($status, $limit)`
Get payments filtered by status
```php
$success_payments = $this->payment_model->get_payments_by_status('Success', 100);
```

#### `get_revenue_by_date_range($from_date, $to_date)`
Get revenue data grouped by date
```php
$revenue = $this->payment_model->get_revenue_by_date_range('2024-01-01', '2024-01-31');
```

#### `insert_payment($data)`
Insert new payment record
```php
$data = array(
    'order_no' => 'ORD-12345',
    'cust_name' => 'John Doe',
    'amount' => 500.00,
    'status' => 'Success'
);
$id = $this->payment_model->insert_payment($data);
```

#### `update_payment($id, $data)`
Update payment record
```php
$data = array(
    'status' => 'Failed',
    'status_reason' => 'Gateway timeout'
);
$this->payment_model->update_payment(1, $data);
```

#### `delete_payment($id)`
Delete payment record
```php
$this->payment_model->delete_payment(1);
```

#### `get_payment_by_receipt($receipt_no)`
Get payment by receipt number
```php
$payment = $this->payment_model->get_payment_by_receipt(12345);
```

#### `get_payment_details($id)`
Get formatted payment details with badges
```php
$payment = $this->payment_model->get_payment_details(1);
// Returns: amount_formatted, datetime_formatted, status_badge
```

## Controller Methods

### Payment.php

#### `index()`
List all payments with search and pagination

#### `view($id)`
View payment details

#### `search()`
Search payments (POST)

#### `statistics()`
View payment statistics

#### `export()`
Export payments to CSV (GET)

#### `get_by_status($status)`
API endpoint to get payments by status (JSON response)

#### `revenue_report()`
View revenue report with date range filter

#### `add()`
Add new payment record manually (GET/POST)

#### `edit($id)`
Edit payment record (GET/POST)

## Views

### payment/list.php
Main listing page with search and pagination

### payment/view.php
Detailed payment view with all information

### payment/edit.php
Edit payment status and reason

### payment/add.php
Add new payment record

### payment/statistics.php
Payment statistics dashboard

### payment/revenue_report.php
Revenue report by date range

## Access Control

All payment pages are protected with `CI_ProtectedController` - requires authenticated user session.

## Database Indexing

- Primary Key: `id`
- Index on `mobile_no` for faster customer lookups
- Consider adding indexes on: `order_no`, `email_id`, `datetime`

## Usage Examples

### In a View
```php
<a href="<?php echo base_url('payment/view/1'); ?>">View Payment</a>
<?php echo $this->payment_model->get_status_badge('Success'); ?>
```

### In a Controller
```php
$this->load->model('Payment_model', 'payment_model');
$stats = $this->payment_model->get_payment_stats();
$revenue = $this->payment_model->get_revenue_by_date_range('2024-01-01', '2024-01-31');
```

## API Endpoints

### Get Payments by Status
```
GET /payment/get_by_status/Success
Response: JSON with success, message, and payments data
```

## CSV Export Format
```
ID,Order No,Customer Name,Mobile,Email,Amount,Currency,Status,Date,Receipt No
1,ORD-12345,John Doe,9876543210,john@example.com,500.00,INR,Success,2024-01-15 10:30:00,12345
```

## Error Handling
- Invalid payment ID: Redirects to payment list
- Database errors: Returns false from model methods
- API errors: Returns JSON with error message

## Security Considerations
- All input is escaped using `$db->escape()`
- Only authenticated users can access the module
- Payment details are read-only for critical fields
- Only status and reason can be edited

## Future Enhancements
- Payment status webhooks
- Refund management
- Payment reconciliation
- Advanced filtering and reports
- Payment batch operations
- Integration with payment gateway APIs

---
**Version**: 1.0  
**Last Updated**: November 28, 2025
