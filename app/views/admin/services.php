<?php
session_start();
$name = isset($_SESSION['user']['admin_name']) ? $_SESSION['user']['admin_name'] : 'Admin';
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
            <a href="/public/index.php?page=admin_services" style="display:flex;align-items:center;padding:0.9rem 2rem;color:#fff;text-decoration:none;font-size:1.08rem;transition:background 0.2s, border-left 0.2s;font-weight:500;border-left:4px solid #3b5998;background:#232a32;">
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
         <!-- Services Content -->
        <div style="max-width:1100px;margin:2.5rem auto;padding:2.5rem 2rem;background:#fff;border-radius:1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.07);">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;">
                <h2 style="font-size:1.6rem;font-weight:600;color:#232a32;letter-spacing:0.5px;">Manage Services</h2>
                <a href="/public/index.php?page=admin_add_service" style="background:#3b5998;color:#fff;padding:0.8rem 2rem;border-radius:1.2rem;font-size:1.08rem;font-weight:500;text-decoration:none;display:flex;align-items:center;gap:0.7rem;"><i class="fas fa-plus"></i> Add Service</a>
            </div>
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:1.08rem;">
                    <thead>
                        <tr style="background:#e6eaf3;color:#232a32;">
                            <th style="padding:1rem 0.5rem;">No</th>
                            <th style="padding:1rem 0.5rem;">Service Name</th>
                            <th style="padding:1rem 0.5rem;">Description</th>
                            <th style="padding:1rem 0.5rem;">Duration (min)</th>
                            <th style="padding:1rem 0.5rem;">Price (RM)</th>
                            <th style="padding:1rem 0.5rem;">Status</th>
                            <th style="padding:1rem 0.5rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
<?php if (!empty($services)): ?>
    <?php foreach (
        $services as $i => $service): ?>
        <tr style="background:#f8fafd;">
            <td style="padding:0.9rem 0.5rem;"> <?= $i+1 ?> </td>
            <td style="padding:0.9rem 0.5rem;"> <?= htmlspecialchars($service['service_name']) ?> </td>
            <td style="padding:0.9rem 0.5rem;"> <?= htmlspecialchars($service['service_description']) ?> </td>
            <td style="padding:0.9rem 0.5rem;"> <?= htmlspecialchars($service['service_duration']) ?> </td>
            <td style="padding:0.9rem 0.5rem;"> <?= number_format($service['service_price'], 2) ?> </td>
            <td style="padding:0.9rem 0.5rem;">
                <?php if ($service['is_active']): ?>
                    <span style="background:#e6fbe6;color:#2e7d32;padding:0.4rem 1.2rem;border-radius:1.2rem;font-weight:500;">Active</span>
                <?php else: ?>
                    <span style="background:#fdeaea;color:#c62828;padding:0.4rem 1.2rem;border-radius:1.2rem;font-weight:500;">Inactive</span>
                <?php endif; ?>
            </td>
            <td style="padding:0.9rem 0.5rem;">
                <a href="/public/index.php?page=admin_edit_service&id=<?= $service['service_id'] ?>" style="background:#ffd966;color:#232a32;padding:0.4rem 1.1rem;border-radius:1rem;font-weight:500;text-decoration:none;display:inline-block;margin-bottom:0.3rem;margin-right:0.3rem;"><i class="fas fa-edit"></i> Edit</a>
                <form method="post" action="/public/index.php?page=admin_services&toggle_id=<?= $service['service_id'] ?>" style="display:inline;">
                    <button type="submit" style="background:<?= $service['is_active'] ? '#fdeaea' : '#e6fbe6' ?>;color:<?= $service['is_active'] ? '#c62828' : '#2e7d32' ?>;padding:0.4rem 1.1rem;border-radius:1rem;font-weight:500;border:none;cursor:pointer;margin-bottom:0.3rem;">
                        <?= $service['is_active'] ? 'Deactivate' : 'Activate' ?>
                    </button>
                </form>
                <a href="#" onclick="confirmDelete(<?= $service['service_id'] ?>, '<?= htmlspecialchars(addslashes($service['service_name'])) ?>');return false;" style="background:#fdeaea;color:#c62828;padding:0.4rem 1.1rem;border-radius:1rem;font-weight:500;text-decoration:none;display:inline-block;margin-bottom:0.3rem;"><i class="fas fa-trash"></i> Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
        <tr><td colspan="7" style="text-align:center;padding:1.5rem;">No services found.</td></tr>
<?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script>
function confirmDelete(serviceId, serviceName) {
    document.getElementById('deleteServiceName').textContent = serviceName;
    document.getElementById('deleteConfirmBtn').onclick = function() {
        window.location.href = '/public/index.php?page=admin_services&delete_id=' + serviceId;
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
        <p style="margin-bottom:2rem;">Are you sure you want to delete service <b id="deleteServiceName"></b>?</p>
        <button id="deleteConfirmBtn" style="background:#c62828;color:#fff;padding:0.7rem 2rem;border-radius:1rem;font-size:1.05rem;font-weight:500;border:none;margin-right:1rem;">Yes, Delete</button>
        <button onclick="closeDeleteModal()" style="background:#e6eaf3;color:#232a32;padding:0.7rem 2rem;border-radius:1rem;font-size:1.05rem;font-weight:500;border:none;">Cancel</button>
    </div>
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