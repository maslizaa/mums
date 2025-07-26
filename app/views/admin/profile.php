<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$name = isset($_SESSION['user']['admin_name']) ? $_SESSION['user']['admin_name'] : 'Admin';
require_once __DIR__ . '/../../models/Therapist.php';
$unapproved_count = Therapist::countUnapproved();
$admin = $_SESSION['user'] ?? [
    'admin_name' => 'Admin Name',
    'admin_email' => 'admin@admin.mums.com',
    'admin_phone_number' => '0123456789',
    'admin_photo' => null
];
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
        <!-- Profile Content -->
        <div style="max-width:700px;margin:2.5rem auto;padding:2.5rem 2rem;background:#fff;border-radius:2rem;box-shadow:0 2px 12px rgba(0,0,0,0.07);">
            <?php if (!empty($msg)) echo $msg; ?>
            <div style="text-align:center;margin-bottom:2rem;">
                <form method="post" enctype="multipart/form-data" id="profileImageForm" style="display:inline-block;position:relative;">
                    <input type="file" name="profile_image" id="profileImageInput" accept="image/*" style="display:none;" onchange="document.getElementById('profileImageForm').submit();">
                    <label for="profileImageInput" style="cursor:pointer;display:inline-block;position:relative;">
                        <img src="<?= $admin['admin_photo'] ? htmlspecialchars($admin['admin_photo']) : '/public/assets/images/profile.png' ?>"
                             alt="Profile"
                             style="height:90px;width:90px;border-radius:50%;object-fit:cover;border:3px solid #e6eaf3;background:#fff;">
                        <span style="position:absolute;bottom:-8px;right:-8px;">
                            <img src="/public/assets/images/camera.png" alt="Change" style="height:40px;width:40px;">
                        </span>
                    </label>
                </form>
                <h2 style="margin:1.2rem 0 0.5rem 0;font-size:2rem;font-weight:bold;color:#232a32;">Profile</h2>
            </div>
            <form method="post" action="" style="margin-bottom:2rem;">
                <div style="margin-bottom:1.2rem;">
                    <div style="font-weight:bold;color:#232a32;">Name</div>
                    <input type="text" name="admin_name" value="<?= htmlspecialchars($admin['admin_name']) ?>" style="background:#fff;padding:0.7rem 1rem;border-radius:1rem;border:1.5px solid #e6eaf3;width:100%;">
                </div>
                <div style="margin-bottom:1.2rem;">
                    <div style="font-weight:bold;color:#232a32;">Email</div>
                    <input type="email" name="admin_email" value="<?= htmlspecialchars($admin['admin_email']) ?>" style="background:#fff;padding:0.7rem 1rem;border-radius:1rem;border:1.5px solid #e6eaf3;width:100%;" readonly>
                </div>
                <div style="margin-bottom:2rem;">
                    <div style="font-weight:bold;color:#232a32;">Phone</div>
                    <input type="text" name="admin_phone_number" value="<?= htmlspecialchars($admin['admin_phone_number']) ?>" style="background:#fff;padding:0.7rem 1rem;border-radius:1rem;border:1.5px solid #e6eaf3;width:100%;">
                </div>
                <div style="text-align:center;">
                    <button type="submit" name="edit_profile" style="background:#232a32;color:#fff;padding:0.9rem 2.5rem;border-radius:2rem;font-size:1.1rem;font-weight:bold;border:none;">Save Changes</button>
                </div>
            </form>
            <form method="post" action="" style="margin-bottom:2rem;">
                <h3 style="font-size:1.1rem;font-weight:600;color:#232a32;margin-bottom:1rem;">Change Password</h3>
                <div style="margin-bottom:1rem;">
                    <div style="display:flex;align-items:center;position:relative;">
                        <input type="password" name="current_password" id="current_password" placeholder="Current Password" style="width:100%;padding:0.7rem 2.5rem 0.7rem 1rem;border-radius:1rem;border:1.5px solid #e6eaf3;">
                        <span onclick="togglePassword('current_password')" style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);cursor:pointer;font-size:1.2rem;color:#888;">&#128065;</span>
                    </div>
                </div>
                <div style="margin-bottom:1rem;">
                    <div style="display:flex;align-items:center;position:relative;">
                        <input type="password" name="new_password" id="new_password" placeholder="New Password" style="width:100%;padding:0.7rem 2.5rem 0.7rem 1rem;border-radius:1rem;border:1.5px solid #e6eaf3;">
                        <span onclick="togglePassword('new_password')" style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);cursor:pointer;font-size:1.2rem;color:#888;">&#128065;</span>
                    </div>
                </div>
                <div style="margin-bottom:1.5rem;">
                    <div style="display:flex;align-items:center;position:relative;">
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm New Password" style="width:100%;padding:0.7rem 2.5rem 0.7rem 1rem;border-radius:1rem;border:1.5px solid #e6eaf3;">
                        <span onclick="togglePassword('confirm_password')" style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);cursor:pointer;font-size:1.2rem;color:#888;">&#128065;</span>
                    </div>
                </div>
                <div style="text-align:center;">
                    <button type="submit" name="change_password" style="background:#3b5998;color:#fff;padding:0.8rem 2.5rem;border-radius:2rem;font-size:1.08rem;font-weight:500;border:none;">Change Password</button>
                </div>
            </form>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const eyeIcon = event.target;
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = '&#128065;&#8205;&#127787;'; // Eye with slash
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = '&#128065;'; // Eye
    }
}

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