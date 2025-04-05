<?php
session_start();
require '../dbCon.php';
require '../vendor/autoload.php';

use setasign\Fpdi\Fpdi;

class PDF extends Fpdi
{
    private $logo = './uploads/logo.png'; // Update this path


    function Header()
    {
        // Logo
        if (file_exists($this->logo)) {
            $this->Image($this->logo, 10, 8, 30);
        }

        // Title
        $this->SetY(15);
        $this->SetFont('Arial', 'B', 20);
        $this->SetTextColor(33, 150, 243); // Blue color
        $this->Cell(0, 10, 'Village Chef Order Report', 0, 1, 'C');

        // Subtitle
        $this->SetFont('Arial', 'I', 12);
        $this->SetTextColor(0, 0, 0); // Black
        $this->Cell(0, 6, 'Delicious Orders, Perfect Records', 0, 1, 'C');

        // Line break
        $this->Ln(8);
        $this->SetDrawColor(255, 193, 7); // Yellow accent color
        $this->SetLineWidth(0.5);
        $this->Line(10, 40, $this->GetPageWidth() - 10, 40);
        $this->Ln(12);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(150, 150, 150);
        $this->Cell(0, 10, 'Generated on: ' . date('F j, Y, g:i a'), 0, 0, 'L');
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        // $this->Cell(0, 10, 'Confidential', 0, 0, 'R');
    }

    function BasicTable($header, $data)
    {
        // Colors, line width and bold font
        $this->SetFillColor(245, 245, 245); // Light gray
        $this->SetTextColor(0);
        $this->SetDrawColor(150, 150, 150);
        $this->SetLineWidth(0.1);
        $this->SetFont('Arial', 'B', 12);

        // Header
        $this->SetX(10);
        foreach ($header as $col) {
            $this->Cell(45, 8, $col, 1, 0, 'C', true);
        }
        $this->Ln();

        // Data
        $this->SetFont('Arial', '', 10);
        $fill = false;
        foreach ($data as $row) {
            $this->SetX(10);

            // Order ID
            $this->Cell(45, 8, $row['order_id'], 'LR', 0, 'C', $fill);

            // Customer Name
            $this->Cell(45, 8, iconv('UTF-8', 'windows-1252', $row['first_name'] . ' ' . $row['last_name']), 'LR', 0, 'L', $fill);

            // Restaurant
            $this->Cell(45, 8, iconv('UTF-8', 'windows-1252', $row['restaurant_name']), 'LR', 0, 'L', $fill);

            // Total Amount
            $this->Cell(45, 8, "Rs." . number_format($row['total_amount'], 2), 'LR', 0, 'R', $fill);

            // Status
            $this->SetStatusColor($row['status']);
            $this->Cell(45, 8, ucfirst(str_replace('_', ' ', $row['status'])), 'LR', 0, 'C', $fill);
            $this->SetTextColor(0); // Reset color

            // Order Date
            $this->Cell(45, 8, date('M d, Y', strtotime($row['order_date'])), 'LR', 0, 'C', $fill);

            $this->Ln();
            $fill = !$fill;
        }
        $this->SetX(10);
        $this->Cell(270, 0, '', 'T'); // Closing line
    }

    private function SetStatusColor($status)
    {
        $colors = [
            'pending' => [255, 193, 7],    // Yellow
            'confirmed' => [33, 150, 243], // Blue
            'preparing' => [255, 87, 34],  // Orange
            'out_for_delivery' => [156, 39, 176], // Purple
            'delivered' => [76, 175, 80],  // Green
            'cancelled' => [244, 67, 54]   // Red
        ];

        $this->SetTextColor(...$colors[strtolower($status)] ?? [0, 0, 0]);
    }
}

// Check admin session
if (!isset($_SESSION['admin'])) {
    header('location:login.php');
    exit();
}

try {
    $obj = new Foodies();
    $orders = $obj->getAllOrders();

    $pdf = new PDF('L');
    $pdf->AliasNbPages();
    $pdf->AddPage();

    // Add summary box
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetX(10);
    $pdf->Cell(270, 8, 'Order Summary', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 10);

    $totalOrders = count($orders);
    $totalRevenue = array_sum(array_column($orders, 'total_amount'));

    $pdf->SetX(10);
    $pdf->Cell(135, 8, 'Total Orders: ' . $totalOrders, 1, 0, 'C');
    $pdf->Cell(135, 8, 'Total Revenue: Rs.' . number_format($totalRevenue, 2), 1, 1, 'C');
    $pdf->Ln(10);

    // Table headers
    $header = ['Order ID', 'Customer Name', 'Restaurant', 'Total Amount', 'Status', 'Order Date'];
    $data = [];

    foreach ($orders as $order) {
        $data[] = [
            'order_id' => $order['order_id'],
            'first_name' => $order['first_name'],
            'last_name' => $order['last_name'],
            'restaurant_name' => $order['restaurant_name'],
            'total_amount' => $order['total_amount'],
            'status' => $order['status'],
            'order_date' => $order['order_date']
        ];
    }

    $pdf->BasicTable($header, $data);

    // Add final summary
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 8, '* All amounts in Indian Rupees (Rs.)', 0, 1, 'C');

    $pdf->Output('I', 'VillageChef_Orders_Report_' . date('Ymd_His') . '.pdf');
    exit();

} catch (Exception $e) {
    // Handle errors
    header('Content-Type: text/plain');
    echo "Error generating PDF: " . $e->getMessage();
    exit();
}
?>