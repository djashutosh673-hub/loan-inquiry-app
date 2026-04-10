<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$serverName = "cosmosrds.cnoo4wwa6kfo.ap-south-1.rds.amazonaws.com";

// Default: use sqlsrv
$usingPDO = false;

// ============================================================
// Try sqlsrv with SSL disabled (fixes certificate error)
// ============================================================
$connectionOptions = [
    "Database" => "Cosmos_Stamp",
    "Uid" => "cosmos",
    "PWD" => "1234",
    "Encrypt" => false,               // Disable SSL
    "TrustServerCertificate" => true,
    "LoginTimeout" => 30
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

// ============================================================
// If sqlsrv fails, fall back to PDO with same settings
// ============================================================
if ($conn === false) {
    $dsn = "sqlsrv:Server=$serverName;Database=Cosmos_Stamp;Encrypt=0;TrustServerCertificate=1";
    try {
        $conn = new PDO($dsn, "cosmos", "1234");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $usingPDO = true;
    } catch (PDOException $e) {
        die("Both sqlsrv and PDO failed.<br>sqlsrv error: " . print_r(sqlsrv_errors(), true) . "<br>PDO error: " . $e->getMessage());
    }
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

// Execute using the appropriate connection method
if ($usingPDO) {
    $stmt = $conn->prepare($sql);
    $success = $stmt->execute($params);
    if ($success) {
        echo "✅ Data inserted successfully via PDO!";
    } else {
        echo "❌ PDO insert failed: " . print_r($stmt->errorInfo(), true);
    }
    $conn = null; // close PDO connection
} else {
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt) {
        echo "✅ Data inserted successfully!";
    } else {
        echo "❌ Insert failed: <pre>" . print_r(sqlsrv_errors(), true) . "</pre>";
    }
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
}
?>