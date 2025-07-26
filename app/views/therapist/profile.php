<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$therapist = $_SESSION['user'] ?? [
    'therapist_name' => 'Therapist Name',
    'therapist_email' => 'therapist@staff.mums.com',
    'therapist_phone_number' => '0123456789',
    'therapist_photo' => null
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
<div style="flex:1;max-width:700px;margin:3rem auto;padding:2.5rem 2rem;background:#fff7f3;border-radius:2rem;box-shadow:0 2px 12px rgba(0,0,0,0.07);">
    <?php if (!empty($msg)) echo $msg; ?>
    <div style="text-align:center;margin-bottom:2rem;">
        <form method="post" enctype="multipart/form-data" id="profileImageForm" style="display:inline-block;position:relative;">
            <input type="file" name="profile_image" id="profileImageInput" accept="image/*" style="display:none;" onchange="document.getElementById('profileImageForm').submit();">
            <label for="profileImageInput" style="cursor:pointer;display:inline-block;position:relative;">
                <img src="<?= $therapist['therapist_photo'] ? htmlspecialchars($therapist['therapist_photo']) : '/public/assets/images/profile.png' ?>" 
                     alt="Profile" 
                     style="height:90px;width:90px;border-radius:50%;object-fit:cover;border:3px solid   #e5cfc2;background:#fff;">
                <!-- Camera icon overlay -->
                <span style="position:absolute;bottom:-8px;right:-8px;">
                    <img src="/public/assets/images/camera.png" alt="Change" style="height:40px;width:40px;">
                </span>
            </label>
        </form>
        <h2 style="margin:1.2rem 0 0.5rem 0;font-size:2rem;font-weight:bold;color:#9c6b53;">Profile</h2>
    </div>
    <div style="margin-bottom:1.2rem;">
        
        <form method="post" action="" style="margin-bottom:2rem;">
            <div style="margin-bottom:1.2rem;">
                <div style="font-weight:bold;color:#9c6b53;">Name</div>
                <input type="text" name="therapist_name" value="<?= htmlspecialchars($_POST['therapist_name'] ?? $therapist['therapist_name']) ?>" style="background:#fff;padding:0.7rem 1rem;border-radius:1rem;border:1.5px solid #f3e3d6;width:100%;">
            </div>
            <div style="margin-bottom:1.2rem;">
                <div style="font-weight:bold;color:#9c6b53;">Email</div>
                <input type="email" name="therapist_email" value="<?= htmlspecialchars($therapist['therapist_email']) ?>" style="background:#fff;padding:0.7rem 1rem;border-radius:1rem;border:1.5px solid #f3e3d6;width:100%;" readonly>
            </div>
            <div style="margin-bottom:2rem;">
                <div style="font-weight:bold;color:#9c6b53;">Phone</div>
                <input type="text" name="therapist_phone_number" value="<?= htmlspecialchars($_POST['therapist_phone_number'] ?? $therapist['therapist_phone_number']) ?>" style="background:#fff;padding:0.7rem 1rem;border-radius:1rem;border:1.5px solid #f3e3d6;width:100%;">
            </div>
            <div style="text-align:center;">
                <button type="submit" name="edit_profile" style="background:#9c6b53;color:#fff;padding:0.9rem 2.5rem;border-radius:2rem;font-size:1.1rem;font-weight:bold;border:none;">Save Changes</button>
            </div>
        </form>
    </div>
   
    <div style="text-align:center;">
        <a href="/public/index.php?page=therapist_change_password" style="background:#9c6b53;color:#fff;padding:0.9rem 2.5rem;border-radius:2rem;font-size:1.1rem;font-weight:bold;text-decoration:none;">Change Password</a>
    </div>
</div> 
</div>