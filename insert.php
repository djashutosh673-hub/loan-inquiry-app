<?php
// Force error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$serverName = "cosmosrds.cnoo4wwa6kfo.ap-south-1.rds.amazonaws.com";

// ============================================================
// METHOD 1: Completely disable SSL (works for most RDS setups)
// ============================================================
$connectionOptions = [
    "Database" => "Cosmos_Stamp",
    "Uid" => "cosmos",
    "PWD" => "4321aeiou",
    "Encrypt" => 0,                    // Integer 0 = no encryption
    "TrustServerCertificate" => 1,     // Bypass cert check
    "LoginTimeout" => 30
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

// ============================================================
// If METHOD 1 fails, try METHOD 2 with explicit DSN string
// ============================================================
if ($conn === false) {
    // Try alternative connection string (DSN style)
    $connectionString = "sqlsrv:Server=$serverName;Database=Cosmos_Stamp;Encrypt=0;TrustServerCertificate=1";
    try {
        $conn = new PDO($connectionString, "cosmos", "1234");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $usingPDO = true;
    } catch (PDOException $e) {
        die("Both sqlsrv and PDO failed. Last error: " . print_r(sqlsrv_errors(), true) . "<br>PDO error: " . $e->getMessage());
    }
}

// If sqlsrv succeeded, we continue with that
if (!$usingPDO && $conn === false) {
    die("Connection failed: <pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
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
} else {
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt) {
        echo "✅ Data inserted successfully!";
    } else {
        echo "❌ Insert failed: <pre>" . print_r(sqlsrv_errors(), true) . "</pre>";
    }
}

// Close connections
if (!$usingPDO) {
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
} else {
    $conn = null;
}
?>