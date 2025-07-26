<?php
session_start();
$name = isset($_SESSION['user']['admin_name']) ? $_SESSION['user']['admin_name'] : 'Admin';
require_once __DIR__ . '/../../models/Appointment.php';
$year = date('Y');
$month = date('m');
$today = date('Y-m-d');
$dailyCount = Appointment::getDailyCount($today);
$weeklyCounts = Appointment::getWeeklyCounts();
$monthlyCounts = Appointment::getMonthlyCounts($year, $month);
$yearlyCounts = Appointment::getYearlyMonthlyCounts($year);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<div style="display:flex;min-height:100vh;background:#f5f5f5;">
    <!-- Sidebar -->
    <aside style="width:220px;background:#232a32;color:#fff;display:flex;flex-direction:column;align-items:flex-start;padding:2rem 0 0 0;min-height:100vh;border-right:2px solid #e6eaf3;">
        <div style="width:100%;text-align:center;margin-bottom:2.5rem;">
  <div style="
    font-family: 'Georgia', 'Times New Roman', serif;
    font-size: 1.2rem;
    font-weight: bold;
    color: #fff;
    background: #2d3640;
    border-radius: 12px;
    padding: 14px 0 6px 0;
    letter-spacing: 1.5px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin: 0 auto 1.5rem auto;
    width: 90%;
    max-width: 160px;
    ">
    Spa Makcik Urut <br>
    <span style="font-size:1.05rem;font-weight:normal;letter-spacing:2px;color:#bfcf7c;">Muslimah</span>
  </div>
