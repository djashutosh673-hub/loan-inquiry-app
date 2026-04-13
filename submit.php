<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// DB CONNECTION
$serverName = "cosmosrds.cnoo4wwa6kfo.ap-south-1.rds.amazonaws.com";

$connectionOptions = [
    "Database" => "Cosmos_Stamp",
    "Uid" => "cosmos",
    "PWD" => "4321aeiou",
    "Encrypt" => false,
    "TrustServerCertificate" => true,
    "CharacterSet" => "UTF-8"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die("❌ Connection failed");
}

// GET FORM DATA
$FullName = $_POST['FullName'];
$PhoneNumber = $_POST['PhoneNumber'];
$Email = $_POST['Email'];
$AddressLine = $_POST['AddressLine'];
$City = $_POST['City'];
$State = $_POST['State'];
$Pincode = (int)$_POST['Pincode'];
$LoanType = $_POST['LoanType'];
$LoanAmount = (int)$_POST['LoanAmount'];
$TenureMonths = (int)$_POST['TenureMonths'];
$EmploymentType = $_POST['EmploymentType'];
$CompanyName = $_POST['CompanyName'];
$MonthlyIncome = (int)$_POST['MonthlyIncome'];
$AadhaarNumber = $_POST['AadhaarNumber'];
$PANNumber = $_POST['PANNumber'];
$ProfileType = $_POST['ProfileType'];

// CHECK DUPLICATE
$checkSql = "SELECT * FROM Inquiries WHERE AadhaarNumber = ?";
$checkStmt = sqlsrv_query($conn, $checkSql, [$AadhaarNumber]);

if (sqlsrv_has_rows($checkStmt)) {
    die("<h2 style='color:red;text-align:center;'>❌ Aadhaar already exists!</h2>");
}

// FILE UPLOAD
$uploadDir = "uploads/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

function uploadFile($file, $uploadDir) {
    if (!empty($file['name'])) {
        $fileName = time() . "_" . basename($file['name']);
        $target = $uploadDir . $fileName;
        move_uploaded_file($file['tmp_name'], $target);
        return $fileName;
    }
    return null;
}

$aadhaarFile = uploadFile($_FILES['aadhaar_file'], $uploadDir);
$panFile = uploadFile($_FILES['pan_file'], $uploadDir);
$salaryFile = uploadFile($_FILES['salary_file'], $uploadDir);
$bankFile = uploadFile($_FILES['bank_file'], $uploadDir);
$gstFile = uploadFile($_FILES['gst_file'], $uploadDir);
$udyamFile = uploadFile($_FILES['udyam_file'], $uploadDir);

// INSERT DATA
$sql = "INSERT INTO Inquiries
(FullName, PhoneNumber, Email, AddressLine, City, State, Pincode, LoanType, LoanAmount, TenureMonths, EmploymentType, CompanyName, MonthlyIncome, AadhaarNumber, PANNumber, ProfileType, AadhaarFile, PANFile, SalaryFile, BankFile, GSTFile, UdyamFile)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$params = [
    $FullName, $PhoneNumber, $Email, $AddressLine, $City, $State, $Pincode,
    $LoanType, $LoanAmount, $TenureMonths, $EmploymentType,
    $CompanyName, $MonthlyIncome, $AadhaarNumber, $PANNumber,
    $ProfileType,
    $aadhaarFile, $panFile, $salaryFile, $bankFile, $gstFile, $udyamFile
];

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt) {
    // SUCCESS → Redirect to user page
    header("Location: user.php?aadhaar=" . urlencode($AadhaarNumber));
    exit();
} else {
    die("❌ Insert failed");
}

?>