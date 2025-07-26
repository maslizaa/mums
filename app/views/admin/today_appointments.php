<?php
session_start();
$name = isset($_SESSION['user']['admin_name']) ? $_SESSION['user']['admin_name'] : 'Admin';
require_once __DIR__ . '/../../models/Appointment.php';
require_once __DIR__ . '/../../models/Therapist.php';
$therapists = Therapist::getAllActive();
$selected_therapist = isset($_GET['therapist_id']) ? $_GET['therapist_id'] : '';
$appointments = Appointment::getTodayConfirmed($selected_therapist);
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
            <a href="/public/index.php?page=admin_today_appointments" style="display:flex;align-items:center;padding:0.9rem 2rem;color:#fff;text-decoration:none;font-size:1.08rem;transition:background 0.2s, border-left 0.2s;font-weight:500;border-left:4px solid #3b5998;background:#232a32;">
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
        <!-- Today Appointments Table -->
        <div style="max-width:1100px;margin:2.5rem auto 2rem auto;background:#f8fafd;padding:2rem 2rem 1.5rem 2rem;border-radius:1.2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
            <form method="get" style="margin-bottom:1.5rem;display:flex;align-items:center;gap:1rem;">
                <input type="hidden" name="page" value="admin_today_appointments">
                <label for="therapist_id" style="font-weight:500;">Filter by Therapist:</label>
                <select name="therapist_id" id="therapist_id" style="padding:0.6rem 1rem;border-radius:0.7rem;border:1.5px solid #e6eaf3;font-size:1.05rem;">
                    <option value="">All Therapists</option>
<?php foreach ($therapists as $t): ?>
                    <option value="<?= htmlspecialchars($t['therapist_id']) ?>" <?= $selected_therapist == $t['therapist_id'] ? 'selected' : '' ?>><?= htmlspecialchars($t['therapist_name']) ?> (Strength: <?= htmlspecialchars($t['therapist_strength']) ?>)</option>
<?php endforeach; ?>
                </select>
                <button type="submit" style="background:#3b5998;color:#fff;padding:0.7rem 1.5rem;border-radius:1rem;font-size:1.05rem;font-weight:500;border:none;">Filter</button>
            </form>
            <h3 style="font-size:1.2rem;font-weight:600;margin-bottom:1.2rem;color:#232a32;">Today's Confirmed Appointments</h3>
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#e6eaf3;">
                            <th style="padding:1rem 0.5rem;">No</th>
                            <th style="padding:1rem 0.5rem;">Customer Name</th>
                            <th style="padding:1rem 0.5rem;">Service</th>
                            <th style="padding:1rem 0.5rem;">Therapist</th>
                            <th style="padding:1rem 0.5rem;">Date</th>
                            <th style="padding:1rem 0.5rem;">Time</th>
                            <th style="padding:1rem 0.5rem;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($appointments)): ?>
                        <tr><td colspan="7" style="text-align:center;padding:2rem;color:#888;">No confirmed appointments for today.</td></tr>
                        <?php else: $i=1; foreach ($appointments as $a): ?>
                        <tr style="border-bottom:1px solid #e6eaf3;">
                            <td style="padding:0.8rem 0.5rem;"> <?= $i++ ?> </td>
                            <td style="padding:0.8rem 0.5rem;"> <?= htmlspecialchars($a['customer_name']) ?> </td>
                            <td style="padding:0.8rem 0.5rem;"> <?= htmlspecialchars($a['service_name']) ?> </td>
                            <td style="padding:0.8rem 0.5rem;"> <?= htmlspecialchars($a['therapist_name']) ?> </td>
                            <td style="padding:0.8rem 0.5rem;"> <?= htmlspecialchars($a['date']) ?> </td>
                            <td style="padding:0.8rem 0.5rem;"> <?= htmlspecialchars($a['time']) ?> </td>
                            <td style="padding:0.8rem 0.5rem;"> <span style="color:#4caf50;font-weight:600;">Confirmed</span> 
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="appointment_id" value="<?= htmlspecialchars($a['appointment_id']) ?>">
                                    <input type="hidden" name="action" value="mark_complete">
                                    <button type="submit" style="background:#4caf50;color:#fff;padding:0.5rem 1.2rem;border:none;border-radius:1.2rem;font-weight:600;font-size:1rem;margin-left:10px;cursor:pointer;">Mark as Complete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
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