<?php

// SHOW ERRORS
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DATABASE CONNECTION
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
    die("❌ Connection failed: " . print_r(sqlsrv_errors(), true));
}

// COLLECT + TYPE SAFE DATA
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

// CHECK DUPLICATE AADHAAR
$checkSql = "SELECT * FROM Inquiries WHERE AadhaarNumber = ?";
$checkStmt = sqlsrv_query($conn, $checkSql, [$AadhaarNumber]);

if (sqlsrv_has_rows($checkStmt)) {
    die("<h2 style='color:red;text-align:center;'>❌ Aadhaar already exists!</h2>");
}

// INSERT QUERY
$sql = "INSERT INTO Inquiries
(FullName, PhoneNumber, Email, AddressLine, City, State, Pincode, LoanType, LoanAmount, TenureMonths, EmploymentType, CompanyName, MonthlyIncome, AadhaarNumber, PANNumber)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$params = [
    $FullName, $PhoneNumber, $Email, $AddressLine, $City, $State, $Pincode,
    $LoanType, $LoanAmount, $TenureMonths, $EmploymentType,
    $CompanyName, $MonthlyIncome, $AadhaarNumber, $PANNumber
];

$stmt = sqlsrv_query($conn, $sql, $params);

// RESULT
if ($stmt) {
    echo "<h2 style='color:green;text-align:center;'>✅ Data inserted successfully!</h2>";
} else {
    echo "<h2 style='color:red;text-align:center;'>❌ Insert failed</h2>";
    die(print_r(sqlsrv_errors(), true));
}

?>
