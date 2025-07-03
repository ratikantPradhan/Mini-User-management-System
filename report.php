<?php
require_once('tcpdf/tcpdf.php');
require_once('db.php'); // Include the database connection script

$id = $_GET['id'];

// Retrieve user data from database
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Check if user data was retrieved successfully
if (!$user) {
    echo "Error: Unable to retrieve user data.";
    exit;
}

// Create a new TCPDF object
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Admin');
$pdf->SetTitle('User Report');
$pdf->SetSubject('User Report');
$pdf->SetKeywords('user, report');

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add user data to PDF
$pdf->Cell(0, 10, $user['username']." ".'Report', 1, 1, 'C');
$pdf->Ln(10);
$pdf->Cell(0, 10, 'Name: ' . $user['username'], 1, 1, 'L');
$pdf->Cell(0, 10, 'Email: ' . $user['email'], 1, 1, 'L');
$pdf->Cell(0, 10, 'Role: ' . $user['role'], 1, 1, 'L');
// TCPDF::Image($file,  $x = '',  $y = '',  $w,  $h,  $type = '',  $link = '',  $align = '',  $resize = false,  $dpi = 300,  $palign = '',  $ismask = false,  $imgmask = false,  $border,  $fitbox = false,  $hidden = false,  $fitonpage = false,  $alt = false,  $altimgs = array())
// Add profile photo to PDF
if ($user['profile'] != '') {
    $pdf->Image( $user['profile'], 50, 70, 50, 50, 'JPG', '', '', true, 300, '', false, false, 0, false, false, 'profile');
}

// Output the PDF
ob_end_clean(); // Clean the output buffer
$pdf->Output('user_report.pdf', 'D');

?>