<?php
session_start();
require '../dbCon.php';
require '../vendor/autoload.php';

use setasign\Fpdi\Fpdi;

if (!isset($_SESSION['admin'])) {
    die('Unauthorized access');
}

class ProfessionalPDF extends Fpdi
{
    // Properties
    private $summaryData = []; // Holds summary data
    private $paymentData = []; // Holds payment items
    private $companyName = 'Village Chef';
    private $companyTagline = 'Delicious food, delivered';
    private $companyAddress = '123 Food Street, Cuisine City, CC 12345';
    private $companyPhone = '+1 (555) 123-4567';
    private $companyEmail = 'support@villagechef.com';
    private $companyWebsite = 'www.villageche.com';

    // Color scheme
    private $primaryColor = [41, 128, 185]; // Blue
    private $secondaryColor = [52, 152, 219]; // Lighter blue
    private $accentColor = [230, 126, 34]; // Orange
    private $lightGray = [245, 245, 245];
    private $mediumGray = [189, 195, 199];
    private $darkGray = [52, 73, 94];
    private $white = [255, 255, 255];

    // Constructor to set margins and defaults
    public function __construct()
    {
        parent::__construct();
        $this->SetMargins(15, 15, 15);
        $this->SetAutoPageBreak(true, 25);

        // Set default font
        $this->SetFont('Helvetica', '', 10);
    }

    // Set data methods
    public function setSummaryData($data)
    {
        $this->summaryData = $data;
    }

    public function setPaymentData($data)
    {
        $this->paymentData = $data;
    }

