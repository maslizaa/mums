console.log('booking.js loaded');
window.addEventListener('DOMContentLoaded', function() {
  const bookingForm = document.getElementById('booking-form');
  if (bookingForm) {
    console.log('Booking form found');
    bookingForm.addEventListener('submit', function(e) {
      console.log('Booking form submit event triggered');
    });
  } else {
    console.log('Booking form NOT found');
  }
});

// Initialize Flatpickr for the booking form date input
window.addEventListener('DOMContentLoaded', function() {
  if (window.flatpickr) {
    flatpickr('input[name="date"]', {
      minDate: 'today',
      dateFormat: 'Y-m-d',
      disableMobile: true,
      onChange: function(selectedDates, dateStr, instance) {
        // Clear time slots when date changes
        const timeSlotsContainer = document.getElementById('time-slots');
        const selectedTimeInput = document.getElementById('selected-time');
        if (timeSlotsContainer) timeSlotsContainer.innerHTML = '';
        if (selectedTimeInput) selectedTimeInput.value = '';
        
        // Check if selected date is today and adjust working hours if needed
        if (dateStr === new Date().toISOString().split('T')[0]) {
          const currentTime = new Date();
          const currentHour = currentTime.getHours();
          const currentMinute = currentTime.getMinutes();
          const bufferHour = currentHour + 1;
          
          // This will be handled in renderTimeSlotsForDay function
          console.log('Today selected, will apply buffer time');
        }
      }
    });
  }

  // Working hours by day (0=Sunday, 1=Monday, ...)
  const workingHours = {
    0: {start: '09:30', end: '19:00'}, // Sunday
    1: {start: '09:30', end: '19:00'}, // Monday
    2: {start: '12:30', end: '22:00'}, // Tuesday
    3: {start: '12:30', end: '22:00'}, // Wednesday
    4: {start: '12:30', end: '22:00'}, // Thursday
    5: {start: '09:30', end: '19:00'}, // Friday
    6: {start: '09:30', end: '19:00'}, // Saturday
  };

  function timeToMinutes(t) {
    const [h, m] = t.split(':');
    return parseInt(h, 10) * 60 + parseInt(m, 10);
  }

  function renderTimeSlotsForDay(day) {
    const container = document.getElementById('time-slots');
    const hiddenInput = document.getElementById('selected-time');
    if (!container || !hiddenInput) return;
    container.innerHTML = '';
    if (typeof workingHours[day] === 'undefined') return;
    
    // Get selected service duration (default 60 min if not found)
    const serviceSelect = document.querySelector('select[name="service"]');
    let duration = 60;
    if (serviceSelect && serviceSelect.selectedOptions.length > 0) {
      const dur = parseInt(serviceSelect.selectedOptions[0].getAttribute('data-duration'));
      if (!isNaN(dur) && dur > 0) duration = dur;
    }
    
    const start = timeToMinutes(workingHours[day].start);
    const end = timeToMinutes(workingHours[day].end);
    
    // Check if selected date is today
    const dateInput = document.querySelector('input[name="date"]');
    const isToday = dateInput && dateInput.value === new Date().toISOString().split('T')[0];
    
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
    
    const times = [];
    for (let mins = roundedStart; mins <= latestStart; mins += 30) {
      const h = Math.floor(mins / 60).toString().padStart(2, '0');
      const m = (mins % 60).toString().padStart(2, '0');
      times.push(`${h}:${m}`);
    }
    
    // For availability, we need to block slots that would overlap with existing bookings (considering duration)
    // We'll update this in updateTimeSlotsAvailability
    times.forEach(time => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'time-slot-btn';
      btn.textContent = time;
      btn.onclick = function() {
        document.querySelectorAll('.time-slot-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        hiddenInput.value = time;
      };
      btn.setAttribute('data-time', time);
      container.appendChild(btn);
    });
    hiddenInput.value = '';
  }

  // Listen to date change
  const dateInput = document.querySelector('input[name="date"]');
  if (dateInput) {
    dateInput.addEventListener('change', function() {
      const val = this.value;
      if (!val) return renderTimeSlotsForDay(0);
      const d = new Date(val);
      renderTimeSlotsForDay(d.getDay());
    });
    // Initial render for today
    if (dateInput.value) {
      const d = new Date(dateInput.value);
      renderTimeSlotsForDay(d.getDay());
    } else {
      renderTimeSlotsForDay(new Date().getDay());
    }
  }

  // --- AVAILABILITY LOGIC ---
  const therapistSelect = document.querySelector('select[name="therapist"]');
  let currentBookedTimes = [];

  function updateTimeSlotsAvailability() {
    // Get selected service duration (default 60 min if not found)
    const serviceSelect = document.querySelector('select[name="service"]');
    let duration = 60;
    if (serviceSelect && serviceSelect.selectedOptions.length > 0) {
      const dur = parseInt(serviceSelect.selectedOptions[0].getAttribute('data-duration'));
      if (!isNaN(dur) && dur > 0) duration = dur;
    }
    // Disable time slot buttons that are booked or overlap with booked slots
    document.querySelectorAll('.time-slot-btn').forEach(btn => {
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
          document.getElementById('selected-time').value = '';
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
        // Now data.booked is an array of {time, duration}
        currentBookedTimes = (data.booked || []).map(b => ({
          time: b.time.substring(0,5),
          duration: parseInt(b.duration) || 60
        }));
        updateTimeSlotsAvailability();
      });
  }

  if (therapistSelect && dateInput) {
    therapistSelect.addEventListener('change', function() {
      // re-render time slots for new therapist
      const val = dateInput.value;
      if (val) {
        const d = new Date(val);
        renderTimeSlotsForDay(d.getDay());
      }
      fetchAvailabilityAndUpdate();
    });
    dateInput.addEventListener('change', function() {
      const val = this.value;
      if (val) {
        const d = new Date(val);
        renderTimeSlotsForDay(d.getDay());
      }
      fetchAvailabilityAndUpdate();
    });
    // Listen for service change to re-render time slots and update availability
    const serviceSelect = document.querySelector('select[name="service"]');
    if (serviceSelect) {
      serviceSelect.addEventListener('change', function() {
        const val = dateInput.value;
        if (val) {
          const d = new Date(val);
          renderTimeSlotsForDay(d.getDay());
        }
        fetchAvailabilityAndUpdate();
      });
    }
    // Initial fetch
    fetchAvailabilityAndUpdate();
  }

  // After rendering time slots, always update availability
  const origRenderTimeSlotsForDay = renderTimeSlotsForDay;
  renderTimeSlotsForDay = function(day) {
    origRenderTimeSlotsForDay(day);
    updateTimeSlotsAvailability();
  };

  // Confirmation modal logic
  const bookingForm = document.getElementById('booking-form');
  function getSelectedText(select) {
    if (!select) return '';
    return select.options[select.selectedIndex] ? select.options[select.selectedIndex].text : '';
  }
  if (bookingForm) {
    // Normalize phone number to local format
    function normalizePhoneNumber(input) {
      let phone = input.trim();
      if (phone.startsWith('+60')) {
        phone = '0' + phone.slice(3);
      } else if (phone.startsWith('60')) {
        phone = '0' + phone.slice(2);
      }
      return phone;
    }
    bookingForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Validate date and time - prevent past appointments
      const dateInput = bookingForm.date;
      const timeInput = bookingForm.time;
      
      if (dateInput && timeInput && dateInput.value && timeInput.value) {
        const selectedDateTime = new Date(dateInput.value + ' ' + timeInput.value);
        const currentDateTime = new Date();
        const bufferTime = new Date(currentDateTime.getTime() + (60 * 60 * 1000)); // 1 hour buffer
        
        if (selectedDateTime < bufferTime) {
          Swal.fire({
            icon: 'error',
            title: 'Invalid Date/Time',
            text: 'Cannot book appointments for past date/time. Please select a future date and time.'
          });
          return;
        }
        
        // Check if date is not in the past
        const today = new Date().toISOString().split('T')[0];
        if (dateInput.value < today) {
          Swal.fire({
            icon: 'error',
            title: 'Invalid Date',
            text: 'Cannot book appointments for past dates.'
          });
          return;
        }
        
        // Check if time is valid for today
        if (dateInput.value === today) {
          const currentTime = new Date();
          const currentHour = currentTime.getHours();
          const currentMinute = currentTime.getMinutes();
          const bufferHour = currentHour + 1;
          const bufferTimeStr = `${bufferHour.toString().padStart(2, '0')}:${currentMinute.toString().padStart(2, '0')}`;
          
          if (timeInput.value <= bufferTimeStr) {
            Swal.fire({
              icon: 'error',
              title: 'Invalid Time',
              text: 'Cannot book appointments within 1 hour from now. Please select a later time.'
            });
            return;
          }
        }
      }
      
      // Validate full name
      const nameInput = bookingForm.full_name;
      if (nameInput && !nameInput.value.trim()) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Please enter your name.'
        });
        nameInput.focus();
        return;
      }
      // Only allow letters, spaces, apostrophe, hyphen
      if (nameInput && !/^[A-Za-z\s'\-]+$/.test(nameInput.value.trim())) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Name must contain only letters.'
        });
        nameInput.focus();
        return;
      }
      // Validate and normalize phone number
      const phoneInput = bookingForm.phone;
      let normalizedPhone = '';
      if (phoneInput) {
        normalizedPhone = normalizePhoneNumber(phoneInput.value);
        phoneInput.value = normalizedPhone; // update input for submission
      }
      if (!/^0\d{9,10}$/.test(normalizedPhone)) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Please enter a valid phone number.'
        });
        phoneInput.focus();
        return;
      }
      // Collect details
      const name = bookingForm.full_name.value;
      const email = bookingForm.email.value;
      const service = getSelectedText(bookingForm.service);
      const therapist = getSelectedText(bookingForm.therapist);
      const date = bookingForm.date.value;
      const time = bookingForm.time.value;
      // Add-on therapist
      const addonTherapistSelect = bookingForm.querySelector('select[name="addon_therapist[]"]');
      let addonTherapist = [];
      if (addonTherapistSelect) {
        addonTherapist = Array.from(addonTherapistSelect.selectedOptions).map(opt => opt.text);
      }
      // Add-on service
      const addonServiceSelect = bookingForm.querySelector('select[name="addon_service[]"]');
      let addonService = [];
      if (addonServiceSelect) {
        addonService = Array.from(addonServiceSelect.selectedOptions).map(opt => opt.text);
      }
      Swal.fire({
        icon: 'question',
        title: 'Confirm Your Appointment?',
        html: `
          <div style="text-align:left;">
            <b>Name:</b> ${name}<br>
            <b>Email:</b> ${email}<br>
            <b>Phone:</b> ${normalizedPhone}<br>
            <b>Service:</b> ${service}<br>
            <b>Therapist:</b> ${therapist}<br>
            <b>Date:</b> ${date}<br>
            <b>Time:</b> ${time}<br>
            ${addonTherapist.length ? `<b>Add-on Therapist:</b> ${addonTherapist.join(', ')}<br>` : ''}
            ${addonService.length ? `<b>Add-on Service:</b> ${addonService.join(', ')}<br>` : ''}
          </div>
          <div style=\"margin-top:1rem;\">Please check the details before confirming the appointment.</div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          bookingForm.submit();
        }
        // If cancel, popup closes, user can edit form
      });
    });
  }
});

// ADMIN ADD APPOINTMENT LOGIC
(function() {
  const serviceSelect = document.getElementById('admin-service-select');
  const therapistSelect = document.getElementById('admin-therapist-select');
  const dateInput = document.getElementById('admin-date-input');
  const timeSlotsDiv = document.getElementById('admin-time-slots');
  const selectedTimeInput = document.getElementById('admin-selected-time');
  const adminForm = document.getElementById('admin-booking-form');
  const modal = document.getElementById('booking-confirm-modal');
  const btnCancel = modal ? modal.querySelector('.btn-cancel') : null;
  const btnConfirm = modal ? modal.querySelector('.btn-confirm') : null;
  const detailsList = document.getElementById('confirm-details-list');
  let confirmed = false;
  let formDataCache = null;

  if (!(serviceSelect && therapistSelect && dateInput && timeSlotsDiv && selectedTimeInput)) return;

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
    
    // Check if selected date is today
    const isToday = dateInput && dateInput.value === new Date().toISOString().split('T')[0];
    
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
    
    const times = [];
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
    // updateTimeSlotsAvailability(); // This line is removed as per the edit hint
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

  // Confirmation modal logic
  if (adminForm) {
    adminForm.addEventListener('submit', function(e) {
      if (!confirmed) {
        e.preventDefault();
        // Guard: check all required fields exist
        if (!adminForm.customer_name || !adminForm.customer_email || !adminForm.customer_phone || !adminForm.service || !adminForm.therapist || !adminForm.date || !adminForm.time) {
          alert('Form error: One or more fields are missing. Please contact admin.');
          return;
        }
        // Populate details
        const name = adminForm.customer_name.value;
        const email = adminForm.customer_email.value;
        const phone = adminForm.customer_phone.value;
        const service = getSelectedText(adminForm.service);
        const therapist = getSelectedText(adminForm.therapist);
        const date = adminForm.date.value;
        const time = adminForm.time.value;
        detailsList.innerHTML = `
          <li><strong>Name:</strong> ${name}</li>
          <li><strong>Email:</strong> ${email}</li>
          <li><strong>Phone:</strong> ${phone}</li>
          <li><strong>Service:</strong> ${service}</li>
          <li><strong>Therapist:</strong> ${therapist}</li>
          <li><strong>Date:</strong> ${date}</li>
          <li><strong>Time:</strong> ${time}</li>
        `;
        modal.classList.add('active');
        // Cache form data for AJAX
        formDataCache = new FormData(adminForm);
      }
    });
    btnCancel.addEventListener('click', function() {
      modal.classList.remove('active');
    });
    btnConfirm.addEventListener('click', function() {
      confirmed = false; // always false for AJAX
      modal.classList.remove('active');
      // Submit via AJAX
      fetch(adminForm.action, {
        method: 'POST',
        body: formDataCache,
      })
        .then(res => res.text())
        .then(html => {
          // Extract only the form or success message from returned HTML
          const temp = document.createElement('div');
          temp.innerHTML = html;
          // Try to find the success message
          const successMsg = temp.querySelector('.success-message');
          if (successMsg) {
            // Replace whole booking-section with just the success message
            const section = document.getElementById('booking-section');
            if (section) section.outerHTML = successMsg.outerHTML;
          } else {
            // fallback: replace with new form (with error)
            const newForm = temp.querySelector('#booking-form');
            if (newForm) {
              adminForm.parentNode.replaceChild(newForm, adminForm);
            }
          }
        })
        .catch(() => {
          adminForm.parentNode.innerHTML = '<div style="color:#c62828;padding:1rem;text-align:center;">Sorry, something went wrong. Please try again.</div>';
        });
    });
  }
})();

if (!document.getElementById('admin-time-slot-style')) {
  const style = document.createElement('style');
  style.id = 'admin-time-slot-style';
  style.innerHTML = `
    .admin-time-slot-btn.selected { background: #9c6b53 !important; color: #fff !important; border: 2px solid #7a4c2e !important; }
    .admin-time-slot-btn:disabled { background: #eee !important; color: #aaa !important; border: 1px solid #ccc !important; cursor: not-allowed; }
  `;
  document.head.appendChild(style);
} 