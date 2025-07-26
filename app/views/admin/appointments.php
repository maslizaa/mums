<?php
session_start();
$name = isset($_SESSION['user']['admin_name']) ? $_SESSION['user']['admin_name'] : 'Admin';
require_once __DIR__ . '/../../models/Therapist.php';
$unapproved_count = Therapist::countUnapproved();
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
$today = date('Y-m-d');
?>
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
            <a href="/public/index.php?page=admin_appointments" style="display:flex;align-items:center;padding:0.9rem 2rem;color:#fff;text-decoration:none;font-size:1.08rem;transition:background 0.2s, border-left 0.2s;font-weight:500;border-left:4px solid #3b5998;background:#232a32;">
                <i class="fas fa-calendar-alt" style="margin-right:16px;font-size:1.3rem;"></i> All Appointments
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
        <!-- Appointments Content -->
        <div style="max-width:1100px;margin:2.5rem auto;padding:2.5rem 2rem;background:#fff;border-radius:1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.07);">
            <?php if (!empty($msg)) echo $msg; ?>
            <?php if (isset($_GET['rescheduled']) && $_GET['rescheduled'] == 1): ?>
  <div style="background:#eafbe7;color:#388e3c;padding:1rem 1.5rem;border-radius:10px;margin-bottom:1.5rem;font-size:1.15rem;text-align:center;box-shadow:0 2px 8px rgba(56,142,60,0.07);">
    Appointment successfully rescheduled!<br>
    <b>Please state your name on the appointment.</b>
  </div>
<?php endif; ?>
<?php if (isset($_GET['cancelled']) && $_GET['cancelled'] == 1): ?>
  <div style="background:#fdeaea;color:#c62828;padding:1rem 1.5rem;border-radius:10px;margin-bottom:1.5rem;font-size:1.15rem;text-align:center;box-shadow:0 2px 8px rgba(198,40,40,0.07);">
    Appointment cancelled successfully!
  </div>
<?php endif; ?>
<?php if (isset($_GET['status_updated']) && $_GET['status_updated'] == 1): ?>
  <div style="background:#eafbe7;color:#388e3c;padding:1rem 1.5rem;border-radius:10px;margin-bottom:1.5rem;font-size:1.15rem;text-align:center;box-shadow:0 2px 8px rgba(56,142,60,0.07);">
    Appointment status updated successfully!
  </div>
<?php endif; ?>
<?php if (isset($_GET['error'])): ?>
  <div style="background:#fdeaea;color:#c62828;padding:1rem 1.5rem;border-radius:10px;margin-bottom:1.5rem;font-size:1.15rem;text-align:center;box-shadow:0 2px 8px rgba(198,40,40,0.07);">
    <?= htmlspecialchars($_GET['error']) ?>
  </div>
