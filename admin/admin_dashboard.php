<?php
session_start();

// Protect page
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch summary counts for cards (adjust queries to your database)
require '../db.php';

// Total Patients
$totalPatients = $conn->query("SELECT COUNT(*) AS total FROM patients")->fetch_assoc()['total'] ?? 0;

// Total Medical Records
$totalRecords = $conn->query("SELECT COUNT(*) AS total FROM medical_records")->fetch_assoc()['total'] ?? 0;

// Upcoming Visits (example: future dates)
$upcomingVisits = $conn->query("SELECT COUNT(*) AS total FROM medical_records WHERE visit_date >= CURDATE()")->fetch_assoc()['total'] ?? 0;

// Doctors On Duty (example: count distinct doctors)
$doctorsOnDuty = $conn->query("SELECT COUNT(DISTINCT doctor) AS total FROM medical_records")->fetch_assoc()['total'] ?? 0;

// Fetch recent medical records (latest 5)
$recentRecords = $conn->query("
    SELECT mr.id, p.name AS patient_name, mr.diagnosis, mr.treatment, mr.doctor, mr.visit_date
    FROM medical_records mr
    JOIN patients p ON mr.patient_id = p.id
    ORDER BY mr.visit_date DESC
    LIMIT 5
");
// Patient Visits per Month (last 6 months)
$visitsData = [];
$months = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i month"));
    $months[] = date('M Y', strtotime("-$i month"));
    $count = $conn->query("SELECT COUNT(*) AS total FROM medical_records WHERE DATE_FORMAT(visit_date, '%Y-%m')='$month'")->fetch_assoc()['total'] ?? 0;
    $visitsData[] = $count;
}

// Top 5 Diagnoses
$diagnosisData = [];
$diagnosisLabels = [];
$diagResult = $conn->query("SELECT diagnosis, COUNT(*) AS total FROM medical_records GROUP BY diagnosis ORDER BY total DESC LIMIT 5");
while ($row = $diagResult->fetch_assoc()) {
    $diagnosisLabels[] = $row['diagnosis'];
    $diagnosisData[] = $row['total'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Kitere Health System</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* Base */
    body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #0f172a;
        color: #fff;
    }

    /* Dashboard card */
    .dashboard-card {
        background-color: #1e293b;
        max-width: 1200px;
        margin: 30px auto;
        padding: 30px 40px;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.6);
    }

    /* Welcome heading */
    .dashboard-card h2 {
        text-align: center;
        color: #f8fafc;
        font-size: 28px;
        margin-bottom: 25px;
    }

    /* Menu styling */
    .menu {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .menu a {
        background-color: #1e40af;
        padding: 12px 25px;
        border-radius: 10px;
        color: #f8fafc;
        font-weight: bold;
        text-decoration: none;
        transition: all 0.3s;
    }

    .menu a:hover {
        background-color: #3b82f6;
        transform: translateY(-3px);
    }

    /* Dashboard stats cards */
    .stats {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        margin-bottom: 30px;
    }

    .stats .card {
        background-color: #334155;
        flex: 1 1 220px;
        max-width: 250px;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 6px 18px rgba(0,0,0,0.4);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stats .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.5);
    }

    .stats .card h3 {
        font-size: 32px;
        margin-bottom: 10px;
        color: #3b82f6;
    }

    .stats .card p {
        font-size: 16px;
        color: #f1f5f9;
    }

    /* Recent records table */
    .recent-records {
        overflow-x: auto;
        margin-bottom: 30px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #1e293b;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 15px rgba(0,0,0,0.2);
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        color: #f1f5f9;
    }

    th {
        background-color: #3b82f6;
        color: #fff;
    }

    tr:nth-child(even) {
        background-color: #334155;
    }

    tr:hover {
        background-color: #475569;
    }

    /* Iframe */
    iframe {
        width: 100%;
        height: 60vh;
        border: none;
        border-radius: 12px;
        background-color: #f1f5f9;
        margin-bottom: 30px;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .stats {
            flex-direction: column;
            align-items: center;
        }
        .stats .card {
            width: 90%;
        }
    }
    .charts {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    justify-content: center;
    margin-bottom: 30px;
}

.chart-container {
    background-color: #334155;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.4);
    flex: 1 1 400px;
    max-width: 500px;
    color: #fff;
}

.chart-container h3 {
    text-align: center;
    margin-bottom: 15px;
    color: #3b82f6;
}

</style>
</head>
<body>

<div class="dashboard-card">
    <h2>Welcome, <?= $_SESSION['admin_name']; ?></h2>

    <!-- Menu -->
    <div class="menu">
        <a href="patient_list.php" target="main">Patients</a>
        <a href="medical_records_list.php" target="main">Medical Records List</a>
        <a href="patient_medical_record_add.php" target="main">Add Medical Record</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Stats Cards -->
    <div class="stats">
        <div class="card">
            <h3><?= $totalPatients ?></h3>
            <p>Total Patients</p>
        </div>
        <div class="card">
            <h3><?= $totalRecords ?></h3>
            <p>Medical Records</p>
        </div>
        <div class="card">
            <h3><?= $upcomingVisits ?></h3>
            <p>Upcoming Visits</p>
        </div>
        <div class="card">
            <h3><?= $doctorsOnDuty ?></h3>
            <p>Doctors On Duty</p>
        </div>
    </div>
    <div class="charts">
    <div class="chart-container">
        <h3>Patient Visits per Month</h3>
        <canvas id="visitsChart"></canvas>
    </div>
    <div class="chart-container">
        <h3>Top Diagnoses</h3>
        <canvas id="diagnosisChart"></canvas>
    </div>
</div>


    <!-- Recent Medical Records Table -->
    <div class="recent-records">
        <h3>Recent Medical Records</h3>
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Diagnosis</th>
                    <th>Treatment</th>
                    <th>Doctor</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $recentRecords->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['patient_name']) ?></td>
                    <td><?= htmlspecialchars($row['diagnosis']) ?></td>
                    <td><?= htmlspecialchars($row['treatment']) ?></td>
                    <td><?= htmlspecialchars($row['doctor']) ?></td>
                    <td><?= htmlspecialchars($row['visit_date']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Iframe for pages -->
    <iframe name="main"></iframe>
</div>
<script>
    // Visits per Month Bar Chart
    const visitsCtx = document.getElementById('visitsChart').getContext('2d');
    const visitsChart = new Chart(visitsCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($months); ?>,
            datasets: [{
                label: 'Patient Visits',
                data: <?= json_encode($visitsData); ?>,
                backgroundColor: '#3b82f6'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'Patient Visits per Month', color: '#fff' }
            },
            scales: {
                x: { ticks: { color: '#fff' }, grid: { color: '#475569' } },
                y: { ticks: { color: '#fff' }, grid: { color: '#475569' } }
            }
        }
    });

    // Top Diagnoses Pie Chart
    const diagCtx = document.getElementById('diagnosisChart').getContext('2d');
    const diagnosisChart = new Chart(diagCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode($diagnosisLabels); ?>,
            datasets: [{
                data: <?= json_encode($diagnosisData); ?>,
                backgroundColor: [
                    '#3b82f6',
                    '#f97316',
                    '#22c55e',
                    '#e11d48',
                    '#8b5cf6'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { color: '#fff' } },
                title: { display: true, text: 'Top Diagnoses', color: '#fff' }
            }
        }
    });
</script>

</body>
</html>
