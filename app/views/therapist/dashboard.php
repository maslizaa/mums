<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$name = isset($_SESSION['user']['therapist_name']) ? $_SESSION['user']['therapist_name'] : 'Therapist';
$status = isset($status) ? $status : 'all';
$status_options = [
    'all' => 'All',
    'today' => 'Today',
    'booked' => 'Booked',
    'completed' => 'Completed',
    'cancelled' => 'Cancelled',
    'rescheduled' => 'Rescheduled',
];
?>
<div style="display:flex;min-height:100vh;background:#f5f5f5;">
    <!-- Sidebar -->
    <aside style="width:220px;background:#9c6b53;color:#fff;display:flex;flex-direction:column;align-items:flex-start;padding:2rem 0 0 0;min-height:100vh;">
        <div style="width:100%;text-align:center;margin-bottom:2.5rem;">
  <div style="
    font-family: 'Georgia', 'Times New Roman', serif;
    font-size: 1.3rem;
    font-weight: bold;
    color: #fff;
    background: #8d624a;
    border-radius: 12px;
    padding: 18px 0 8px 0;
    letter-spacing: 1.5px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin: 0 auto 1rem auto;
    width: 90%;
    ">
    Spa Makcik Urut <br>
    <span style="font-size:1.05rem;font-weight:normal;letter-spacing:2px;color:#ffd9b3;">Muslimah</span>
  </div>
</div>
        <nav style="width:100%;">
            <a href="/public/index.php?page=therapist_dashboard" style="display:flex;align-items:center;padding:1rem 2rem;color:#ffd966;font-weight:bold;background:#9c6b53;text-decoration:none;"><span style="margin-right:10px;font-size:1.2rem;">📅</span>Appointment</a>
            <a href="/public/index.php?page=therapist_profile" style="display:flex;align-items:center;padding:1rem 2rem;color:#fff;text-decoration:none;"><span style="margin-right:10px;font-size:1.2rem;">👤</span>Profile</a>
            <a href="/public/index.php?page=logout" style="display:flex;align-items:center;padding:1rem 2rem;color:#fff;text-decoration:none;"><span style="margin-right:10px;font-size:1.2rem;">🚪</span>Log out</a>
        </nav>
    </aside>
    <!-- Main Content -->
    <main style="flex:1;">
        <div style="max-width:1100px;margin:2.5rem auto;padding:2.5rem 2rem;background:#fff7f3;border-radius:2rem;box-shadow:0 2px 12px rgba(0,0,0,0.07);">
            <h1 style="text-align:center;font-size:2.5rem;font-weight:bold;color:#9c6b53;margin-bottom:1.2rem;">Therapist Dashboard</h1>
            <div style="text-align:center;margin-bottom:2.5rem;">
                <span style="display:inline-block;background:#fff;border-radius:50%;padding:1.2rem;margin-bottom:0.7rem;border:2px solid #e5cfc2;">
                    <img src="<?= isset($_SESSION['user']['therapist_photo']) && $_SESSION['user']['therapist_photo'] ? htmlspecialchars($_SESSION['user']['therapist_photo']) : '/public/assets/images/profile.png' ?>" alt="Profile" style="height:64px;width:64px;border-radius:50%;object-fit:cover;">
                </span>
                <div style="font-size:1.4rem;font-weight:bold;color:#9c6b53;">Welcome, <?= htmlspecialchars($name) ?>!</div>
            </div>
            <div style="font-size:1.2rem;font-weight:bold;color:#9c6b53;margin-bottom:1.2rem;display:flex;align-items:center;justify-content:space-between;gap:1.5rem;">
                <span>Your Appointments</span>
                <form method="get" action="" style="margin:0;">
                    <input type="hidden" name="page" value="therapist_dashboard">
                    <label for="status" style="font-weight:normal;font-size:1rem;margin-right:0.5rem;">Status:</label>
                    <select name="status" id="status" onchange="this.form.submit()" style="padding:0.4rem 1rem;border-radius:8px;border:1.5px solid #e0cfc2;font-size:1rem;">
                        <?php foreach ($status_options as $key => $label): ?>
                            <option value="<?= $key ?>" <?= $status === $key ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:1.1rem;">
                    <thead>
                        <tr style="background:#9c6b53;color:#fff;">
                            <th style="padding:0.9rem 0.5rem;">No</th>
                            <th style="padding:0.9rem 0.5rem;">Appointment #</th>
                            <th style="padding:0.9rem 0.5rem;">Customer Name</th>
                            <th style="padding:0.9rem 0.5rem;">Service</th>
                            <th style="padding:0.9rem 0.5rem;">Date</th>
                            <th style="padding:0.9rem 0.5rem;">Time</th>
                            <th style="padding:0.9rem 0.5rem;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($appointments)): ?>
                            <?php foreach ($appointments as $i => $a): ?>
                                <tr style="background:#fbeee6;">
                                    <td style="padding:0.8rem 0.5rem;"> <?= $i+1 ?> </td>
                                    <td style="padding:0.8rem 0.5rem;">APT<?= str_pad($a['appointment_id'], 10, '0', STR_PAD_LEFT) ?></td>
                                    <td style="padding:0.8rem 0.5rem;"> <?= htmlspecialchars($a['customer_name']) ?> </td>
                                    <td style="padding:0.8rem 0.5rem;"> <?= htmlspecialchars($a['service_name'] ?? '-') ?> </td>
                                    <td style="padding:0.8rem 0.5rem;"> <?= htmlspecialchars($a['date']) ?> </td>
                                    <td style="padding:0.8rem 0.5rem;"> <?= htmlspecialchars($a['time']) ?> </td>
                                    <td style="padding:0.8rem 0.5rem;"><span style="background:#e6fbe6;color:#2e7d32;padding:0.4rem 1.2rem;border-radius:1.2rem;font-weight:bold;"> <?= htmlspecialchars(ucfirst($a['status'])) ?> </span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" style="text-align:center;padding:1.5rem;">No appointments found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div> 