</div>
        <nav style="width:100%;">
            <a href="/public/index.php?page=admin_dashboard" style="display:flex;align-items:center;padding:0.9rem 2rem;color:#fff;text-decoration:none;font-size:1.08rem;transition:background 0.2s, border-left 0.2s;font-weight:500;border-left:4px solid #3b5998;background:#232a32;">
                <i class="fas fa-chart-bar" style="margin-right:16px;font-size:1.3rem;"></i>Dashboard
            </a>
            <a href="/public/index.php?page=admin_services" style="display:flex;align-items:center;padding:0.9rem 2rem;color:#fff;text-decoration:none;font-size:1.08rem;transition:background 0.2s, border-left 0.2s;font-weight:500;border-left:4px solid transparent;" onmouseover="this.style.background='#1a2027';this.style.borderLeft='4px solid #3b5998'" onmouseout="this.style.background='none';this.style.borderLeft='4px solid transparent'">
                <i class="fas fa-cut" style="margin-right:16px;font-size:1.3rem;"></i>Services
            </a>
            <a href="/public/index.php?page=admin_therapists" style="display:flex;align-items:center;padding:0.9rem 2rem;color:#fff;text-decoration:none;font-size:1.08rem;transition:background 0.2s, border-left 0.2s;font-weight:500;border-left:4px solid transparent;" onmouseover="this.style.background='#1a2027';this.style.borderLeft='4px solid #3b5998'" onmouseout="this.style.background='none';this.style.borderLeft='4px solid transparent'">
                <i class="fas fa-user-md" style="margin-right:16px;font-size:1.3rem;"></i>Therapists
            </a>
            <a href="/public/index.php?page=manage_feedback" style="display:flex;align-items:center;padding:0.9rem 2rem;color:#fff;text-decoration:none;font-size:1.08rem;transition:background 0.2s, border-left 0.2s;font-weight:500;border-left:4px solid transparent;" onmouseover="this.style.background='#1a2027';this.style.borderLeft='4px solid #3b5998'" onmouseout="this.style.background='none';this.style.borderLeft='4px solid transparent'">
                <i class="fas fa-comments" style="margin-right:16px;font-size:1.3rem;"></i>Feedback
            </a>
            <a href="/public/index.php?page=admin_appointments" style="display:flex;align-items:center;padding:0.9rem 2rem;color:#fff;text-decoration:none;font-size:1.08rem;transition:background 0.2s, border-left 0.2s;font-weight:500;border-left:4px solid transparent;" onmouseover="this.style.background='#1a2027';this.style.borderLeft='4px solid #3b5998'" onmouseout="this.style.background='none';this.style.borderLeft='4px solid transparent'">
                <i class="fas fa-calendar-alt" style="margin-right:16px;font-size:1.3rem;"></i>All Appointments
            </a>
            <a href="/public/index.php?page=admin_today_appointments" style="display:flex;align-items:center;padding:0.9rem 2rem;color:#fff;text-decoration:none;font-size:1.08rem;transition:background 0.2s, border-left 0.2s;font-weight:500;border-left:4px solid transparent;" onmouseover="this.style.background='#1a2027';this.style.borderLeft='4px solid #3b5998'" onmouseout="this.style.background='none';this.style.borderLeft='4px solid transparent'">
                <i class="fas fa-plus-circle" style="margin-right:16px;font-size:1.3rem;"></i>Today Appointments
            </a>
            <a href="/public/index.php?page=admin_sales_report" style="display:flex;align-items:center;padding:0.9rem 2rem;color:#fff;text-decoration:none;font-size:1.08rem;transition:background 0.2s, border-left 0.2s;font-weight:500;border-left:4px solid transparent;" onmouseover="this.style.background='#1a2027';this.style.borderLeft='4px solid #3b5998'" onmouseout="this.style.background='none';this.style.borderLeft='4px solid transparent'">
                <i class="fas fa-chart-line" style="margin-right:16px;font-size:1.3rem;"></i>Sales Report
            </a>
            <a href="/public/index.php?page=logout" style="display:flex;align-items:center;padding:0.9rem 2rem;color:#fff;text-decoration:none;font-size:1.08rem;transition:background 0.2s, border-left 0.2s;font-weight:500;border-left:4px solid transparent;" onmouseover="this.style.background='#1a2027';this.style.borderLeft='4px solid #e53935'" onmouseout="this.style.background='none';this.style.borderLeft='4px solid transparent'">
                <i class="fas fa-sign-out-alt" style="margin-right:16px;font-size:1.3rem;"></i>Log out
            </a>
        </nav>
    </aside>
    <!-- Main Content -->
    <main style="flex:1;font-family:'Segoe UI',Roboto,Arial,sans-serif;">
        <!-- Topbar -->
        <div style="background:#fff;padding:1rem 2rem;display:flex;align-items:center;justify-content:flex-end;box-shadow:0 2px 8px rgba(0,0,0,0.03);">
            <div style="margin-right:2rem;display:flex;align-items:center;gap:1.2rem;">
                <span style="color:#7d7d7d;font-size:1.3rem;position:relative;cursor:pointer;" id="admin-notification-bell">
                    <i class="fas fa-bell" style="font-size:1.5rem;"></i>
                    <?php if (!empty($unapproved_count) && $unapproved_count > 0): ?>
                        <span id="notification-badge" style="position:absolute;top:-6px;right:-6px;background:#e53935;color:#fff;font-size:0.85rem;padding:2px 7px;border-radius:50%;font-weight:bold;min-width:22px;text-align:center;"> <?= $unapproved_count ?> </span>
                    <?php endif; ?>
                </span>
                <span style="font-weight:500;"> <?= htmlspecialchars($name) ?> </span>
                <a href="/public/index.php?page=admin_profile" style="display:inline-block;"><img src="<?= isset($_SESSION['user']['admin_photo']) && $_SESSION['user']['admin_photo'] ? htmlspecialchars($_SESSION['user']['admin_photo']) : '/public/assets/images/profile.png' ?>" alt="Profile" style="height:36px;width:36px;border-radius:50%;object-fit:cover;border:2px solid #eee;cursor:pointer;"></a>
            </div>
        </div>
        <!-- Dashboard Content -->
        <div style="padding:2.5rem 0;max-width:1100px;margin:0 auto;">
            <div style="display:flex;align-items:center;gap:1.2rem;margin-bottom:2.5rem;">
                <div style="font-size:1.8rem;display:flex;align-items:center;justify-content:center;">
                    📊
                </div>
                <h1 style="font-size:2.2rem;font-weight:700;color:#232a32;letter-spacing:0.5px;margin:0;">Dashboard Overview</h1>
            </div>
            <div style="display:flex;flex-wrap:wrap;gap:2rem;justify-content:space-between;">
                <div style="flex:1 1 45%;min-width:320px;background:#fff;padding:2rem 1.5rem;border-radius:1.2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);text-align:center;margin-bottom:2rem;">
                    <div style="font-size:1.1rem;font-weight:500;margin-bottom:0.5rem;color:#232a32;">Today's Appointments (<?= date('l, d M Y') ?>)</div>
                    <div style="font-size:2.2rem;font-weight:bold;color:#3b5998;"><?= $dailyCount ?></div>
                </div>
                <div style="flex:1 1 45%;min-width:320px;background:#fff;padding:2rem 1.5rem;border-radius:1.2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);text-align:center;margin-bottom:2rem;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <div style="font-size:1.1rem;font-weight:500;margin-bottom:0.5rem;color:#232a32;">Weekly Appointments</div>
                    <canvas id="weeklyChart" width="320" height="180"></canvas>
                </div>
                <div style="flex:1 1 45%;min-width:320px;background:#fff;padding:2rem 1.5rem;border-radius:1.2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);text-align:center;margin-bottom:2rem;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <div style="font-size:1.1rem;font-weight:500;margin-bottom:0.5rem;color:#232a32;">Monthly Appointments (<?= date('F Y') ?>)</div>
                    <canvas id="monthlyChart" width="320" height="180"></canvas>
                </div>
                <div style="flex:1 1 45%;min-width:320px;background:#fff;padding:2rem 1.5rem;border-radius:1.2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);text-align:center;margin-bottom:2rem;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <div style="font-size:1.1rem;font-weight:500;margin-bottom:0.5rem;color:#232a32;">Yearly Appointments (<?= $year ?>)</div>
                    <canvas id="yearlyChart" width="320" height="180"></canvas>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const weekLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
