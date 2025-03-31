<?php
session_start();
require '../dbCon.php';
require '../vendor/autoload.php';

use setasign\Fpdi\Fpdi;

class ReceiptPDF extends Fpdi
{
    private $logo = './uploads/logo.png';
    public $primaryColor = [33, 47, 69];       // Midnight Navy
    public $accentColor = [255, 118, 67];      // Warm Orange
    public $darkColor = [64, 64, 64];          // Charcoal Gray
    public $lightColor = [245, 245, 245];      // Soft White

    function Header()
    {
        // Header Background
        $this->SetFillColor(...$this->primaryColor);
        $this->Rect(0, 0, 210, 25, 'F');

        // Logo and Company Info
        if (file_exists($this->logo)) {
            $this->Image($this->logo, 15, 8, 25);
        }
        $this->SetFont('Helvetica', 'B', 14);
        $this->SetTextColor(255, 255, 255);
        $this->SetXY(45, 10);
        $this->Cell(0, 6, 'Village Chef Inc.', 0, 1);
        $this->SetFont('Helvetica', '', 9);
        $this->SetX(45);
        $this->Cell(0, 4, 'GSTIN: 27ABCDE1234F2Z5', 0, 1);

        // Receipt Title
        $this->SetFont('Helvetica', 'B', 16);
        $this->SetXY(0, 10);
        $this->Cell(0, 8, 'Payment Receipt', 0, 1, 'R');
        $this->SetFont('Helvetica', '', 10);
        $this->Cell(0, 4, 'Receipt #PAY-' . $_GET['payment_id'], 0, 1, 'R');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-25);
        $this->SetFillColor(...$this->lightColor);
        $this->Rect(0, 272, 210, 25, 'F');
        $this->SetFont('Helvetica', 'I', 8);
        $this->SetTextColor(...$this->darkColor);
        $this->SetY(-20);
        $this->Cell(0, 5, 'Generated on: ' . date('M d, Y h:i A'), 0, 0, 'L');
        $this->Cell(0, 5, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'R');
        $this->Ln(5);
        $this->SetFont('Helvetica', '', 9);
        $this->SetTextColor(...$this->primaryColor);
        $this->Cell(0, 5, 'Thank you for dining with Foodies Inc.!', 0, 1, 'C');
    }

    function FancyTable($header, $data, $widths, $isSummary = false)
    {
        // Header
        $this->SetFillColor(...$this->primaryColor);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Helvetica', 'B', 10);
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($widths[$i], 7, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();

        // Data
        $this->SetFont('Helvetica', '', 10);
        $this->SetTextColor(...$this->darkColor);
        $fill = false;

        foreach ($data as $index => $row) {
            $this->SetFillColor($fill ? 245 : 255, $fill ? 245 : 255, $fill ? 245 : 255);
            if ($isSummary && $index === count($data) - 1) {
                $this->SetFont('Helvetica', 'B', 11);
                $this->SetTextColor(...$this->primaryColor);
                $this->SetFillColor(...$this->lightColor);
            }
            for ($i = 0; $i < count($row); $i++) {
                $align = ($i === 0) ? 'L' : 'R';
                $this->Cell($widths[$i], 6, $row[$i], 1, 0, $align, true);
            }
            $this->Ln();
            $fill = !$fill;
        }
    }
}

if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}

if (!isset($_GET['payment_id'])) {
    die("Payment ID is missing.");
}

$obj = new Foodies();
$payment_id = $_GET['payment_id'];
$paymentDetails = $obj->getPaymentDetails($payment_id);

if (!$paymentDetails) {
    die("Payment details not found.");
}

$orderItems = $obj->getOrderItems($paymentDetails['order_id']);

$grand_total = $paymentDetails['amount'];
$platform_fee = 6;
$delivery_charge = 22;
$fixed_charges = $platform_fee + $delivery_charge;
$actual_total = ($grand_total - $fixed_charges) / 1.09;
$tax = $actual_total * 0.09;

$pdf = new ReceiptPDF('P', 'mm', 'A4');
$pdf->SetMargins(15, 30, 15);
$pdf->AliasNbPages();
$pdf->AddPage();

// Welcome Message
$pdf->SetFont('Helvetica', 'I', 11);
$pdf->SetTextColor(...$pdf->darkColor); // Fixed: Use $pdf instead of $this
$pdf->Cell(0, 8, 'We are delighted to serve you!', 0, 1, 'C');
$pdf->Ln(5);

// Transaction Details
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->SetTextColor(...$pdf->primaryColor);
$pdf->Cell(0, 8, 'Transaction Details', 0, 1);

$customerData = [
    ['Customer Name', $paymentDetails['first_name'] . ' ' . $paymentDetails['last_name']],
    ['Email', $paymentDetails['email']],
    ['Phone', $paymentDetails['phone'] ?? '-'],
    ['Delivery Address', $paymentDetails['delivery_address'] ?? '-'],
    ['Payment ID', '#PAY-' . $paymentDetails['payment_id']],
    ['Order ID', $paymentDetails['order_id']],
    ['Payment Method', ucfirst(str_replace('-', ' ', $paymentDetails['payment_method']))],
    ['Payment Date', date('M d, Y h:i A', strtotime($paymentDetails['payment_date']))]
];

$widths = [50, 130];
$pdf->FancyTable(['Field', 'Details'], $customerData, $widths);
$pdf->Ln(10);

// Order Items
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->SetTextColor(...$pdf->primaryColor);
$pdf->Cell(0, 8, 'Order Items', 0, 1);

$header = ['Item Name', 'Qty', 'Unit Price', 'Total'];
$widths = [90, 20, 35, 35];
$itemsData = [];

foreach ($orderItems as $item) {
    $itemTotal = $item['quantity'] * $item['price'];
    $itemsData[] = [
        $item['item_name'],
        $item['quantity'],
        'Rs.' . number_format($item['price'], 2),
        'Rs.' . number_format($itemTotal, 2)
    ];
}

$pdf->FancyTable($header, $itemsData, $widths);
$pdf->Ln(10);

// Payment Summary
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->SetTextColor(...$pdf->primaryColor);
$pdf->Cell(0, 8, 'Payment Summary', 0, 1);

$breakdownData = [
    ['Subtotal', 'Rs.' . number_format($actual_total, 2)],
    ['Platform Fee', 'Rs.' . number_format($platform_fee, 2)],
    ['Delivery Charge', 'Rs.' . number_format($delivery_charge, 2)],
    ['Tax (9%)', 'Rs.' . number_format($tax, 2)],
    ['Grand Total', 'Rs.' . number_format($grand_total, 2)]
];

$widths = [145, 35];
$pdf->FancyTable(['Description', 'Amount'], $breakdownData, $widths, true);

// Final Note
$pdf->SetY(-35);
$pdf->SetFont('Helvetica', 'I', 9);
$pdf->SetTextColor(...$pdf->darkColor);
// $pdf->Cell(0, 5, 'This is a computer-generated receipt and does not require a signature.', 0, 1, 'C');

$pdf->Output('I', 'Payment_Receipt_' . $paymentDetails['payment_id'] . '.pdf');
exit();
?>