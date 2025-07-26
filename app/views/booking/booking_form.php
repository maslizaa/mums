<!-- Hero Section -->
<div style="background:#fff;padding:3rem 0 2rem 0;text-align:center;border:1.5px solid #eee;border-radius:18px;box-shadow:0 4px 16px rgba(156,107,83,0.07);margin:2rem auto 2.5rem auto;max-width:1100px;">
    <h1 style="font-size:3rem;font-weight:bold;margin-bottom:1rem;">INSPIRASI KECANTIKAN & KESEJAHTERAAN WANITA</h1>
    <p style="font-size:1.2rem;color:#666;margin-bottom:2rem;">
        Experience luxury, comfort, and expert care at MUMS Spa. Your journey to wellness starts here.
    </p>
    <a href="#booking-form" style="background:#9c6b53;color:#fff;padding:1rem 2.5rem;border-radius:30px;font-size:1.1rem;font-weight:bold;text-decoration:none;display:inline-block;">
        Book Appointment
    </a>
</div>

<div style="max-width:1200px;margin:2rem auto;">
    <h2 style="text-align:center;margin-bottom:2rem;">Our Services</h2>
    <div class="scroll-container" style="background:transparent;overflow-x:auto;white-space:nowrap;padding:10px 0 18px 0;">
        <?php if (!empty($services)) foreach ($services as $service): ?>
            <div style="display:inline-block;background:#fff;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.05);padding:2rem;width:260px;min-width:220px;text-align:center;margin-right:18px;vertical-align:top;border:2px solid #e0cfc2;">
                <?php if (!empty($service['service_image'])): ?>
                    <img src="/public/assets/images/<?= htmlspecialchars($service['service_image']) ?>" alt="<?= htmlspecialchars($service['service_name']) ?>" style="width:100px;height:100px;object-fit:cover;border-radius:12px;margin-bottom:1rem;">
                <?php endif; ?>
                <h3><?= htmlspecialchars($service['service_name']) ?></h3>
                <?php if (!empty($service['service_duration'])): ?>
                    <div style="color:#9c6b53;font-size:1.05rem;margin-bottom:0.5rem;">
                        <i class="fa fa-clock"></i> <?= htmlspecialchars($service['service_duration']) ?> minit
                    </div>
                <?php endif; ?>
                <?php if (!empty($service['service_price'])): ?>
                    <div style="font-weight:bold;color:#9c6b53;margin-top:1rem;">
                        RM<?= number_format($service['service_price'], 2) ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="booking-section">
    <h2 style="text-align:center;margin-bottom:2rem;">Book Your Appointment</h2>
    <?php if (isset($success) && $success): ?>
        <!-- Success message div removed, only SweetAlert2 popup is used -->
    <?php endif; ?>
    <?php if (!isset($success) || !$success): ?>
    <form method="post" id="booking-form" action="/public/index.php?page=booking"
      style="max-width:600px;margin:2rem auto;padding:2.5rem 2rem;background:#fff;border-radius:20px;box-shadow:0 4px 16px rgba(156,107,83,0.10);border:2px solid #e0cfc2;">
        <div style="display:flex;flex-wrap:wrap;gap:1.5rem;">
            <div style="flex:1 1 100%;min-width:220px;">
                <label style="font-weight:600;margin-bottom:0.3rem;display:block;">Full Name</label>
                <input type="text" name="full_name" class="form-control" required placeholder="Your Name"
                       style="width:100%;padding:12px 14px;margin-bottom:1rem;border-radius:10px;border:1.5px solid #e0cfc2;font-size:1.08rem;">
            </div>
            <div style="flex:1 1 48%;min-width:220px;">
                <label style="font-weight:600;margin-bottom:0.3rem;display:block;">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="you@email.com"
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
                        <option value="<?= htmlspecialchars($service['service_id']) ?>"
                            data-duration="<?= htmlspecialchars($service['service_duration']) ?>">
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
                            <?= htmlspecialchars($therapist['therapist_name']) ?> (Strength: <?= htmlspecialchars($therapist['therapist_strength']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="flex:1 1 48%;min-width:220px;">
                <label style="font-weight:600;margin-bottom:0.3rem;display:block;">Selected Date</label>
                <input type="text" name="date" class="form-control flatpickr-date" required placeholder="Select date"
                       style="width:100%;padding:12px 14px;margin-bottom:1rem;border-radius:10px;border:1.5px solid #e0cfc2;font-size:1.08rem;">
            </div>
            <div style="flex:1 1 100%;min-width:220px;">
                <label style="font-weight:600;margin-bottom:0.3rem;display:block;">Selected Time</label>
                <input type="hidden" name="time" id="selected-time" required>
                <div id="time-slots" style="display:flex;flex-wrap:wrap;gap:12px 16px;margin-bottom:1rem;"></div>
            </div>
            <div style="flex:1 1 48%;min-width:220px;">
                <label style="font-weight:600;margin-bottom:0.3rem;display:block;">Add-on Therapist (Optional)</label>
                <select name="addon_therapist" class="form-control"
                        style="width:100%;padding:12px 14px;margin-bottom:1rem;border-radius:10px;border:1.5px solid #e0cfc2;font-size:1.08rem;">
                    <option value="">Select add-on therapist</option>
                    <?php if (!empty($therapists)) foreach ($therapists as $therapist): ?>
                        <option value="<?= htmlspecialchars($therapist['therapist_id']) ?>">
                            <?= htmlspecialchars($therapist['therapist_name']) ?> (Strength: <?= htmlspecialchars($therapist['therapist_strength']) ?>)
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
          <button id="book-now-btn" type="submit" style="background:#9c6b53;color:#fff;padding:1.1rem 0;border:none;border-radius:30px;font-size:1.15rem;font-weight:bold;display:inline-block;width:70%;margin:2.5rem auto 0 auto;box-shadow:0 2px 8px rgba(156,107,83,0.07);transition:background 0.2s;cursor:pointer;">
            Book Now
          </button>
        </div>
    </form>
    <?php endif; ?>
    <!-- Confirmation Modal -->
    <div id="booking-confirm-modal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1000;">
      <div class="modal-content" style="background:#fff;padding:2rem;border-radius:16px;max-width:500px;margin:100px auto;box-shadow:0 4px 24px rgba(156,107,83,0.10);">
        <h3>Confirm Your Appointment?</h3>
        <p>Please check your details before confirming your booking:</p>
        <ul id="confirm-details-list" style="text-align:left;margin-bottom:1.2rem;"></ul>
        <button type="button" class="btn-cancel">Cancel</button>
        <button type="button" class="btn-confirm">Confirm</button>
      </div>
    </div>
    <script src="/public/assets/js/booking.js"></script>
</div>

<?php
$appointmentDetails = null;
if (isset($_SESSION['last_appointment'])) {
    $appointmentDetails = $_SESSION['last_appointment'];
    unset($_SESSION['last_appointment']);
}
?>
<?php if (isset($success) && $success && $appointmentDetails): ?>
<script>
window.addEventListener('DOMContentLoaded', function() {
  Swal.fire({
    icon: 'success',
    title: 'Appointment Success!',
    html: `
      <div style="text-align:left;">
        <b>Name:</b> <?= htmlspecialchars($appointmentDetails['name']) ?><br>
        <b>Email:</b> <?= htmlspecialchars($appointmentDetails['email']) ?><br>
        <b>Phone:</b> <?= htmlspecialchars($appointmentDetails['phone']) ?><br>
        <b>Service:</b> <?= htmlspecialchars($services[array_search($appointmentDetails['service'], array_column($services, 'service_id'))]['service_name']) ?><br>
        <b>Therapist:</b> <?= htmlspecialchars($therapists[array_search($appointmentDetails['therapist'], array_column($therapists, 'therapist_id'))]['therapist_name']) ?><br>
        <b>Date:</b> <?= htmlspecialchars($appointmentDetails['date']) ?><br>
        <b>Time:</b> <?= htmlspecialchars($appointmentDetails['time']) ?>
      </div>
      <div style="margin-top:1rem;font-weight:bold;">Please state <span style="color:#1976d2">"<?= htmlspecialchars($appointmentDetails['name']) ?>"</span> on the appointment.</div>
    `,
    confirmButtonText: 'OK'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = '/public/index.php';
    }
  });
});
</script>
<?php endif; ?>

<style>
.addon-pill-group label {
  display: inline-block;
  margin: 4px 8px 4px 0;
}
.addon-pill {
  display: inline-block;
  padding: 8px 18px;
  border-radius: 20px;
  border: 2px solid #e0cfc2;
  background: #faf8f6;
  color: #9c6b53;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.2s, color 0.2s, border 0.2s;
}
.addon-checkbox:checked + .addon-pill {
  background: #9c6b53;
  color: #fff;
  border: 2px solid #9c6b53;
}
.addon-checkbox {
  display: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Auto-select service if service_id is in URL
  const urlParams = new URLSearchParams(window.location.search);
  const serviceId = urlParams.get('service_id');
  if (serviceId) {
    const serviceSelect = document.querySelector('select[name="service"]');
    if (serviceSelect) {
      serviceSelect.value = serviceId;
      // Trigger change event if needed
      serviceSelect.dispatchEvent(new Event('change'));
    }
  }

  // Phone number validation
  const bookingForm = document.getElementById('booking-form');
  if (bookingForm) {
    bookingForm.addEventListener('submit', function(e) {
      const phoneInput = bookingForm.querySelector('input[name="phone"]');
      if (phoneInput && !/^\d+$/.test(phoneInput.value)) {
        Swal.fire({
          icon: 'error',
          title: 'Ralat',
          text: 'Nombor telefon mesti mengandungi nombor sahaja.'
        });
        phoneInput.focus();
        e.preventDefault();
      }
    });
  }
});
</script>
