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

// CHECK DUPLICATE
$checkSql = "SELECT * FROM Inquiries WHERE AadhaarNumber = ?";
$checkStmt = sqlsrv_query($conn, $checkSql, [$AadhaarNumber]);

if (sqlsrv_has_rows($checkStmt)) {
    die("<h2 style='color:red;text-align:center;'>❌ Aadhaar already exists!</h2>");
}

// INSERT
$sql = "INSERT INTO Inquiries
(FullName, PhoneNumber, Email, AddressLine, City, State, Pincode, LoanType, LoanAmount, TenureMonths, EmploymentType, CompanyName, MonthlyIncome, AadhaarNumber, PANNumber)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$params = [
    $FullName, $PhoneNumber, $Email, $AddressLine, $City, $State, $Pincode,
    $LoanType, $LoanAmount, $TenureMonths, $EmploymentType,
    $CompanyName, $MonthlyIncome, $AadhaarNumber, $PANNumber
];

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt) {
    // redirect with Aadhaar (unique identifier)
    header("Location: user.php?aadhaar=" . urlencode($AadhaarNumber));
    exit();
} else {
    die("❌ Insert failed");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Loan Inquiries</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 95%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: #007bff;
            color: white;
            padding: 10px;
        }

        td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .btn {
            display: block;
            width: 200px;
            margin: 20px auto;
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

<h2>✅ Loan Inquiries Data (Grid View)</h2>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Email</th>
    <th>City</th>
    <th>Loan</th>
    <th>Amount</th>
    <th>Tenure</th>
    <th>Income</th>
    <th>Aadhaar</th>
    <th>PAN</th>
</tr>

<?php
while ($row = sqlsrv_fetch_array($getStmt, SQLSRV_FETCH_ASSOC)) {
    echo "<tr>
        <td>{$row['InquiryID']}</td>
        <td>{$row['FullName']}</td>
        <td>{$row['PhoneNumber']}</td>
        <td>{$row['Email']}</td>
        <td>{$row['City']}</td>
        <td>{$row['LoanType']}</td>
        <td>{$row['LoanAmount']}</td>
        <td>{$row['TenureMonths']}</td>
        <td>{$row['MonthlyIncome']}</td>
        <td>{$row['AadhaarNumber']}</td>
        <td>{$row['PANNumber']}</td>
    </tr>";
}
?>

</table>

<a href="index.html" class="btn">⬅ Back to Form</a>

</body>
</html>