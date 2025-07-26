<?php
session_start();
$name = isset($_SESSION['user']['admin_name']) ? $_SESSION['user']['admin_name'] : 'Admin';
require_once __DIR__ . '/../../models/Therapist.php';
$unapproved_count = Therapist::countUnapproved();
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Notification bell functionality
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

  // Confirmation before submit
  var confirmBtn = document.getElementById('admin-confirm-btn');
  if (confirmBtn) {
    confirmBtn.addEventListener('click', function(e) {
      // Get form values
      var form = confirmBtn.closest('form');
      var name = form.querySelector('input[name="full_name"]').value;
      var emailInput = form.querySelector('input[name="email"]');
      var email = emailInput.value.trim();
      // Validate email format
      if (!/^\S+@\S+\.\S+$/.test(email)) {
        Swal.fire({
          icon: 'error',
          title: 'Invalid Email Address',
          text: 'Please enter a valid email address (e.g. user@email.com).',
          confirmButtonColor: '#9c6b53'
        });
        emailInput.focus();
        return;
      }
      var phoneInput = form.querySelector('input[name="phone"]');
      var phone = phoneInput.value.trim();
      // Validate phone number: only + and digits allowed
      if (!/^\+?\d{9,15}$/.test(phone)) {
        Swal.fire({
          icon: 'error',
          title: 'Invalid Phone Number',
          text: 'Phone number must contain only digits and may start with +. Example: +60123456789',
          confirmButtonColor: '#9c6b53'
        });
        phoneInput.focus();
        return;
      }
      var serviceSelect = form.querySelector('select[name="service"]');
      var service = serviceSelect.options[serviceSelect.selectedIndex].text;
      var therapistSelect = form.querySelector('select[name="therapist"]');
      var therapist = therapistSelect.options[therapistSelect.selectedIndex].text;
      var date = form.querySelector('input[name="date"]').value;
      var time = form.querySelector('input[name="time"]').value;
      
      // Validate date and time
      if (date && time) {
        const selectedDateTime = new Date(date + ' ' + time);
        const now = new Date();
        const bufferTime = new Date(now.getTime() + (60 * 60 * 1000)); // 1 hour buffer
        
        if (selectedDateTime < bufferTime) {
          Swal.fire({
            icon: 'error',
            title: 'Invalid Date/Time',
            text: 'Cannot book appointments for past date/time. Please select a future date and time.',
            confirmButtonColor: '#9c6b53'
          });
          return;
        }
      }
      var addonTherapistSelect = form.querySelector('select[name="addon_therapist"]');
      var addonTherapist = addonTherapistSelect && addonTherapistSelect.selectedIndex > 0 ? addonTherapistSelect.options[addonTherapistSelect.selectedIndex].text : '-';
      var addonServiceSelect = form.querySelector('select[name="addon_service"]');
      var addonService = addonServiceSelect && addonServiceSelect.selectedIndex > 0 ? addonServiceSelect.options[addonServiceSelect.selectedIndex].text : '-';
      var detailsHtml = `
        <div style='text-align:left;font-size:1.05rem;'>
          <b>Name:</b> ${name}<br>
          <b>Email:</b> ${email}<br>
          <b>Phone:</b> ${phone}<br>
          <b>Service:</b> ${service}<br>
          <b>Therapist:</b> ${therapist}<br>
          <b>Date:</b> ${date}<br>
          <b>Time:</b> ${time}<br>
          <b>Add-on Therapist:</b> ${addonTherapist}<br>
          <b>Add-on Service:</b> ${addonService}
        </div>
      `;
      Swal.fire({
        title: 'Save Appointment',
        html: detailsHtml + '<div style="margin-top:1rem;">Are you sure you want to save this appointment?</div>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#9c6b53',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          // Submit form
          form.submit();
        }
      });
    });
  }

  // Error popup after submit
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('error')) {
    Swal.fire({
      icon: 'error',
      title: 'Error!',
      text: decodeURIComponent(urlParams.get('error')),
      confirmButtonColor: '#9c6b53'
    });
  }
});
</script>
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
        <!-- Add Appointment Content -->
        <div style="max-width:700px;margin:2.5rem auto 2rem auto;background:#fff;padding:2.5rem 2rem 2rem 2rem;border-radius:1.2rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
            <a href="/public/index.php?page=admin_appointments" style="display:inline-block;margin-bottom:1.2rem;color:#3b5998;text-decoration:none;font-weight:500;font-size:1.05rem;"><i class="fas fa-arrow-left"></i> Back to All Appointments</a>
            <h2 style="text-align:center;margin-bottom:2rem;">Add Appointment (Admin)</h2>
            <?php if ($error): ?>
                <div style="background:#fdeaea;color:#c62828;padding:1rem 1.5rem;border-radius:10px;margin-bottom:1.2rem;font-size:1.08rem;text-align:center;box-shadow:0 2px 8px rgba(198,40,40,0.07);">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div style="background:#fdeaea;color:#c62828;padding:1rem 1.5rem;border-radius:10px;margin-bottom:1.2rem;font-size:1.08rem;text-align:center;box-shadow:0 2px 8px rgba(198,40,40,0.07);">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>
            <form method="post" style="margin-top:1.5rem;">
                <div style="display:flex;gap:1.5rem;flex-wrap:wrap;">
                    <div style="flex:1 1 100%;min-width:220px;">
                        <label style="font-weight:600;margin-bottom:0.3rem;display:block;">Full Name</label>
                        <input type="text" name="full_name" class="form-control" required placeholder="Customer Name"
                               style="width:100%;padding:12px 14px;margin-bottom:1rem;border-radius:10px;border:1.5px solid #e0cfc2;font-size:1.08rem;">
                    </div>
                    <div style="flex:1 1 48%;min-width:220px;">
                        <label style="font-weight:600;margin-bottom:0.3rem;display:block;">Email Address</label>
                        <input type="email" name="email" class="form-control" required placeholder="customer@email.com"
                               style="width:100%;padding:12px 14px;margin-bottom:1rem;border-radius:10px;border:1.5px solid #e0cfc2;font-size:1.08rem;">
                    </div>
                    <div style="flex:1 1 48%;min-width:220px;">
                        <label style="font-weight:600;margin-bottom:0.3rem;display:block;">Phone Number</label>
                        <input type="text" name="phone" class="form-control" required placeholder="+60 0123456789"
                               style="width:100%;padding:12px 14px;margin-bottom:1rem;border-radius:10px;border:1.5px solid #e0cfc2;font-size:1.08rem;">
                    </div>
                    <div style="flex:1 1 48%;min-width:220px;">
                        <label style="font-weight:600;margin-bottom:0.3rem;display:block;">Select Service</label>
                        <select name="service" class="form-control" required
                                style="width:100%;padding:12px 14px;margin-bottom:1rem;border-radius:10px;border:1.5px solid #e0cfc2;font-size:1.08rem;">
                            <option value="">Select service</option>
                            <?php if (!empty($services)) foreach ($services as $service): ?>
                                <option value="<?= htmlspecialchars($service['service_id']) ?>" data-duration="<?= htmlspecialchars($service['service_duration']) ?>">
                                    <?= htmlspecialchars($service['service_name']) ?><?php if (!empty($service['service_duration'])): ?> (<?= htmlspecialchars($service['service_duration']) ?> minit)<?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="flex:1 1 48%;min-width:220px;">
                        <label style="font-weight:600;margin-bottom:0.3rem;display:block;">Select Therapist</label>
                        <select name="therapist" class="form-control" required
                                style="width:100%;padding:12px 14px;margin-bottom:1rem;border-radius:10px;border:1.5px solid #e0cfc2;font-size:1.08rem;">
                            <option value="">Select therapist</option>
                            <option value="any">Any Therapist</option>
                            <?php if (!empty($therapists)) foreach ($therapists as $therapist): ?>
                                <option value="<?= htmlspecialchars($therapist['therapist_id']) ?>">
                                    <?= htmlspecialchars($therapist['therapist_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Date -->
                    <div style="flex:1 1 48%;min-width:220px;">
                        <label style="font-weight:600;margin-bottom:0.3rem;display:block;">Date</label>
                        <input type="text" name="date" id="admin-date-input" class="form-control" required placeholder="Select date"
                               style="width:100%;padding:12px 14px;margin-bottom:1rem;border-radius:10px;border:1.5px solid #e0cfc2;font-size:1.08rem;">
                    </div>
                    <!-- Time Slot -->
                    <div style="flex:1 1 48%;min-width:220px;">
                        <label style="font-weight:600;margin-bottom:0.3rem;display:block;">Time</label>
                        <input type="hidden" name="time" id="admin-selected-time" required>
                        <div id="admin-time-slots" style="display:flex;flex-wrap:wrap;gap:10px 12px;margin-bottom:1rem;min-height:48px;align-items:center;"></div>
                    </div>
                    <div style="flex:1 1 48%;min-width:220px;">
                        <label style="font-weight:600;margin-bottom:0.3rem;display:block;">Add-on Therapist (Optional)</label>
                        <select name="addon_therapist" class="form-control"
                                style="width:100%;padding:12px 14px;margin-bottom:1rem;border-radius:10px;border:1.5px solid #e0cfc2;font-size:1.08rem;">
                            <option value="">Select add-on therapist</option>
                            <?php if (!empty($therapists)) foreach ($therapists as $therapist): ?>
                                <option value="<?= htmlspecialchars($therapist['therapist_id']) ?>">
                                    <?= htmlspecialchars($therapist['therapist_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="flex:1 1 48%;min-width:220px;">
                        <label style="font-weight:600;margin-bottom:0.3rem;display:block;">Add-on Service (Optional)</label>
                        <select name="addon_service" class="form-control"
                                style="width:100%;padding:12px 14px;margin-bottom:1rem;border-radius:10px;border:1.5px solid #e0cfc2;font-size:1.08rem;">
                            <option value="">Select add-on service</option>
                            <?php if (!empty($services)) foreach ($services as $service): ?>
                                <option value="<?= htmlspecialchars($service['service_id']) ?>">
                                    <?= htmlspecialchars($service['service_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div style="width:100%;text-align:center;">
                    <button type="button" id="admin-confirm-btn" style="background:#9c6b53;color:#fff;padding:1.1rem 0;border:none;border-radius:30px;font-size:1.15rem;font-weight:bold;display:inline-block;width:70%;margin:2.5rem auto 0 auto;box-shadow:0 2px 8px rgba(156,107,83,0.07);transition:background 0.2s;cursor:pointer;">
                        Add Appointment
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
(function() {
  const serviceSelect = document.querySelector('select[name="service"]');
  const therapistSelect = document.querySelector('select[name="therapist"]');
  const dateInput = document.getElementById('admin-date-input');
  const timeSlotsDiv = document.getElementById('admin-time-slots');
  const selectedTimeInput = document.getElementById('admin-selected-time');
  if (!serviceSelect || !therapistSelect || !dateInput || !timeSlotsDiv || !selectedTimeInput) return;

  // Flatpickr
  if (window.flatpickr) {
    flatpickr(dateInput, {
      minDate: 'today',
      dateFormat: 'Y-m-d',
      disableMobile: true,
      onChange: function(selectedDates, dateStr, instance) {
        // Clear time slots when date changes
        timeSlotsDiv.innerHTML = '';
        selectedTimeInput.value = '';
        
        // Check if selected date is today
        const today = new Date();
        const selectedDate = new Date(dateStr);
        const isToday = today.toDateString() === selectedDate.toDateString();
        
        if (isToday) {
          // If today, only show future time slots
          const currentTime = new Date();
          const currentHour = currentTime.getHours();
          const currentMinute = currentTime.getMinutes();
          
          // Add 1 hour buffer for today's appointments
          const bufferHour = currentHour + 1;
          
          // Update working hours for today to start from buffer time
          const day = selectedDate.getDay();
          if (workingHours[day]) {
            const bufferTime = `${bufferHour.toString().padStart(2, '0')}:${currentMinute.toString().padStart(2, '0')}`;
            if (bufferTime > workingHours[day].start) {
              workingHours[day] = { ...workingHours[day], start: bufferTime };
            }
          }
        }
      }
    });
  }

  // Working hours by day (0=Sunday, 1=Monday, ...)
  const workingHours = {
    0: {start: '09:30', end: '19:00'}, // Sunday: 9:30 am–7 pm
    1: {start: '09:30', end: '19:00'}, // Monday: 9:30 am–7 pm
    2: {start: '12:30', end: '22:00'}, // Tuesday: 12:30–10 pm
    3: {start: '12:30', end: '22:00'}, // Wednesday: 12:30–10 pm
    4: {start: '12:30', end: '22:00'}, // Thursday: 12:30–10 pm
    5: {start: '09:30', end: '19:00'}, // Friday: 9:30 am–7 pm
    6: {start: '09:30', end: '19:00'}, // Saturday: 9:30 am–7 pm
  };
  function timeToMinutes(t) {
    const [h, m] = t.split(':');
    return parseInt(h, 10) * 60 + parseInt(m, 10);
  }
  let currentBookedTimes = [];
  function renderTimeSlotsForDay(day) {
    timeSlotsDiv.innerHTML = '';
    if (typeof workingHours[day] === 'undefined') return;
    let duration = 60;
    if (serviceSelect && serviceSelect.selectedOptions.length > 0) {
      const dur = parseInt(serviceSelect.selectedOptions[0].getAttribute('data-duration'));
      if (!isNaN(dur) && dur > 0) duration = dur;
    }
    const start = timeToMinutes(workingHours[day].start);
    const end = timeToMinutes(workingHours[day].end);
    const times = [];
    
    // Round start time to nearest 30-minute slot
    let roundedStart = Math.ceil(start / 30) * 30;
    if (roundedStart < start) {
      roundedStart += 30;
    }
    
    // Calculate latest possible start time
    // Allow appointments that finish at or before end time
    const latestStart = end - 30; // Allow 30-minute buffer before end time
    
    for (let mins = roundedStart; mins <= latestStart; mins += 30) {
      const h = Math.floor(mins / 60).toString().padStart(2, '0');
      const m = (mins % 60).toString().padStart(2, '0');
      times.push(`${h}:${m}`);
    }
    times.forEach(time => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'admin-time-slot-btn';
      btn.textContent = time;
      btn.onclick = function() {
        document.querySelectorAll('.admin-time-slot-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        selectedTimeInput.value = time;
      };
      btn.setAttribute('data-time', time);
      timeSlotsDiv.appendChild(btn);
    });
    selectedTimeInput.value = '';
    updateTimeSlotsAvailability();
  }
  function updateTimeSlotsAvailability() {
    let duration = 60;
    if (serviceSelect && serviceSelect.selectedOptions.length > 0) {
      const dur = parseInt(serviceSelect.selectedOptions[0].getAttribute('data-duration'));
      if (!isNaN(dur) && dur > 0) duration = dur;
    }
    document.querySelectorAll('.admin-time-slot-btn').forEach(btn => {
      const slotTime = btn.textContent;
      let overlaps = false;
      const slotStart = timeToMinutes(slotTime);
      const slotEnd = slotStart + duration;
      for (let i = 0; i < currentBookedTimes.length; i++) {
        const bookedStart = timeToMinutes(currentBookedTimes[i].time);
        const bookedEnd = bookedStart + (currentBookedTimes[i].duration || 60);
        if (slotStart < bookedEnd && slotEnd > bookedStart) {
          overlaps = true;
          break;
        }
      }
      if (overlaps) {
        btn.disabled = true;
        btn.classList.add('disabled');
        if (btn.classList.contains('selected')) {
          btn.classList.remove('selected');
          selectedTimeInput.value = '';
        }
      } else {
        btn.disabled = false;
        btn.classList.remove('disabled');
      }
    });
  }
  function fetchAvailabilityAndUpdate() {
    const therapistId = therapistSelect.value;
    const dateVal = dateInput.value;
    if (!therapistId || !dateVal) {
      currentBookedTimes = [];
      updateTimeSlotsAvailability();
      return;
    }
    fetch(`/public/index.php?page=therapist_availability&therapist_id=${therapistId}&date=${dateVal}`)
      .then(res => res.json())
      .then(data => {
        currentBookedTimes = (data.booked || []).map(b => ({
          time: b.time.substring(0,5),
          duration: parseInt(b.duration) || 60
        }));
        updateTimeSlotsAvailability();
      });
  }
  function triggerSlotRender() {
    const val = dateInput.value;
    if (val) {
      const d = new Date(val);
      renderTimeSlotsForDay(d.getDay());
    }
    fetchAvailabilityAndUpdate();
  }
  if (serviceSelect) serviceSelect.addEventListener('change', triggerSlotRender);
  if (therapistSelect) therapistSelect.addEventListener('change', triggerSlotRender);
  if (dateInput) dateInput.addEventListener('change', triggerSlotRender);
})();
</script>
<style>
.admin-time-slot-btn.selected { background: #9c6b53 !important; color: #fff !important; border: 2px solid #7a4c2e !important; }
.admin-time-slot-btn:disabled { background: #eee !important; color: #aaa !important; border: 1px solid #ccc !important; cursor: not-allowed; }
</style>
