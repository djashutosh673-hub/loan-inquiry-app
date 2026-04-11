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

    // GET LAST INSERTED DATA
    $getSql = "SELECT TOP 1 * FROM Inquiries WHERE AadhaarNumber = ? ORDER BY InquiryID DESC";
    $getStmt = sqlsrv_query($conn, $getSql, [$AadhaarNumber]);

    $userData = sqlsrv_fetch_array($getStmt, SQLSRV_FETCH_ASSOC);

} else {
    echo "<h2 style='color:red;text-align:center;'>❌ Insert failed</h2>";
    die(print_r(sqlsrv_errors(), true));
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Submitted Details</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
        }

        .container {
            width: 90%;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color: green;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .card {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            border-left: 5px solid #28a745;
        }

        .label {
            font-weight: bold;
            color: #333;
        }

        .value {
            margin-top: 5px;
            color: #555;
        }

        .btn {
            display: block;
            width: 200px;
            margin: 30px auto;
            text-align: center;
            padding: 10px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>

</head>

<body>

<div class="container">

    <h2>✅ Your Inquiry Submitted Successfully</h2>

    <div class="grid">

        <div class="card">
            <div class="label">Full Name</div>
            <div class="value"><?php echo $userData['FullName']; ?></div>
        </div>

        <div class="card">
            <div class="label">Phone</div>
            <div class="value"><?php echo $userData['PhoneNumber']; ?></div>
        </div>

        <div class="card">
            <div class="label">Email</div>
            <div class="value"><?php echo $userData['Email']; ?></div>
        </div>

        <div class="card">
            <div class="label">Address</div>
            <div class="value"><?php echo $userData['AddressLine']; ?></div>
        </div>

        <div class="card">
            <div class="label">City</div>
            <div class="value"><?php echo $userData['City']; ?></div>
        </div>

        <div class="card">
            <div class="label">State</div>
            <div class="value"><?php echo $userData['State']; ?></div>
        </div>

        <div class="card">
            <div class="label">Pincode</div>
            <div class="value"><?php echo $userData['Pincode']; ?></div>
        </div>

        <div class="card">
            <div class="label">Loan Type</div>
            <div class="value"><?php echo $userData['LoanType']; ?></div>
        </div>

        <div class="card">
            <div class="label">Loan Amount</div>
            <div class="value"><?php echo $userData['LoanAmount']; ?></div>
        </div>

        <div class="card">
            <div class="label">Tenure</div>
            <div class="value"><?php echo $userData['TenureMonths']; ?> months</div>
        </div>

        <div class="card">
            <div class="label">Employment</div>
            <div class="value"><?php echo $userData['EmploymentType']; ?></div>
        </div>

        <div class="card">
            <div class="label">Company</div>
            <div class="value"><?php echo $userData['CompanyName']; ?></div>
        </div>

        <div class="card">
            <div class="label">Monthly Income</div>
            <div class="value"><?php echo $userData['MonthlyIncome']; ?></div>
        </div>

        <div class="card">
            <div class="label">Aadhaar</div>
            <div class="value"><?php echo $userData['AadhaarNumber']; ?></div>
        </div>

        <div class="card">
            <div class="label">PAN</div>
            <div class="value"><?php echo $userData['PANNumber']; ?></div>
        </div>

    </div>

    <a href="index.html" class="btn">⬅ Back to Form</a>

</div>

</body>
</html>
