<?php
session_start();
if (!isset($_SESSION['patient_id'])) {
    header("Location: patient_login.php");
    exit;
}

require '../db.php';

// Fetch patient ID
$pid = $_SESSION['patient_id'];

// Summary counts
$totalVisits = $conn->query("SELECT COUNT(*) AS total FROM medical_records WHERE patient_id = $pid")->fetch_assoc()['total'] ?? 0;
$upcomingAppointments = $conn->query("SELECT COUNT(*) AS total FROM medical_records WHERE patient_id = $pid AND visit_date >= CURDATE()")->fetch_assoc()['total'] ?? 0;

// Last Diagnosis and Assigned Doctor
$lastRecord = $conn->query("SELECT diagnosis, doctor FROM medical_records WHERE patient_id = $pid ORDER BY visit_date DESC LIMIT 1")->fetch_assoc();
$lastDiagnosis = $lastRecord['diagnosis'] ?? '-';
$assignedDoctor = $lastRecord['doctor'] ?? '-';

// Recent 5 records
$recentRecords = $conn->query("SELECT visit_date, diagnosis, treatment, doctor FROM medical_records WHERE patient_id = $pid ORDER BY visit_date DESC LIMIT 5");

// Visits per Month for chart (last 6 months)
$visitsData = [];
$months = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i month"));
    $months[] = date('M Y', strtotime("-$i month"));
    $count = $conn->query("SELECT COUNT(*) AS total FROM medical_records WHERE patient_id = $pid AND DATE_FORMAT(visit_date, '%Y-%m')='$month'")->fetch_assoc()['total'] ?? 0;
    $visitsData[] = $count;
}

// Diagnosis Breakdown (top 5)
$diagnosisLabels = [];
$diagnosisData = [];
$diagResult = $conn->query("SELECT diagnosis, COUNT(*) AS total FROM medical_records WHERE patient_id = $pid GROUP BY diagnosis ORDER BY total DESC LIMIT 5");
while ($row = $diagResult->fetch_assoc()) {
    $diagnosisLabels[] = $row['diagnosis'];
    $diagnosisData[] = $row['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Patient Dashboard - Kitere Health System</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0f172a;
            color: #fff;
        }

        .dashboard-card {
            background-color: #1e293b;
            max-width: 1200px;
            margin: 30px auto;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.6);
        }

        h2 { text-align: center; color: #f8fafc; margin-bottom: 25px; }

        .menu {
            display: flex; justify-content: center; gap: 20px; margin-bottom: 30px; flex-wrap: wrap;
        }

        .menu a {
            background-color: #1e40af; padding: 12px 25px; border-radius: 10px; color: #f8fafc;
            font-weight: bold; text-decoration: none; transition: all 0.3s;
        }

        .menu a:hover { background-color: #3b82f6; transform: translateY(-3px); }

        .stats { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; margin-bottom: 30px; }
        .stats .card {
            background-color: #334155; flex: 1 1 220px; max-width: 250px; padding: 20px; border-radius: 12px;
            text-align: center; box-shadow: 0 6px 18px rgba(0,0,0,0.4); transition: transform 0.2s, box-shadow 0.2s;
        }
        .stats .card:hover { transform: translateY(-5px); box-shadow: 0 12px 25px rgba(0,0,0,0.5); }
        .stats .card h3 { font-size: 32px; margin-bottom: 10px; color: #3b82f6; }
        .stats .card p { font-size: 16px; color: #f1f5f9; }

        .charts { display: flex; flex-wrap: wrap; gap: 30px; justify-content: center; margin-bottom: 30px; }
        .chart-container { background-color: #334155; padding: 20px; border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.4); flex: 1 1 400px; max-width: 500px; color: #fff; }
        .chart-container h3 { text-align: center; margin-bottom: 15px; color: #3b82f6; }

        .recent-records { overflow-x: auto; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; background-color: #1e293b; border-radius: 12px; overflow: hidden; }
        th, td { padding: 12px 15px; text-align: left; color: #f1f5f9; }
        th { background-color: #3b82f6; color: #fff; }
        tr:nth-child(even) { background-color: #334155; }
        tr:hover { background-color: #475569; }

        iframe { width: 100%; height: 50vh; border: none; border-radius: 12px; background-color: #f1f5f9; }

        @media (max-width: 900px) {
            .stats { flex-direction: column; align-items: center; }
            .stats .card { width: 90%; }
            .charts { flex-direction: column; align-items: center; }
            .chart-container { width: 90%; }
        }
    </style>
</head>
<body>

<div class="dashboard-card">
    <h2>Welcome, <?= $_SESSION['patient_name']; ?></h2>

    <div class="menu">
        <a href="patient_profile_edit.php" target="main">Edit Profile</a>
        <a href="medical_record.php" target="main">Medical Records</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Summary Cards -->
    <div class="stats">
        <div class="card">
            <h3><?= $totalVisits ?></h3>
            <p>Total Visits</p>
        </div>
        <div class="card">
            <h3><?= $upcomingAppointments ?></h3>
            <p>Upcoming Appointments</p>
        </div>
        <div class="card">
            <h3><?= htmlspecialchars($lastDiagnosis) ?></h3>
            <p>Last Diagnosis</p>
        </div>
        <div class="card">
            <h3><?= htmlspecialchars($assignedDoctor) ?></h3>
            <p>Assigned Doctor</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="charts">
        <div class="chart-container">
            <h3>Visits per Month</h3>
            <canvas id="visitsChart"></canvas>
        </div>
        <div class="chart-container">
            <h3>Diagnosis Breakdown</h3>
            <canvas id="diagnosisChart"></canvas>
        </div>
    </div>

    <!-- Recent Records Table -->
    <div class="recent-records">
        <h3>Recent Medical Records</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Diagnosis</th>
                    <th>Treatment</th>
                    <th>Doctor</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $recentRecords->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['visit_date']) ?></td>
                    <td><?= htmlspecialchars($row['diagnosis']) ?></td>
                    <td><?= htmlspecialchars($row['treatment']) ?></td>
                    <td><?= htmlspecialchars($row['doctor']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Iframe -->
    <iframe name="main"></iframe>
</div>

<!-- Charts JS -->
<script>
const visitsCtx = document.getElementById('visitsChart').getContext('2d');
new Chart(visitsCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($months) ?>,
        datasets: [{
            label: 'Visits',
            data: <?= json_encode($visitsData) ?>,
            backgroundColor: '#3b82f6'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { x: { ticks: { color: '#fff' }, grid: { color: '#475569' } },
                  y: { ticks: { color: '#fff' }, grid: { color: '#475569' } } }
    }
});

const diagCtx = document.getElementById('diagnosisChart').getContext('2d');
new Chart(diagCtx, {
    type: 'pie',
    data: {
        labels: <?= json_encode($diagnosisLabels) ?>,
        datasets: [{ data: <?= json_encode($diagnosisData) ?>,
                     backgroundColor: ['#3b82f6','#f97316','#22c55e','#e11d48','#8b5cf6'] }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { color:'#fff' } } } }
});
</script>

</body>
</html>
