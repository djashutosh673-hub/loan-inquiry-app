<?php
$serverName = "cosmosrds.cnoo4wwa6kfo.ap-south-1.rds.amazonaws.com";

// Connection options – FIXED SSL ERROR
$connectionOptions = [
    "Database" => "Cosmos_Stamp",
    "Uid" => "cosmos",
    "PWD" => "1234",
    "Encrypt" => false,               // Disable SSL (easiest fix)
    "TrustServerCertificate" => true  // Optional, but safe to keep
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Get form data
$FullName = $_POST['FullName'] ?? '';
$PhoneNumber = $_POST['PhoneNumber'] ?? '';
$Email = $_POST['Email'] ?? '';
$AddressLine = $_POST['AddressLine'] ?? '';
$City = $_POST['City'] ?? '';
$State = $_POST['State'] ?? '';
$Pincode = $_POST['Pincode'] ?? '';
$LoanType = $_POST['LoanType'] ?? '';
$LoanAmount = $_POST['LoanAmount'] ?? '';
$TenureMonths = $_POST['TenureMonths'] ?? '';
$EmploymentType = $_POST['EmploymentType'] ?? '';
$CompanyName = $_POST['CompanyName'] ?? '';
$MonthlyIncome = $_POST['MonthlyIncome'] ?? '';
$AadhaarNumber = $_POST['AadhaarNumber'] ?? '';
$PANNumber = $_POST['PANNumber'] ?? '';

// Insert query
$sql = "INSERT INTO Inquiries 
(FullName, PhoneNumber, Email, AddressLine, City, State, Pincode, LoanType, LoanAmount, TenureMonths, EmploymentType, CompanyName, MonthlyIncome, AadhaarNumber, PANNumber)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$params = [
    $FullName, $PhoneNumber, $Email, $AddressLine, $City, $State, $Pincode,
    $LoanType, $LoanAmount, $TenureMonths, $EmploymentType, $CompanyName,
    $MonthlyIncome, $AadhaarNumber, $PANNumber
];

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt) {
    echo "Data inserted successfully!";
} else {
    echo "Error inserting data:<br>";
    die(print_r(sqlsrv_errors(), true));
}

// Close connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>