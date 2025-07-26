<?php
session_start();
$name = isset($_SESSION['user']['admin_name']) ? $_SESSION['user']['admin_name'] : 'Admin';
require_once __DIR__ . '/../../models/Sale.php';
require_once __DIR__ . '/../../models/Therapist.php';
require_once __DIR__ . '/../../models/Service.php';
require_once __DIR__ . '/../../models/Appointment.php';
// Sync completed appointments to sales
Appointment::syncCompletedToSales();
$therapists = Therapist::getAllActive();
$services = Service::getAllActive();
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$selected_therapist = isset($_GET['therapist_id']) ? $_GET['therapist_id'] : '';
$selected_service = isset($_GET['service_id']) ? $_GET['service_id'] : '';
$show_report = isset($_GET['generate']);
$total = 0; // Tambah baris ini SEBELUM digunakan di header atas

// Adjust therapist and service filter for report generation
$therapist_filter = ($selected_therapist === '' || $selected_therapist === 'all') ? '' : $selected_therapist;
$service_filter = ($selected_service === '' || $selected_service === 'all') ? '' : $selected_service;
$sales = ($show_report && $start_date && $end_date) ? Sale::getAll($start_date, $end_date, $therapist_filter, $service_filter) : [];

// Get unapproved therapist count
$unapproved_count = Therapist::countUnapproved();
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
            <a href="/public/index.php?page=admin_dashboard" style="display:flex;align-items:center;padding:0.9rem 2rem;color:#fff;text-decoration:none;font-size:1.08rem;transition:background 0.2s, border-left 0.2s;font-weight:500;border-left:4px solid transparent;" onmouseover="this.style.background='#1a2027';this.style.borderLeft='4px solid #3b5998'" onmouseout="this.style.background='none';this.style.borderLeft='4px solid transparent'">
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
            <a href="/public/index.php?page=admin_sales_report" style="display:flex;align-items:center;padding:0.9rem 2rem;color:#fff;text-decoration:none;font-size:1.08rem;transition:background 0.2s, border-left 0.2s;font-weight:500;border-left:4px solid #3b5998;background:#232a32;">
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
        <!-- Sales Report Table -->
        <div style="max-width:900px;margin:2.5rem auto;padding:2.5rem 2rem;background:#fff;border-radius:1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.07);">
          <h2 style="font-size:1.5rem;font-weight:600;color:#232a32;margin-bottom:1.5rem;">Sales Report</h2>
          <form method="get" style="display:flex;gap:1.2rem;align-items:end;margin-bottom:2rem;flex-wrap:wrap;">
            <input type="hidden" name="page" value="admin_sales_report">
            <input type="hidden" name="generate" value="1">
            <div>
              <label for="start_date" style="font-weight:500;">From:</label>
              <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>" style="padding:0.45rem 0.8rem;border:1px solid #bfc9d9;border-radius:6px;font-size:1rem;">
            </div>
            <div>
              <label for="end_date" style="font-weight:500;">To:</label>
              <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>" style="padding:0.45rem 0.8rem;border:1px solid #bfc9d9;border-radius:6px;font-size:1rem;">
            </div>
            <div style="display:flex;align-items:center;gap:0.7rem;">
              <label><input type="checkbox" name="show_service" value="1" <?= isset($_GET['show_service']) ? 'checked' : '' ?>> Service</label>
              <label><input type="checkbox" name="show_therapist" value="1" <?= isset($_GET['show_therapist']) ? 'checked' : '' ?>> Therapist</label>
            </div>
            <button type="submit" style="padding:0.45rem 1.3rem;background:#3b5998;color:white;border:none;border-radius:6px;font-weight:500;font-size:1rem;cursor:pointer;">Generate</button>
          </form>
          <?php if ($show_report && (empty($start_date) || empty($end_date))): ?>
            <div style="color:#c62828;background:#fdeaea;padding:1rem 1.5rem;border-radius:10px;margin-bottom:1.5rem;text-align:center;">
              Please choose a date range to generate the report.
            </div>
          <?php endif; ?>
          <?php if (!empty($sales)) : ?>
          <form method="post" style="display:flex;gap:1rem;margin-bottom:1.5rem;">
            <input type="hidden" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
            <input type="hidden" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
            <button type="submit" name="download" value="pdf" style="background:#e53935;color:#fff;padding:0.5rem 1.2rem;border-radius:8px;font-size:1rem;font-weight:500;border:none;">PDF</button>
            <button type="submit" name="download" value="excel" style="background:#388e3c;color:#fff;padding:0.5rem 1.2rem;border-radius:8px;font-size:1rem;font-weight:500;border:none;">Excel</button>
          </form>
          <?php endif; ?>
          <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:1.08rem;">
              <thead>
                <tr style="background:#e6eaf3;color:#232a32;">
                  <th style="padding:1rem 0.5rem;text-align:center;">No</th>
                  <th style="padding:1rem 0.5rem;text-align:center;">Customer Name</th>
                  <?php if (isset($_GET['show_service'])): ?><th style="padding:1rem 0.5rem;text-align:center;">Service</th><?php endif; ?>
                  <?php if (isset($_GET['show_therapist'])): ?><th style="padding:1rem 0.5rem;text-align:center;">Therapist</th><?php endif; ?>
                  <th style="padding:1rem 0.5rem;text-align:center;">Date</th>
                  <th style="padding:1rem 0.5rem;text-align:center;">Amount (RM)</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $total = 0;
                if (!empty($sales)): 
                    foreach ($sales as $i => $s): 
                        $total += $s['amount'];
                ?>
                <tr style="background:<?= $i%2==0 ? '#f8fafd' : '#fff' ?>;">
                  <td style="padding:0.9rem 0.5rem;text-align:center;"> <?= $i+1 ?> </td>
                  <td style="padding:0.9rem 0.5rem;text-align:center;"> <?= htmlspecialchars($s['customer_name']) ?> </td>
                  <?php if (isset($_GET['show_service'])): ?><td style="padding:0.9rem 0.5rem;text-align:center;"> <?= htmlspecialchars($s['service_name']) ?> </td><?php endif; ?>
                  <?php if (isset($_GET['show_therapist'])): ?><td style="padding:0.9rem 0.5rem;text-align:center;"> <?= htmlspecialchars($s['therapist_name']) ?> </td><?php endif; ?>
                  <td style="padding:0.9rem 0.5rem;text-align:center;"> <?= htmlspecialchars($s['date']) ?> </td>
                  <td style="padding:0.9rem 0.5rem;text-align:center;"> <?= number_format($s['amount'],2) ?> </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="<?= 4 + (isset($_GET['show_service'])?1:0) + (isset($_GET['show_therapist'])?1:0) ?>" style="text-align:center;padding:1.5rem;">No sales found.</td></tr>
                <?php endif; ?>
              </tbody>
              <tfoot>
                <tr style="background:#e6eaf3;font-weight:bold;">
                  <td colspan="<?= 3 + (isset($_GET['show_service'])?1:0) + (isset($_GET['show_therapist'])?1:0) ?>" style="text-align:right;padding:1rem 0.5rem;">Total</td>
                  <td style="padding:1rem 0.5rem;text-align:center;"> <?= number_format($total,2) ?> </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        </div>
    </main>
</div> 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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