    // Custom rounded rectangle function
    public function RoundedRect($x, $y, $w, $h, $r, $style = '', $fill = false)
    {
        $k = $this->k;
        $hp = $this->h;
        if ($style == 'F')
            $op = 'f';
        elseif ($style == 'FD' || $style == 'DF')
            $op = 'B';
        else
            $op = 'S';
        $MyArc = 4 / 3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m', ($x + $r) * $k, ($hp - $y) * $k));
        $xc = $x + $w - $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - $y) * $k));

        $this->_Arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);
        $xc = $x + $w - $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $yc) * $k));

        $this->_Arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x + $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - ($y + $h)) * $k));

        $this->_Arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);
        $xc = $x + $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $yc) * $k));

        $this->_Arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    // Helper method for RoundedRect
    protected function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c ',
            $x1 * $this->k,
            ($h - $y1) * $this->k,
            $x2 * $this->k,
            ($h - $y2) * $this->k,
            $x3 * $this->k,
            ($h - $y3) * $this->k
        ));
    }

    // Header
    public function Header()
    {
        // Logo and background
        $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->RoundedRect(15, 10, 40, 20, 3, 'F');

        // If you have a logo, use this instead of the colored rectangle
        // $this->Image('./uploads/logo.png', 15, 10, 40);

        // Company name
        $this->SetFont('Helvetica', 'B', 18);
        $this->SetTextColor($this->white[0], $this->white[1], $this->white[2]);
        $this->SetXY(20, 15);
        $this->Cell(30, 10, 'Village Chef', 0, 0, 'C');

        // Company info
        $this->SetFont('Helvetica', '', 9);
        $this->SetTextColor($this->darkGray[0], $this->darkGray[1], $this->darkGray[2]);
        $this->SetXY(120, 10);
        $this->Cell(75, 5, '123 Om Touenship - 3 , Pasodara ', 0, 1, 'R');
        $this->SetXY(120, 15);
        $this->Cell(75, 5, 'Phone: +91 9727181143', 0, 1, 'R');
        $this->SetXY(120, 20);
        $this->Cell(75, 5, 'Email: support@villagechef.com', 0, 1, 'R');
        $this->SetXY(120, 25);
        $this->Cell(75, 5, 'Web: www.villagechef.com', 0, 1, 'R');

        // Horizontal line
        $this->SetDrawColor($this->mediumGray[0], $this->mediumGray[1], $this->mediumGray[2]);
        $this->Line(15, 35, 195, 35);

        $this->Ln(25); // Space after header
    }

    // Footer
    public function Footer()
    {
        $this->SetY(-25);

        // Horizontal line
        $this->SetDrawColor($this->mediumGray[0], $this->mediumGray[1], $this->mediumGray[2]);
        $this->Line(15, $this->GetY(), 195, $this->GetY());

        // Thank you message
        $this->SetFont('Helvetica', 'I', 9);
        $this->SetTextColor($this->darkGray[0], $this->darkGray[1], $this->darkGray[2]);
        $this->Cell(0, 10, 'Thank you ... For any questions regarding this report, please contact us.', 0, 1, 'C');

        // Page number
        $this->SetFont('Helvetica', '', 9);
        $this->Cell(0, 5, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Report Title
    public function ReportTitle()
    {
        $this->SetFont('Helvetica', 'B', 18);
        $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->Cell(0, 10, 'PAYMENT REPORT', 0, 1, 'C');

        // Report details
        $this->SetFont('Helvetica', '', 10);
        $this->SetTextColor($this->darkGray[0], $this->darkGray[1], $this->darkGray[2]);
        $this->Cell(0, 6, 'Report #: PR-' . date('Ymd') . '-' . rand(1000, 9999), 0, 1, 'C');
        $this->Cell(0, 6, 'Generated on: ' . date('F d, Y, h:i A'), 0, 1, 'C');

        $this->Ln(5);
    }

    // Payment Summary Section with cards
    public function buildSummary()
    {
        $this->ReportTitle();

        // Section title
        $this->SetFillColor($this->lightGray[0], $this->lightGray[1], $this->lightGray[2]);
        $this->Rect(15, $this->GetY(), 180, 10, 'F');
        $this->SetFont('Helvetica', 'B', 12);
        $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->SetXY(20, $this->GetY() + 2);
        $this->Cell(170, 6, 'PAYMENT SUMMARY', 0, 1);

        $this->Ln(10);

        // Summary cards
        $metrics = [
            ['title' => 'Total Revenue', 'value' => 'Rs. ' . number_format($this->summaryData['total_successful_amount'], 2), 'icon' => '$'],
            ['title' => 'Total Payments', 'value' => $this->summaryData['total_payments'], 'icon' => '#'],
            ['title' => 'Successful', 'value' => $this->summaryData['successful_payments'], 'icon' => '@'],
            ['title' => 'Refunded', 'value' => $this->summaryData['refunded_payments'], 'icon' => 'X']
        ];

        $cardWidth = 85;
        $cardHeight = 40;
        $margin = 10;
        $startY = $this->GetY();

        foreach ($metrics as $index => $metric) {
            $row = floor($index / 2);
            $col = $index % 2;

            $x = 15 + $col * ($cardWidth + $margin);
            $y = $startY + $row * ($cardHeight + $margin);

            // Card background
            $this->SetFillColor(255, 255, 255);
            $this->SetDrawColor($this->mediumGray[0], $this->mediumGray[1], $this->mediumGray[2]);
            $this->RoundedRect($x, $y, $cardWidth, $cardHeight, 4, 'FD');

            // Card title
            $this->SetFont('Helvetica', '', 10);
            $this->SetTextColor($this->darkGray[0], $this->darkGray[1], $this->darkGray[2]);
            $this->SetXY($x + 10, $y + 8);
            $this->Cell($cardWidth - 20, 6, $metric['title'], 0, 1);

            // Card value
            $this->SetFont('Helvetica', 'B', 16);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->SetXY($x + 10, $y + 18);
            $this->Cell($cardWidth - 20, 10, $metric['value'], 0, 1);

            // Icon
            $this->SetFont('Helvetica', 'B', 14);
            $this->SetXY($x + $cardWidth - 15, $y + 5);
            $this->Cell(10, 10, $metric['icon'], 0, 0, 'R');
        }

        $this->Ln($cardHeight * 2 + $margin * 2);

        // Period summary
        $this->SetFont('Helvetica', '', 10);
        $this->SetTextColor($this->darkGray[0], $this->darkGray[1], $this->darkGray[2]);
        $this->Cell(0, 10, 'This report includes payment data from ' . date('F d, Y', strtotime('-30 days')) . ' to ' . date('F d, Y'), 0, 1, 'C');

        $this->Ln(5);
    }

    // Create a badge with status color
    public function StatusBadge($x, $y, $status, $width = 25)
    {
        // Set color based on status
        $color = $this->getStatusColor($status);

        $this->SetFillColor($color[0], $color[1], $color[2]);
        $this->RoundedRect($x, $y, $width, 6, 3, 'F');

        $this->SetFont('Helvetica', 'B', 8);
        $this->SetTextColor(255, 255, 255);
        $this->SetXY($x, $y);
        $this->Cell($width, 6, ucfirst($status), 0, 0, 'C');

        // Reset text color
        $this->SetTextColor(0, 0, 0);
    }

    // Payment Items Table
    public function buildPaymentTable()
    {
        // Section title
        $this->SetFillColor($this->lightGray[0], $this->lightGray[1], $this->lightGray[2]);
        $this->Rect(15, $this->GetY(), 180, 10, 'F');
        $this->SetFont('Helvetica', 'B', 12);
        $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->SetXY(20, $this->GetY() + 2);
        $this->Cell(170, 6, 'TRANSACTION DETAILS', 0, 1);

        $this->Ln(10);

        // Column widths
        $cols = [
            ['width' => 15, 'title' => 'ID', 'align' => 'C'],
            ['width' => 25, 'title' => 'Order ID', 'align' => 'C'],
            ['width' => 40, 'title' => 'Customer', 'align' => 'L'],
            ['width' => 25, 'title' => 'Amount', 'align' => 'R'],
            ['width' => 25, 'title' => 'Method', 'align' => 'L'],
            ['width' => 25, 'title' => 'Status', 'align' => 'C'],
            ['width' => 25, 'title' => 'Date', 'align' => 'C']
        ];

        // Table header
        $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Helvetica', 'B', 10);

        $x = 15;
        foreach ($cols as $col) {
            $this->SetXY($x, $this->GetY());
            $this->Cell($col['width'], 10, $col['title'], 0, 0, $col['align'], true);
            $x += $col['width'];
        }
        $this->Ln(12);

        // Table rows
        $this->SetTextColor($this->darkGray[0], $this->darkGray[1], $this->darkGray[2]);
        $this->SetFont('Helvetica', '', 9);

        $fill = false;
        $rowHeight = 12;

        foreach ($this->paymentData as $index => $payment) {
            // Check if we need a new page
            if ($this->GetY() > 250) {
                $this->AddPage();

                // Repeat table header on new page
                $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
                $this->SetTextColor(255, 255, 255);
                $this->SetFont('Helvetica', 'B', 10);

                $x = 15;
                foreach ($cols as $col) {
                    $this->SetXY($x, $this->GetY());
                    $this->Cell($col['width'], 10, $col['title'], 0, 0, $col['align'], true);
                    $x += $col['width'];
                }
                $this->Ln(12);

                $this->SetTextColor($this->darkGray[0], $this->darkGray[1], $this->darkGray[2]);
                $this->SetFont('Helvetica', '', 9);
            }

            // Zebra striping
            if ($fill) {
                $this->SetFillColor(249, 249, 249);
            } else {
                $this->SetFillColor(255, 255, 255);
            }

            $x = 15;
            $y = $this->GetY();

            // Draw row background
            $this->Rect($x, $y, 180, $rowHeight, 'F');

            // ID
            $this->SetXY($x, $y + 2);
            $this->Cell($cols[0]['width'], 8, '#' . $payment['payment_id'], 0, 0, $cols[0]['align']);
            $x += $cols[0]['width'];

            // Order ID
            $this->SetXY($x, $y + 2);
            $this->Cell($cols[1]['width'], 8, '' . $payment['order_id'], 0, 0, $cols[1]['align']);
            $x += $cols[1]['width'];

            // Customer
            $this->SetXY($x, $y + 2);
            $customerName = substr($payment['first_name'] . ' ' . $payment['last_name'], 0, 20);
            $this->Cell($cols[2]['width'], 8, $customerName, 0, 0, $cols[2]['align']);
            $x += $cols[2]['width'];

            // Amount
            $this->SetXY($x, $y + 2);
            $this->Cell($cols[3]['width'], 8, 'Rs. ' . number_format($payment['amount'], 2), 0, 0, $cols[3]['align']);
            $x += $cols[3]['width'];

            // Method
            $this->SetXY($x, $y + 2);
            $this->Cell($cols[4]['width'], 8, ucfirst($payment['payment_method']), 0, 0, $cols[4]['align']);
            $x += $cols[4]['width'];

            // Status (with badge)
            $this->SetXY($x, $y + 2);
            $this->StatusBadge($x + 2.5, $y + 3, $payment['payment_status'], 20);
            $x += $cols[5]['width'];

            // Date
            $this->SetXY($x, $y + 2);
            $this->Cell($cols[6]['width'], 8, date('M d, Y', strtotime($payment['payment_date'])), 0, 0, $cols[6]['align']);

            $this->Ln($rowHeight);
            $fill = !$fill;

            // Add a subtle separator line
            if ($index < count($this->paymentData) - 1) {
                $this->SetDrawColor(240, 240, 240);
                $this->Line(15, $this->GetY(), 195, $this->GetY());
            }
        }

        $this->Ln(10);

        // Totals section
        $this->SetFillColor($this->lightGray[0], $this->lightGray[1], $this->lightGray[2]);
        $this->Rect(15, $this->GetY(), 180, 30, 'F');

        $this->SetFont('Helvetica', 'B', 12);
        $this->SetTextColor($this->darkGray[0], $this->darkGray[1], $this->darkGray[2]);
        $this->SetXY(15, $this->GetY() + 5);
        $this->Cell(90, 8, 'PAYMENT SUMMARY', 0, 0);

        $this->SetFont('Helvetica', '', 10);
        $this->SetXY(15, $this->GetY() + 10);
        $this->Cell(90, 8, 'Total Transactions: ' . count($this->paymentData), 0, 0);

        $this->SetFont('Helvetica', 'B', 14);
        $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->SetXY(105, $this->GetY() - 10);
        $this->Cell(90, 8, 'TOTAL AMOUNT', 0, 0, 'R');

        $this->SetFont('Helvetica', 'B', 18);
        $this->SetXY(105, $this->GetY() + 10);
        $this->Cell(90, 8, 'Rs. ' . number_format($this->summaryData['total_amount'], 2), 0, 0, 'R');
    }

    // Helper function for status colors
    private function getStatusColor($status)
    {
        switch (strtolower($status)) {
            case 'successful':
            case 'completed':
                return [46, 204, 113]; // Green
            case 'failed':
                return [231, 76, 60]; // Red
            case 'pending':
                return [241, 196, 15]; // Yellow
            case 'refunded':
                return [155, 89, 182]; // Purple
            default:
                return [150, 150, 150]; // Gray
        }
    }

    // Generate the PDF
    public function generate()
    {
        $this->AliasNbPages(); // For page numbering
        $this->AddPage();
        $this->buildSummary();

        // Check if we need a new page for the payment table
        if ($this->GetY() > 180) {
            $this->AddPage();
        }

        $this->buildPaymentTable();
        $this->Output('I', 'Payment_Report_' . date('Y-m-d') . '.pdf');
    }
}

// Usage example
// Assuming a Foodies class exists with methods to fetch data
$obj = new Foodies();
$filters = [
    'status' => $_GET['status'] ?? '',
    'payment_method' => $_GET['payment_method'] ?? '',
    'date_from' => $_GET['date_from'] ?? '',
    'date_to' => $_GET['date_to'] ?? '',
    'amount_range' => $_GET['amount_range'] ?? '',
    'search' => $_GET['search'] ?? ''
]; // Add filters if needed

try {
    $pdf = new ProfessionalPDF();
    $pdf->setSummaryData($obj->getPaymentSummary());
    $pdf->setPaymentData($obj->getAllPayments($filters));
    $pdf->generate();
} catch (Exception $e) {
    die('Error generating report: ' . $e->getMessage());
}