<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$name = isset($_SESSION['user']['therapist_name']) ? $_SESSION['user']['therapist_name'] : 'Therapist';
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
            <a href="/public/index.php?page=therapist_dashboard" style="display:flex;align-items:center;padding:1rem 2rem;color:#fff;text-decoration:none;"><span style="margin-right:10px;font-size:1.2rem;">📅</span>Appointment</a>
            <a href="/public/index.php?page=therapist_profile" style="display:flex;align-items:center;padding:1rem 2rem;color:#fff;text-decoration:none;"><span style="margin-right:10px;font-size:1.2rem;">👤</span>Profile</a>
            <a href="/public/index.php?page=logout" style="display:flex;align-items:center;padding:1rem 2rem;color:#fff;text-decoration:none;"><span style="margin-right:10px;font-size:1.2rem;">🚪</span>Log out</a>
        </nav>
    </aside>
    <!-- Main Content -->
    <main style="flex:1;">
        <div style="max-width:500px;margin:3rem auto;padding:2.5rem 2rem;background:#fff7f3;border-radius:2rem;box-shadow:0 2px 12px rgba(0,0,0,0.07);">
            <h2 style="text-align:center;font-size:2rem;font-weight:bold;color:#9c6b53;margin-bottom:2rem;">Change Password</h2>
            <?php if (!empty($msg)) echo $msg; ?>
            <form method="post">
                <div style="margin-bottom:1.2rem;">
                    <label style="font-weight:500;">Current Password</label>
                    <div style="display:flex;align-items:center;position:relative;">
                        <input type="password" name="current_password" id="current_password" class="form-control" required style="width:100%;padding:0.7rem 2.5rem 0.7rem 1rem;border-radius:0.7rem;border:1.5px solid #e6eaf3;font-size:1.05rem;margin-top:0.3rem;">
                        <span onclick="togglePassword('current_password')" style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);cursor:pointer;font-size:1.2rem;color:#888;">&#128065;</span>
                    </div>
                </div>
                <div style="margin-bottom:1.2rem;">
                    <label style="font-weight:500;">New Password</label>
                    <div style="display:flex;align-items:center;position:relative;">
                        <input type="password" name="new_password" id="new_password" class="form-control" required style="width:100%;padding:0.7rem 2.5rem 0.7rem 1rem;border-radius:0.7rem;border:1.5px solid #e6eaf3;font-size:1.05rem;margin-top:0.3rem;">
                        <span onclick="togglePassword('new_password')" style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);cursor:pointer;font-size:1.2rem;color:#888;">&#128065;</span>
                    </div>
                </div>
                <div style="margin-bottom:1.2rem;">
                    <label style="font-weight:500;">Confirm New Password</label>
                    <div style="display:flex;align-items:center;position:relative;">
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required style="width:100%;padding:0.7rem 2.5rem 0.7rem 1rem;border-radius:0.7rem;border:1.5px solid #e6eaf3;font-size:1.05rem;margin-top:0.3rem;">
                        <span onclick="togglePassword('confirm_password')" style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);cursor:pointer;font-size:1.2rem;color:#888;">&#128065;</span>
                    </div>
                </div>
                <button type="submit" style="background:#9c6b53;color:#fff;padding:0.9rem 2.5rem;border-radius:2rem;font-size:1.1rem;font-weight:bold;border:none;width:100%;margin-top:0.5rem;">Change Password</button>
            </form>
        </div>
    </main>
</div>

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
</script> 