const weekData = <?= json_encode(array_values($weeklyCounts)) ?>;
const weekCtx = document.getElementById('weeklyChart').getContext('2d');
new Chart(weekCtx, {
    type: 'bar',
    data: { labels: weekLabels, datasets: [{ label: 'Appointments', data: weekData, backgroundColor: '#bfcf7c', borderRadius: 8 }] },
    options: { responsive: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
const monthDays = Array.from({length: Object.keys(<?= json_encode($monthlyCounts) ?>).length}, (_,i)=>i+1);
const monthData = <?= json_encode(array_values($monthlyCounts)) ?>;
const monthCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthCtx, {
    type: 'line',
    data: { labels: monthDays, datasets: [{ label: 'Appointments', data: monthData, borderColor: '#3b5998', backgroundColor: 'rgba(59,89,152,0.1)', tension:0.3, fill:true }] },
    options: { responsive: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
const yearLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const yearData = <?= json_encode(array_values($yearlyCounts)) ?>;
const yearCtx = document.getElementById('yearlyChart').getContext('2d');
new Chart(yearCtx, {
    type: 'bar',
    data: { labels: yearLabels, datasets: [{ label: 'Appointments', data: yearData, backgroundColor: '#3b5998', borderRadius: 8 }] },
    options: { responsive: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
document.addEventListener('DOMContentLoaded', function() {
  var bell = document.getElementById('admin-notification-bell');
  if (bell) {
    bell.addEventListener('click', function() {
      <?php if (!empty($unapproved_count) && $unapproved_count > 0): ?>
        Swal.fire({
          icon: 'info',
          title: 'New Therapist Registration',
          html: '<b><?= $unapproved_count ?></b> new therapist(s) awaiting approval.',
          confirmButtonText: 'View',
          showCancelButton: true,
          cancelButtonText: 'Close',
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = '/public/index.php?page=admin_therapists';
          }
        });
      <?php else: ?>
        Swal.fire({
          icon: 'success',
          title: 'No New Notifications',
          text: 'There are no new therapist registrations awaiting approval.'
        });
      <?php endif; ?>
    });
  }
});
</script> 