<?php endif; ?>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;">
                <h2 style="font-size:1.6rem;font-weight:600;color:#232a32;letter-spacing:0.5px;">Manage Appointments</h2>
                <div style="display:flex;justify-content:flex-end;align-items:center;gap:1.5rem;margin-bottom:1.5rem;">
                    <form method="get" action="" id="filterForm" style="background:linear-gradient(135deg, #f8fafd, #f1f5f9);padding:1.5rem 2rem;border-radius:16px;box-shadow:0 4px 12px rgba(0,0,0,0.08);display:flex;align-items:center;gap:1.5rem;margin:0;border:1px solid #e2e8f0;">
                        <input type="hidden" name="page" value="admin_appointments">
                        <div style="display:flex;flex-direction:column;gap:0.3rem;">
                            <label for="filter_month" style="font-weight:600;font-size:0.95rem;color:#475569;">Filter by Month:</label>
                            <div style="display:flex;align-items:center;gap:0.8rem;">
                                <select name="filter_month" id="filter_month" style="padding:0.6rem 1rem;border:1.5px solid #cbd5e1;border-radius:8px;font-size:0.95rem;background:#fff;transition:border-color 0.2s;min-width:110px;" onchange="document.getElementById('filterForm').submit();">
                                    <?php
                                    $months = [
                                        '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
                                        '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
                                        '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
                                    ];
                                    $selected_month = isset($_GET['filter_month']) ? $_GET['filter_month'] : date('m');
                                    foreach ($months as $num => $name) {
                                        $selected = ($selected_month == $num) ? 'selected' : '';
                                        echo "<option value='$num' $selected>$name</option>";
                                    }
                                    ?>
                                </select>
                                <select name="filter_year" id="filter_year" style="padding:0.6rem 1rem;border:1.5px solid #cbd5e1;border-radius:8px;font-size:0.95rem;background:#fff;transition:border-color 0.2s;min-width:90px;" onchange="document.getElementById('filterForm').submit();">
                                    <?php
                                    $current_year = date('Y');
                                    $start_year = $current_year - 3;
                                    $end_year = $current_year + 2;
                                    $selected_year = isset($_GET['filter_year']) ? $_GET['filter_year'] : $current_year;
                                    for ($y = $start_year; $y <= $end_year; $y++) {
                                        $selected = ($selected_year == $y) ? 'selected' : '';
                                        echo "<option value='$y' $selected>$y</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:0.3rem;">
                            <label for="status_filter" style="font-weight:600;font-size:0.95rem;color:#475569;">Status:</label>
                            <select name="status_filter" id="status_filter" style="padding:0.6rem 1rem;border:1.5px solid #cbd5e1;border-radius:8px;font-size:0.95rem;background:#fff;transition:border-color 0.2s;min-width:140px;" onfocus="this.style.borderColor='#3b82f6';" onblur="this.style.borderColor='#cbd5e1';" onchange="document.getElementById('filterForm').submit();">
                                <option value="">All Status</option>
                                <option value="booked" <?= (isset($_GET['status_filter']) && $_GET['status_filter'] === 'booked') ? 'selected' : '' ?>>Booked</option>
                                <option value="completed" <?= (isset($_GET['status_filter']) && $_GET['status_filter'] === 'completed') ? 'selected' : '' ?>>Completed</option>
                                <option value="cancelled" <?= (isset($_GET['status_filter']) && $_GET['status_filter'] === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                                <option value="rescheduled" <?= (isset($_GET['status_filter']) && $_GET['status_filter'] === 'rescheduled') ? 'selected' : '' ?>>Rescheduled</option>
                            </select>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:0.3rem;">
                            <label for="therapist_filter" style="font-weight:600;font-size:0.95rem;color:#475569;">Therapist:</label>
                            <select name="therapist_filter" id="therapist_filter" style="padding:0.6rem 1rem;border:1.5px solid #cbd5e1;border-radius:8px;font-size:0.95rem;background:#fff;transition:border-color 0.2s;min-width:160px;" onfocus="this.style.borderColor='#3b82f6';" onblur="this.style.borderColor='#cbd5e1';" onchange="document.getElementById('filterForm').submit();">
                                <option value="">All Therapists</option>
                                <?php foreach ($therapists as $t): ?>
                                    <option value="<?= htmlspecialchars($t['therapist_id']) ?>" <?= (isset($_GET['therapist_filter']) && $_GET['therapist_filter'] == $t['therapist_id']) ? 'selected' : '' ?>><?= htmlspecialchars($t['therapist_name']) ?> (Strength: <?= htmlspecialchars($t['therapist_strength']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                    <a href="/public/index.php?page=admin_add_appointment" style="background:linear-gradient(135deg, #3b5998, #2d4a6b);color:#fff;padding:0.9rem 2rem;border-radius:12px;font-size:1.05rem;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:0.7rem;box-shadow:0 4px 12px rgba(59,89,152,0.3);transition:all 0.2s;" onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 16px rgba(59,89,152,0.4)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 12px rgba(59,89,152,0.3)';"><i class="fas fa-plus"></i> Add Appointment</a>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <script>
function showCancelReasonModal(id) {
  document.getElementById('cancelReasonModal').style.display = 'block';
}
function closeCancelReasonModal() {
  document.getElementById('cancelReasonModal').style.display = 'none';
}
function showRescheduleModal(id) {
  document.getElementById('rescheduleModal').style.display = 'block';
}
function closeRescheduleModal() {
  document.getElementById('rescheduleModal').style.display = 'none';
}

function validateRescheduleForm() {
  const selectedTime = document.getElementById('reschedule-selected-time').value;
  if (!selectedTime) {
    alert('Please select a time slot before submitting.');
    return false;
  }
  return true;
}
</script>
<!-- Cancel Reason Modal -->
<div id="cancelReasonModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;padding:2rem 2.5rem;border-radius:1.2rem;max-width:400px;margin:10vh auto;box-shadow:0 2px 12px rgba(0,0,0,0.12);position:relative;">
    <h3 style="margin-bottom:1rem;">Cancel Appointment</h3>
    <form method="post" action="/public/index.php?page=admin_appointments">
      <input type="hidden" name="appointment_id" value="">
      <input type="hidden" name="action" value="cancel">
      <label style="font-weight:500;">Reason for cancellation:</label>
      <textarea name="cancel_reason" required style="width:100%;padding:0.7rem 1rem;border-radius:0.7rem;border:1.5px solid #e6eaf3;font-size:1.05rem;margin:0.5rem 0 1rem 0;"></textarea>
      <div style="display:flex;gap:1rem;justify-content:flex-end;">
        <button type="submit" style="background:#c62828;color:#fff;padding:0.7rem 1.5rem;border-radius:1rem;font-size:1.05rem;font-weight:500;border:none;">Submit</button>
        <button type="button" onclick="closeCancelReasonModal()" style="background:#eee;color:#232a32;padding:0.7rem 1.5rem;border-radius:1rem;font-size:1.05rem;font-weight:500;border:none;">Close</button>
      </div>
    </form>
  </div>
</div>
<!-- Reschedule Modal -->
<div id="rescheduleModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;padding:2rem 2.5rem;border-radius:1.2rem;max-width:420px;margin:10vh auto;box-shadow:0 2px 12px rgba(0,0,0,0.12);position:relative;">
    <h3 style="margin-bottom:1rem;">Reschedule Appointment</h3>
    <form id="rescheduleForm" method="post" style="display:flex;flex-direction:column;gap:1.2rem;" onsubmit="return validateRescheduleForm()">
      <input type="hidden" name="appointment_id">
      <input type="hidden" name="action" value="reschedule">
      <label style="font-weight:500;">New Date:</label>
      <input type="date" name="new_date" id="reschedule-date" required style="width:100%;padding:0.7rem 1rem;border-radius:0.7rem;border:1.5px solid #e6eaf3;font-size:1.05rem;">
      <label style="font-weight:500;">New Time:</label>
      <div id="reschedule-time-slots" style="display:flex;flex-wrap:wrap;gap:10px 12px;margin-bottom:1rem;"></div>
      <input type="hidden" name="new_time" id="reschedule-selected-time" required>
      <div style="display:flex;gap:1rem;justify-content:flex-end;">
        <button type="submit" style="background:#3b5998;color:#fff;padding:0.7rem 1.5rem;border-radius:1rem;font-size:1.05rem;font-weight:500;border:none;">Submit</button>
        <button type="button" onclick="closeRescheduleModal()" style="background:#eee;color:#232a32;padding:0.7rem 1.5rem;border-radius:1rem;font-size:1.05rem;font-weight:500;border:none;">Close</button>
      </div>
    </form>
  </div>
</div>
<!-- Appointments Table (actions updated) -->
<table style="width:100%;border-collapse:collapse;font-size:1.08rem;">
  <thead>
    <tr style="background:#e6eaf3;color:#232a32;">
      <th style="padding:1rem 0.5rem;">No</th>
      <th style="padding:1rem 0.5rem;">Customer Name</th>
      <th style="padding:1rem 0.5rem;">Service</th>
      <th style="padding:1rem 0.5rem;">Therapist</th>
      <th style="padding:1rem 0.5rem;">Date</th>
      <th style="padding:1rem 0.5rem;">Time</th>
      <th style="padding:1rem 0.5rem;">Status</th>
      <th style="padding:1rem 0.5rem;">Actions</th>
    </tr>
  </thead>
  <tbody>
<?php if (!empty($appointments)): ?>
    <?php 
    $rowNo = 1;
    foreach ($appointments as $a): 
?>
        <tr style="background:#f8fafd;">
            <td style="padding:0.9rem 0.5rem;"> <?= $rowNo++ ?> </td>
            <td style="padding:0.9rem 0.5rem;"> <?= htmlspecialchars($a['customer_name']) ?> </td>
            <td style="padding:0.9rem 0.5rem;"> <?= htmlspecialchars($a['service_name'] ?? '-') ?> </td>
            <td style="padding:0.9rem 0.5rem;"> <?= htmlspecialchars($a['therapist_name'] ?? '-') ?> </td>
            <td style="padding:0.9rem 0.5rem;"> <?= htmlspecialchars($a['date']) ?> </td>
            <td style="padding:0.9rem 0.5rem;"> <?= htmlspecialchars($a['time']) ?> </td>
            <td style="padding:0.9rem 0.5rem;">
                <form method="post" action="" style="display:inline;">
                    <input type="hidden" name="toggle_appointment_status_id" value="<?= $a['appointment_id'] ?>">
                    <?php if (strtolower($a['status']) === 'booked'): ?>
                        <button type="submit" name="set_completed" style="background:#e6fbe6;color:#2e7d32;padding:0.4rem 1.2rem;border-radius:1.2rem;font-weight:500;border:none;cursor:pointer;">Booked</button>
                    <?php elseif (strtolower($a['status']) === 'completed'): ?>
                        <button type="submit" name="set_booked" style="background:#e3f2fd;color:#1565c0;padding:0.4rem 1.2rem;border-radius:1.2rem;font-weight:500;border:none;cursor:pointer;">Completed</button>
                    <?php elseif (strtolower($a['status']) === 'cancelled'): ?>
                        <button type="submit" name="set_booked" style="background:#fdeaea;color:#c62828;padding:0.4rem 1.2rem;border-radius:1.2rem;font-weight:500;border:none;cursor:pointer;">Cancelled</button>
                    <?php elseif (strtolower($a['status']) === 'rescheduled'): ?>
                        <button type="submit" name="set_booked" style="background:#fff3e0;color:#ef6c00;padding:0.4rem 1.2rem;border-radius:1.2rem;font-weight:500;border:none;cursor:pointer;">Rescheduled</button>
                    <?php else: ?>
                        <button type="submit" name="set_booked" style="background:#f5f5f5;color:#666;padding:0.4rem 1.2rem;border-radius:1.2rem;font-weight:500;border:none;cursor:pointer;"><?= htmlspecialchars(ucfirst($a['status'])) ?></button>
                    <?php endif; ?>
                </form>
            </td>
            <td style="padding:0.9rem 0.5rem;display:flex;gap:0.2rem;flex-wrap:wrap;align-items:center;<?php if (strtolower($a['status']) === 'completed' || strtolower($a['status']) === 'cancelled') echo 'justify-content:center;'; ?>">
                <button type="button" class="btn-view btn-action" data-id="<?= $a['appointment_id'] ?>" style="<?php if (strtolower($a['status']) === 'completed' || strtolower($a['status']) === 'cancelled') echo 'min-width:120px;'; ?>">View</button>
                <?php if (strtolower($a['status']) !== 'cancelled' && strtolower($a['status']) !== 'completed'): ?>
                    <button type="button" class="btn-cancel btn-action" data-id="<?= $a['appointment_id'] ?>" data-name="<?= htmlspecialchars(addslashes($a['customer_name'])) ?>">Cancel</button>
                    <button type="button" class="btn-reschedule btn-action" data-id="<?= $a['appointment_id'] ?>" data-therapist-id="<?= $a['therapist_id'] ?>">Reschedule</button>
                <?php endif; ?>
                <?php if (strtolower($a['status']) !== 'completed' && strtolower($a['status']) !== 'cancelled' && $a['date'] <= $today): ?>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="action" value="mark_complete">
                        <input type="hidden" name="appointment_id" value="<?= $a['appointment_id'] ?>">
                        <button type="submit" style="background:#4caf50;color:#fff;padding:0.4rem 1.1rem;border-radius:1rem;font-weight:500;border:none;cursor:pointer;">Mark as Complete</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
                    <tr><td colspan="9" style="text-align:center;padding:3rem 1rem;">
                    <div style="display:flex;flex-direction:column;align-items:center;gap:1rem;">
                        <span style="font-size:3rem;">📅</span>
                        <div style="font-size:1.2rem;font-weight:500;color:#475569;">No appointments found</div>
                        <div style="font-size:0.95rem;color:#94a3b8;">Try adjusting your filters or check back later</div>
                    </div>
                </td></tr>
<?php endif; ?>
</tbody>
</table>
<script>
function confirmDelete(appointmentId, customerName) {
    document.getElementById('deleteAppointmentName').textContent = customerName;
    document.getElementById('deleteConfirmBtn').onclick = function() {
        window.location.href = '/public/index.php?page=admin_appointments&delete_id=' + appointmentId;
    };
    document.getElementById('deleteModal').style.display = 'flex';
}
function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}
</script>
<div id="deleteModal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
    <div style="background:#fff;padding:2rem 2.5rem;border-radius:1.2rem;max-width:90vw;min-width:320px;text-align:center;box-shadow:0 2px 12px rgba(0,0,0,0.15);margin:auto;">
        <h3 style="margin-bottom:1.2rem;">Confirm Delete</h3>
        <p style="margin-bottom:2rem;">Are you sure you want to delete appointment for <b id="deleteAppointmentName"></b>?</p>
        <button id="deleteConfirmBtn" style="background:#c62828;color:#fff;padding:0.7rem 2rem;border-radius:1rem;font-size:1.05rem;font-weight:500;border:none;margin-right:1rem;">Yes, Delete</button>
        <button onclick="closeDeleteModal()" style="background:#e6eaf3;color:#232a32;padding:0.7rem 2rem;border-radius:1rem;font-size:1.05rem;font-weight:500;border:none;">Cancel</button>
    </div>
</div> 
<!-- Appointment Detail Modal -->
<div id="viewAppointmentModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);z-index:1000;align-items:center;justify-content:center;">
  <div style="background:#fff;padding:2rem 2.5rem;border-radius:1.2rem;max-width:500px;margin:10vh auto;box-shadow:0 2px 12px rgba(0,0,0,0.12);position:relative;">
    <h3 style="margin-bottom:1rem;">Appointment Details</h3>
    <div id="appointmentDetailContent"></div>
    <button type="button" onclick="closeViewAppointmentModal()" style="background:#eee;color:#232a32;padding:0.7rem 1.5rem;border-radius:1rem;font-size:1.05rem;font-weight:500;border:none;margin-top:1rem;">Close</button>
  </div>
</div>
<script>
function closeViewAppointmentModal() {
  document.getElementById('viewAppointmentModal').style.display = 'none';
}
</script>
<!-- Update Cancel/Reschedule Modal forms to include appointment_id as hidden input -->
<form id="cancelForm" method="post" style="display:none;">
  <input type="hidden" name="appointment_id">
  <input type="hidden" name="action" value="cancel">
  <textarea name="cancel_reason" required></textarea>
</form>
<form id="rescheduleForm" method="post" style="display:none;">
  <input type="hidden" name="appointment_id">
  <input type="hidden" name="action" value="reschedule">
  <input type="date" name="new_date" required>
  <input type="time" name="new_time" required>
</form>
<script>
document.querySelectorAll('.btn-view').forEach(btn => {
  btn.addEventListener('click', function() {
    const row = btn.closest('tr');
    let html = '<table style="width:100%;font-size:1.05rem;">';
    row.querySelectorAll('td').forEach((td, i) => {
      if (i < 8) {
        const label = document.querySelector('thead tr').children[i].textContent;
        html += `<tr><td style=\'font-weight:600;padding:0.3rem 0.5rem;\'>${label}</td><td style=\'padding:0.3rem 0.5rem;\'>${td.textContent}</td></tr>`;
      }
    });
    html += '</table>';
    document.getElementById('appointmentDetailContent').innerHTML = html;
    document.getElementById('viewAppointmentModal').style.display = 'flex';
  });
});
document.querySelectorAll('.btn-cancel').forEach(btn => {
  btn.addEventListener('click', function() {
    document.getElementById('cancelReasonModal').style.display = 'block';
    var form = document.querySelector('#cancelReasonModal form');
    form.querySelector('[name=cancel_reason]').value = '';
    form.querySelector('[name=appointment_id]').value = btn.dataset.id;
  });
});
// Reschedule modal logic
let rescheduleTherapistId = null;
document.querySelectorAll('.btn-reschedule').forEach(btn => {
  btn.addEventListener('click', function() {
    document.getElementById('rescheduleModal').style.display = 'block';
    document.querySelector('#rescheduleModal [name=appointment_id]').value = btn.dataset.id;
    rescheduleTherapistId = btn.dataset.therapistId;
    document.getElementById('reschedule-date').value = '';
    document.getElementById('reschedule-time-slots').innerHTML = '';
    document.getElementById('reschedule-selected-time').value = '';
  });
});
  document.getElementById('reschedule-date').addEventListener('change', function() {
    const dateVal = this.value;
    console.log('Reschedule therapist_id:', rescheduleTherapistId, 'date:', dateVal);
    if (!rescheduleTherapistId || !dateVal) {
      alert('Therapist ID not found or date not selected!');
      document.getElementById('reschedule-time-slots').innerHTML = '';
      document.getElementById('reschedule-selected-time').value = '';
      return;
    }
    
    // Check if selected date is in the past
    const today = new Date();
    const selectedDate = new Date(dateVal);
    today.setHours(0, 0, 0, 0); // Reset time to start of day
    selectedDate.setHours(0, 0, 0, 0); // Reset time to start of day
    
    if (selectedDate < today) {
      Swal.fire({
        icon: 'error',
        title: 'Invalid Date',
        text: 'Cannot reschedule to past date. Please select today or a future date.',
        confirmButtonColor: '#9c6b53'
      });
      this.value = ''; // Clear the date input
      document.getElementById('reschedule-time-slots').innerHTML = '';
      document.getElementById('reschedule-selected-time').value = '';
      return;
    }
    
    // Show loading
    document.getElementById('reschedule-time-slots').innerHTML = '<p style="color:#666;text-align:center;padding:1rem;">Loading time slots...</p>';
    document.getElementById('reschedule-selected-time').value = '';
  fetch(`/public/index.php?page=therapist_availability&therapist_id=${rescheduleTherapistId}&date=${dateVal}`)
    .then(res => res.json())
    .then(data => {
      console.log('Availability data received:', data);
      // Working hours (copy from booking.js)
      const workingHours = {
        0: {start: '09:30', end: '19:00'},
        1: {start: '09:30', end: '19:00'},
        2: {start: '12:30', end: '22:00'},
        3: {start: '12:30', end: '22:00'},
        4: {start: '12:30', end: '22:00'},
        5: {start: '09:30', end: '19:00'},
        6: {start: '09:30', end: '19:00'},
      };
      function timeToMinutes(t) {
        const [h, m] = t.split(':');
        return parseInt(h, 10) * 60 + parseInt(m, 10);
      }
      const d = new Date(dateVal);
      const day = d.getDay();
      const wh = workingHours[day];
      let html = '';
      if (!wh) return;
      const start = timeToMinutes(wh.start);
      const end = timeToMinutes(wh.end);
      const times = [];
      
      // Check if selected date is today
      const today = new Date();
      const selectedDate = new Date(dateVal);
      const isToday = today.toDateString() === selectedDate.toDateString();
      
      let adjustedStart = start;
      if (isToday) {
        // If today, only show future time slots
        const currentTime = new Date();
        const currentHour = currentTime.getHours();
        const currentMinute = currentTime.getMinutes();
        
        // Add 1 hour buffer for today's appointments
        const bufferHour = currentHour + 1;
        const bufferTime = `${bufferHour.toString().padStart(2, '0')}:${currentMinute.toString().padStart(2, '0')}`;
        const bufferMinutes = timeToMinutes(bufferTime);
        
        if (bufferMinutes > start) {
          adjustedStart = bufferMinutes;
        }
      }
      
      // Round start time to nearest 30-minute slot
      let roundedStart = Math.ceil(adjustedStart / 30) * 30;
      if (roundedStart < adjustedStart) {
        roundedStart += 30;
      }
      
      // Calculate latest possible start time (end time - 30 min buffer)
      const latestStart = end - 30;
      
      for (let mins = roundedStart; mins <= latestStart; mins += 30) {
        const h = Math.floor(mins / 60).toString().padStart(2, '0');
        const m = (mins % 60).toString().padStart(2, '0');
        times.push(`${h}:${m}`);
      }
      const booked = (data.booked || []).map(t => t.time ? t.time.substring(0,5) : t.substring(0,5));
      console.log('Booked times:', booked);
      console.log('Available times:', times);
      
      if (times.length === 0) {
        document.getElementById('reschedule-time-slots').innerHTML = '<p style="color:#666;text-align:center;padding:1rem;">No available time slots for this date.</p>';
        return;
      }
      
      times.forEach(time => {
        const disabled = booked.includes(time) ? 'disabled' : '';
        html += `<button type='button' class='reschedule-time-btn' ${disabled} style='margin:4px 6px;padding:0.6rem 1.2rem;border-radius:1rem;border:none;font-size:1.05rem;${disabled?'background:#eee;color:#aaa;':'background:#3b5998;color:#fff;cursor:pointer;'}'>${time}</button>`;
      });
      document.getElementById('reschedule-time-slots').innerHTML = html;
      document.querySelectorAll('.reschedule-time-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          document.querySelectorAll('.reschedule-time-btn').forEach(b => b.classList.remove('selected'));
          btn.classList.add('selected');
          document.getElementById('reschedule-selected-time').value = btn.textContent;
          console.log('Selected new time:', btn.textContent);
        });
      });
      document.getElementById('reschedule-selected-time').value = '';
    })
    .catch(error => {
      console.error('Error fetching availability:', error);
      // Fallback: show all time slots as available
      const workingHours = {
        0: {start: '09:30', end: '19:00'},
        1: {start: '09:30', end: '19:00'},
        2: {start: '12:30', end: '22:00'},
        3: {start: '12:30', end: '22:00'},
        4: {start: '12:30', end: '22:00'},
        5: {start: '09:30', end: '19:00'},
        6: {start: '09:30', end: '19:00'},
      };
      function timeToMinutes(t) {
        const [h, m] = t.split(':');
        return parseInt(h, 10) * 60 + parseInt(m, 10);
      }
      const d = new Date(dateVal);
      const day = d.getDay();
      const wh = workingHours[day];
      let html = '';
      if (wh) {
        const start = timeToMinutes(wh.start);
        const end = timeToMinutes(wh.end);
        const times = [];
        
        // Check if selected date is today
        const today = new Date();
        const selectedDate = new Date(dateVal);
        const isToday = today.toDateString() === selectedDate.toDateString();
        
        let adjustedStart = start;
        if (isToday) {
          // If today, only show future time slots
          const currentTime = new Date();
          const currentHour = currentTime.getHours();
          const currentMinute = currentTime.getMinutes();
          
          // Add 1 hour buffer for today's appointments
          const bufferHour = currentHour + 1;
          const bufferTime = `${bufferHour.toString().padStart(2, '0')}:${currentMinute.toString().padStart(2, '0')}`;
          const bufferMinutes = timeToMinutes(bufferTime);
          
          if (bufferMinutes > start) {
            adjustedStart = bufferMinutes;
          }
        }
        
        // Round start time to nearest 30-minute slot
        let roundedStart = Math.ceil(adjustedStart / 30) * 30;
        if (roundedStart < adjustedStart) {
          roundedStart += 30;
        }
        
        // Calculate latest possible start time (end time - 30 min buffer)
        const latestStart = end - 30;
        
        for (let mins = roundedStart; mins <= latestStart; mins += 30) {
          const h = Math.floor(mins / 60).toString().padStart(2, '0');
          const m = (mins % 60).toString().padStart(2, '0');
          times.push(`${h}:${m}`);
        }
        times.forEach(time => {
          html += `<button type='button' class='reschedule-time-btn' style='margin:4px 6px;padding:0.6rem 1.2rem;border-radius:1rem;border:none;font-size:1.05rem;background:#3b5998;color:#fff;cursor:pointer;'>${time}</button>`;
        });
      }
      document.getElementById('reschedule-time-slots').innerHTML = html || '<p style="color:#666;text-align:center;padding:1rem;">No available time slots for this date.</p>';
      document.querySelectorAll('.reschedule-time-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          document.querySelectorAll('.reschedule-time-btn').forEach(b => b.classList.remove('selected'));
          btn.classList.add('selected');
          document.getElementById('reschedule-selected-time').value = btn.textContent;
          console.log('Selected new time:', btn.textContent);
        });
      });
    });
});
</script> 
<style>
.reschedule-time-btn.selected {
  background: #1976d2 !important;
  color: #fff !important;
  border: 2px solid #1976d2 !important;
  font-weight: bold;
  box-shadow: 0 2px 8px rgba(25,118,210,0.08);
}
.btn-action {
  padding: 0.48rem 1.2rem;
  border-radius: 2rem;
  font-weight: 500;
  border: 1.5px solid transparent;
  cursor: pointer;
  font-size: 1.05rem;
  display: inline-block;
  transition: background 0.18s, color 0.18s, border 0.18s, box-shadow 0.18s;
  margin-right: 0.3rem;
  margin-bottom: 0.3rem;
  box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}
.btn-view {
  background: #f3f6fa;
  color: #232a32;
  border-color: #e6eaf3;
}
.btn-view:hover {
  background: #e6eaf3;
  color: #1a237e;
  border-color: #bfc9d8;
}
.btn-cancel {
  background: #fdeaea;
  color: #c62828;
  border-color: #f8bdbd;
}
.btn-cancel:hover {
  background: #f8bdbd;
  color: #fff;
  border-color: #c62828;
}
.btn-reschedule {
  background: #fff9e3;
  color: #b48a00;
  border-color: #ffe9a7;
}
.btn-reschedule:hover {
  background: #ffe9a7;
  color: #7c5a00;
  border-color: #b48a00;
}
</style> 
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