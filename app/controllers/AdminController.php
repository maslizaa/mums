<?php
class AdminController {
    public function dashboard() {
        require_once '../app/models/Therapist.php';
        $unapproved_count = Therapist::countUnapproved();
        include '../app/views/admin/dashboard.php';
    }
    public function services() {
        require_once '../app/models/Service.php';
        require_once '../app/models/Therapist.php';
        if (isset($_GET['delete_id'])) {
            Service::delete((int)$_GET['delete_id']);
            header('Location: /public/index.php?page=admin_services');
            exit;
        }
        if (isset($_GET['toggle_id'])) {
            global $pdo;
            $id = (int)$_GET['toggle_id'];
            $stmt = $pdo->prepare('UPDATE services SET is_active = NOT is_active WHERE service_id = ?');
            $stmt->execute([$id]);
            header('Location: /public/index.php?page=admin_services');
            exit;
        }
        $services = Service::getAllWithInactive();
        $unapproved_count = Therapist::countUnapproved();
        include '../app/views/admin/services.php';
    }
    public function add_service() {
        require_once '../app/models/Service.php';
        session_start();
        $msg = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['service_name']);
            $description = trim($_POST['service_description']);
            $duration = (int)$_POST['service_duration'];
            $price = (float)$_POST['service_price'];
            $status = (int)$_POST['service_status'];
            if (Service::add($name, $description, $duration, $price, $status)) {
                $msg = '<div style="color:green;margin-bottom:1rem;">Service added successfully.</div>';
            } else {
                $msg = '<div style="color:red;margin-bottom:1rem;">Failed to add service.</div>';
            }
        }
        include '../app/views/admin/add_service.php';
    }
    public function edit_service() {
        require_once '../app/models/Service.php';
        session_start();
        $msg = '';
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /public/index.php?page=admin_services');
            exit;
        }
        $service = Service::getById($id);
        if (!$service) {
            $msg = '<div style="color:red;margin-bottom:1rem;">Service not found.</div>';
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['service_name']);
            $description = trim($_POST['service_description']);
            $duration = (int)$_POST['service_duration'];
            $price = (float)$_POST['service_price'];
            if (Service::update($id, $name, $description, $duration, $price, $service['is_active'])) {
                $msg = '<div style="color:green;margin-bottom:1rem;">Service updated successfully.</div>';
                $service = Service::getById($id); // Refresh data
            } else {
                $msg = '<div style="color:red;margin-bottom:1rem;">Failed to update service.</div>';
            }
        }
        include '../app/views/admin/edit_service.php';
    }
    public function therapists() {
        require_once '../app/models/Therapist.php';
        $msg = '';
        $unapproved_count = Therapist::countUnapproved();
        if (isset($_GET['delete_id'])) {
            try {
                Therapist::delete((int)$_GET['delete_id']);
                header('Location: /public/index.php?page=admin_therapists');
                exit;
            } catch (Exception $e) {
                if ($e->getMessage() === 'Therapist has future appointments.') {
                    $msg = '<div style="color:red;margin-bottom:1rem;">Cannot delete therapist because there are future appointments.</div>';
                } else {
                    $msg = '<div style="color:red;margin-bottom:1rem;">Cannot delete therapist because there are related appointments.</div>';
                }
            }
        }
        if (isset($_GET['approve_id'])) {
            global $pdo;
            $stmt = $pdo->prepare('UPDATE therapists SET is_approved = 1, status = 1 WHERE therapist_id = ?');
            $stmt->execute([(int)$_GET['approve_id']]);
            header('Location: /public/index.php?page=admin_therapists');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['toggle_status_id'])) {
                $id = (int)$_POST['toggle_status_id'];
                
                // Check if therapist is approved first
                global $pdo;
                $stmt = $pdo->prepare('SELECT is_approved FROM therapists WHERE therapist_id = ?');
                $stmt->execute([$id]);
                $is_approved = $stmt->fetchColumn();
                
                if (!$is_approved) {
                    $msg = '<div style="color:red;margin-bottom:1rem;">Cannot change status: Therapist must be approved first.</div>';
                } else {
                    if (isset($_POST['set_active'])) {
                        $stmt = $pdo->prepare('UPDATE therapists SET status = 1 WHERE therapist_id = ?');
                        $stmt->execute([$id]);
                        header('Location: /public/index.php?page=admin_therapists');
                        exit;
                    } elseif (isset($_POST['set_inactive'])) {
                        // Check for upcoming appointments
                        $today = date('Y-m-d');
                        $stmt = $pdo->prepare('SELECT COUNT(*) FROM appointments WHERE therapist_id = ? AND date >= ? AND status IN ("booked", "rescheduled")');
                        $stmt->execute([$id, $today]);
                        $count = $stmt->fetchColumn();
                        if ($count > 0) {
                            $msg = '<div style="color:red;margin-bottom:1rem;">Cannot set as inactive: Therapist has upcoming appointments.</div>';
                        } else {
                            $stmt = $pdo->prepare('UPDATE therapists SET status = 0 WHERE therapist_id = ?');
                            $stmt->execute([$id]);
                            header('Location: /public/index.php?page=admin_therapists');
                            exit;
                        }
                    }
                }
            }
        }
        $therapists = Therapist::getAll();
        include '../app/views/admin/therapists.php';
    }
    public function add_therapist() {
        require_once '../app/models/Therapist.php';
        session_start();
        $msg = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['therapist_name']);
            $email = trim($_POST['therapist_email']);
            $phone = trim($_POST['therapist_phone']);
            $password = $_POST['therapist_password'];
            $strength = (int)$_POST['therapist_strength'];
            $photo = null;
            if (isset($_FILES['therapist_photo']) && $_FILES['therapist_photo']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['therapist_photo']['name'], PATHINFO_EXTENSION);
                $target = '/public/assets/images/therapist_' . time() . '.' . $ext;
                $absTarget = $_SERVER['DOCUMENT_ROOT'] . $target;
                if (move_uploaded_file($_FILES['therapist_photo']['tmp_name'], $absTarget)) {
                    $photo = $target;
                } else {
                    $msg = '<div style="color:red;margin-bottom:1rem;">Failed to upload image.</div>';
                }
            }
            $result = Therapist::register($name, $email, $password, true); // autoApprove = true for admin
            if ($result === true) {
                // Get the new therapist's ID
                global $pdo;
                $stmt = $pdo->prepare('SELECT therapist_id FROM therapists WHERE therapist_email = ?');
                $stmt->execute([$email]);
                $therapist = $stmt->fetch();
                if ($therapist) {
                    $id = $therapist['therapist_id'];
                    Therapist::updateProfile($id, $name, $phone);
                    $stmt = $pdo->prepare('UPDATE therapists SET therapist_strength = ? WHERE therapist_id = ?');
                    $stmt->execute([$strength, $id]);
                    if ($photo) {
                        Therapist::updatePhoto($id, $photo);
                    }
                }
                $msg = '<div style="color:green;margin-bottom:1rem;">Therapist added successfully.</div>';
            } else {
                $msg = '<div style="color:red;margin-bottom:1rem;">' . htmlspecialchars($result) . '</div>';
            }
        }
        include '../app/views/admin/add_therapist.php';
    }
    public function edit_therapist() {
        require_once '../app/models/Therapist.php';
        session_start();
        $msg = '';
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /public/index.php?page=admin_therapists');
            exit;
        }
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM therapists WHERE therapist_id = ?');
        $stmt->execute([$id]);
        $therapist = $stmt->fetch();
        if (!$therapist) {
            $msg = '<div style="color:red;margin-bottom:1rem;">Therapist not found.</div>';
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['therapist_name']);
            $email = trim($_POST['therapist_email']);
            $phone = trim($_POST['therapist_phone']);
            $strength = (int)$_POST['therapist_strength'];
            $photo = $therapist['therapist_photo'];
            if (isset($_FILES['therapist_photo']) && $_FILES['therapist_photo']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['therapist_photo']['name'], PATHINFO_EXTENSION);
                $target = '/public/assets/images/therapist_' . $id . '_' . time() . '.' . $ext;
                $absTarget = $_SERVER['DOCUMENT_ROOT'] . $target;
                if (move_uploaded_file($_FILES['therapist_photo']['tmp_name'], $absTarget)) {
                    $photo = $target;
                } else {
                    $msg = '<div style="color:red;margin-bottom:1rem;">Failed to upload image.</div>';
                }
            }
            try {
                $stmt = $pdo->prepare('UPDATE therapists SET therapist_name = ?, therapist_email = ?, therapist_phone_number = ?, therapist_strength = ?, therapist_photo = ? WHERE therapist_id = ?');
                if ($stmt->execute([$name, $email, $phone, $strength, $photo, $id])) {
                    $msg = '<div style="color:green;margin-bottom:1rem;">Therapist updated successfully.</div>';
                    $stmt = $pdo->prepare('SELECT * FROM therapists WHERE therapist_id = ?');
                    $stmt->execute([$id]);
                    $therapist = $stmt->fetch();
                } else {
                    $msg = '<div style="color:red;margin-bottom:1rem;">Failed to update therapist.</div>';
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $msg = '<div style="color:red;margin-bottom:1rem;">Email already exists. Please use a different email.</div>';
                } else {
                    $msg = '<div style="color:red;margin-bottom:1rem;">Failed to update therapist.</div>';
                }
            }
        }
        include '../app/views/admin/edit_therapist.php';
    }
    public function appointments() {
        require_once '../app/models/Appointment.php';
        require_once '../app/models/Sale.php';
        require_once '../app/models/Therapist.php';
        $therapists = Therapist::getAllActive();
        $msg = '';
        if (isset($_GET['delete_id'])) {
            try {
                global $pdo;
                $stmt = $pdo->prepare('DELETE FROM appointments WHERE appointment_id = ?');
                $stmt->execute([(int)$_GET['delete_id']]);
                header('Location: /public/index.php?page=admin_appointments');
                exit;
            } catch (Exception $e) {
                $msg = '<div style="color:red;margin-bottom:1rem;">Failed to delete appointment.</div>';
            }
        }
        // Mark as complete
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'mark_complete') {
            $id = (int)$_POST['appointment_id'];
            global $pdo;
            // Update status
            $stmt = $pdo->prepare('UPDATE appointments SET status = "completed" WHERE appointment_id = ?');
            $stmt->execute([$id]);
            // Insert ke sales jika belum ada
            $sql = "SELECT a.appointment_id, aps.service_id, s.service_price
                    FROM appointments a
                    JOIN appointment_services aps ON a.appointment_id = aps.appointment_id
                    JOIN services s ON aps.service_id = s.service_id
                    WHERE a.appointment_id = ?";
            $stmt2 = $pdo->prepare($sql);
            $stmt2->execute([$id]);
            $row = $stmt2->fetch();
            if ($row) {
                $check = $pdo->prepare('SELECT COUNT(*) FROM sales WHERE appointment_id = ?');
                $check->execute([$id]);
                if ($check->fetchColumn() == 0) {
                    $insert = $pdo->prepare('INSERT INTO sales (appointment_id, amount) VALUES (?, ?)');
                    $insert->execute([$id, $row['service_price']]);
                }
            }
            $msg = '<div style="background:#eafbe7;color:#388e3c;padding:1rem 1.5rem;border-radius:10px;margin-bottom:1.5rem;font-size:1.15rem;text-align:center;box-shadow:0 2px 8px rgba(56,142,60,0.07);">Appointment marked as completed and added to sales.</div>';
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reschedule') {
            require_once '../app/models/Appointment.php';
            $id = (int)$_POST['appointment_id'];
            $new_date = $_POST['new_date'];
            $new_time = $_POST['new_time'];
            
            // Validate date and time - prevent past appointments
            $selected_datetime = strtotime($new_date . ' ' . $new_time);
            $current_datetime = time();
            $buffer_time = $current_datetime + (60 * 60); // 1 hour buffer
            
            if ($selected_datetime < $buffer_time) {
                header('Location: /public/index.php?page=admin_appointments&error=' . urlencode('Cannot reschedule to past date/time. Please select a future date and time.'));
                exit;
            }
            
            // Validate date format
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $new_date)) {
                header('Location: /public/index.php?page=admin_appointments&error=' . urlencode('Invalid date format.'));
                exit;
            }
            
            // Validate time format
            if (!preg_match('/^\d{2}:\d{2}$/', $new_time)) {
                header('Location: /public/index.php?page=admin_appointments&error=' . urlencode('Invalid time format.'));
                exit;
            }
            
            // Check if date is not in the past
            $today = date('Y-m-d');
            if ($new_date < $today) {
                header('Location: /public/index.php?page=admin_appointments&error=' . urlencode('Cannot reschedule to past dates.'));
                exit;
            }
            
            // Check if time is valid for today
            if ($new_date === $today) {
                $current_time = date('H:i');
                $buffer_hour = date('H:i', strtotime('+1 hour'));
                if ($new_time <= $buffer_hour) {
                    header('Location: /public/index.php?page=admin_appointments&error=' . urlencode('Cannot reschedule within 1 hour from now. Please select a later time.'));
                    exit;
                }
            }
            
            global $pdo;
            $stmt = $pdo->prepare('UPDATE appointments SET date = ?, time = ?, status = "rescheduled" WHERE appointment_id = ?');
            $stmt->execute([$new_date, $new_time, $id]);
            header('Location: /public/index.php?page=admin_appointments&rescheduled=1');
            exit;
        }
        // Cancel appointment
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancel') {
            $id = (int)$_POST['appointment_id'];
            $reason = trim($_POST['cancel_reason']);
            global $pdo;
            $stmt = $pdo->prepare('UPDATE appointments SET status = "cancelled", feedback = ? WHERE appointment_id = ?');
            $stmt->execute([$reason, $id]);
            header('Location: /public/index.php?page=admin_appointments&cancelled=1');
            exit;
        }
        
        // Toggle appointment status
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_appointment_status_id'])) {
            $id = (int)$_POST['toggle_appointment_status_id'];
            global $pdo;
            
            // Get current status
            $stmt = $pdo->prepare('SELECT status FROM appointments WHERE appointment_id = ?');
            $stmt->execute([$id]);
            $current_status = $stmt->fetchColumn();
            
            // Toggle status based on current status
            if (strtolower($current_status) === 'booked') {
                $new_status = 'completed';
            } elseif (strtolower($current_status) === 'completed') {
                $new_status = 'booked';
            } elseif (strtolower($current_status) === 'cancelled' || strtolower($current_status) === 'rescheduled') {
                $new_status = 'booked';
            } else {
                $new_status = 'booked';
            }
            
            // Update status
            $stmt = $pdo->prepare('UPDATE appointments SET status = ? WHERE appointment_id = ?');
            $stmt->execute([$new_status, $id]);
            
            // If changing to completed, add to sales
            if ($new_status === 'completed') {
                $sql = "SELECT a.appointment_id, aps.service_id, s.service_price
                        FROM appointments a
                        JOIN appointment_services aps ON a.appointment_id = aps.appointment_id
                        JOIN services s ON aps.service_id = s.service_id
                        WHERE a.appointment_id = ?";
                $stmt2 = $pdo->prepare($sql);
                $stmt2->execute([$id]);
                $row = $stmt2->fetch();
                if ($row) {
                    $check = $pdo->prepare('SELECT COUNT(*) FROM sales WHERE appointment_id = ?');
                    $check->execute([$id]);
                    if ($check->fetchColumn() == 0) {
                        $insert = $pdo->prepare('INSERT INTO sales (appointment_id, amount) VALUES (?, ?)');
                        $insert->execute([$id, $row['service_price']]);
                    }
                }
            }
            
            header('Location: /public/index.php?page=admin_appointments&status_updated=1');
            exit;
        }
        // Handle filters
        $filter_month = isset($_GET['filter_month']) ? $_GET['filter_month'] : date('m');
        $filter_year = isset($_GET['filter_year']) ? $_GET['filter_year'] : date('Y');
        $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';
        $therapist_filter = isset($_GET['therapist_filter']) ? $_GET['therapist_filter'] : '';
        $appointments = Appointment::getByMonthYearStatusTherapist($filter_month, $filter_year, $status_filter, $therapist_filter);
        include '../app/views/admin/appointments.php';
    }
    public function today_appointments() {
        require_once '../app/models/Appointment.php';
        require_once '../app/models/Sale.php';
        require_once '../app/models/Therapist.php';
        $therapists = Therapist::getAllActive();
        $msg = '';
        $unapproved_count = Therapist::countUnapproved();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'mark_complete') {
            $id = (int)$_POST['appointment_id'];
            global $pdo;
            // Update status
            $stmt = $pdo->prepare('UPDATE appointments SET status = "completed" WHERE appointment_id = ?');
            $stmt->execute([$id]);
            // Insert ke sales jika belum ada
            $sql = "SELECT a.appointment_id, aps.service_id, s.service_price
                    FROM appointments a
                    JOIN appointment_services aps ON a.appointment_id = aps.appointment_id
                    JOIN services s ON aps.service_id = s.service_id
                    WHERE a.appointment_id = ?";
            $stmt2 = $pdo->prepare($sql);
            $stmt2->execute([$id]);
            $row = $stmt2->fetch();
            if ($row) {
                $check = $pdo->prepare('SELECT COUNT(*) FROM sales WHERE appointment_id = ?');
                $check->execute([$id]);
                if ($check->fetchColumn() == 0) {
                    $insert = $pdo->prepare('INSERT INTO sales (appointment_id, amount) VALUES (?, ?)');
                    $insert->execute([$id, $row['service_price']]);
                }
            }
            $msg = '<div style="background:#eafbe7;color:#388e3c;padding:1rem 1.5rem;border-radius:10px;margin-bottom:1.5rem;font-size:1.15rem;text-align:center;box-shadow:0 2px 8px rgba(56,142,60,0.07);">Appointment marked as completed and added to sales.</div>';
        }
        include '../app/views/admin/today_appointments.php';
    }
    public function sales_report() {
        require_once '../app/models/Therapist.php';
        $unapproved_count = Therapist::countUnapproved();
        include '../app/views/admin/sales_report.php';
    }
    public function profile() {
        require_once '../app/models/Admin.php';
        session_start();
        $admin = $_SESSION['user'] ?? null;
        $msg = '';
        // Edit profile
        if (isset($_POST['edit_profile'])) {
            $name = trim($_POST['admin_name']);
            $phone = trim($_POST['admin_phone_number']);
            // Normalize phone number to local format
            if (strpos($phone, '+60') === 0) {
                $phone = '0' . substr($phone, 3);
            } else if (strpos($phone, '60') === 0) {
                $phone = '0' . substr($phone, 2);
            }
            if ($admin && Admin::updateProfile($admin['admin_id'], $name, $phone)) {
                $_SESSION['user']['admin_name'] = $name;
                $_SESSION['user']['admin_phone_number'] = $phone;
                $msg = '<div style="color:green;margin-bottom:1rem;">Profile updated successfully.</div>';
            } else {
                $msg = '<div style="color:red;margin-bottom:1rem;">Failed to update profile.</div>';
            }
        }
        // Change profile image
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $target = '/public/assets/images/admin_' . $admin['admin_id'] . '_' . time() . '.' . $ext;
            $absTarget = $_SERVER['DOCUMENT_ROOT'] . $target;
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $absTarget)) {
                Admin::updatePhoto($admin['admin_id'], $target);
                $_SESSION['user']['admin_photo'] = $target;
                $msg = '<div style="color:green;margin-bottom:1rem;">Profile image updated.</div>';
            } else {
                $msg = '<div style="color:red;margin-bottom:1rem;">Failed to upload image.</div>';
            }
        }
        // Change password
        if (isset($_POST['change_password'])) {
            $current = $_POST['current_password'];
            $new = $_POST['new_password'];
            $confirm = $_POST['confirm_password'];
            if (!$current || !$new || !$confirm) {
                $msg = '<div style="color:red;margin-bottom:1rem;">All password fields required.</div>';
            } else if ($new !== $confirm) {
                $msg = '<div style="color:red;margin-bottom:1rem;">New passwords do not match.</div>';
            } else {
                // Verify current password
                require_once '../app/models/Admin.php';
                global $pdo;
                $stmt = $pdo->prepare('SELECT admin_password FROM admins WHERE admin_id = ?');
                $stmt->execute([$admin['admin_id']]);
                $row = $stmt->fetch();
                if ($row && password_verify($current, $row['admin_password'])) {
                    Admin::updatePassword($admin['admin_id'], $new);
                    $msg = '<div style="color:green;margin-bottom:1rem;">Password changed successfully.</div>';
                } else {
                    $msg = '<div style="color:red;margin-bottom:1rem;">Current password is incorrect.</div>';
                }
            }
        }
        include '../app/views/admin/profile.php';
    }
    public function manage_feedback() {
        require_once '../app/models/Feedback.php';
        require_once '../app/models/Therapist.php';
        $msg = '';
        $unapproved_count = Therapist::countUnapproved();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
            if (Feedback::deleteById((int)$_POST['delete_id'])) {
                $msg = '<div style="color:green;margin-bottom:1rem;">Feedback deleted successfully.</div>';
            } else {
                $msg = '<div style="color:red;margin-bottom:1rem;">Failed to delete feedback.</div>';
            }
        }
        $feedbacks = Feedback::getAllWithDetails();
        include '../app/views/admin/manage_feedback.php';
    }
    public function all_feedback() {
        require_once '../app/models/Feedback.php';
        $feedbacks = Feedback::getAllFeedback();
        include '../app/views/all_feedback.php';
    }
    public function add_appointment() {
        require_once '../app/models/Service.php';
        require_once '../app/models/Therapist.php';
        require_once '../app/models/Appointment.php';
        $services = Service::getAll();
        $therapists = Therapist::getAllActive();
        $success = false;
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $service_id = $_POST['service'] ?? '';
            $therapist_id = $_POST['therapist'] ?? '';
            $date = $_POST['date'] ?? '';
            $time = $_POST['time'] ?? '';
            $addon_therapist = isset($_POST['addon_therapist']) ? $_POST['addon_therapist'] : null;
            $addon_service = isset($_POST['addon_service']) ? $_POST['addon_service'] : null;
            if (!$name || !$email || !$phone || !$service_id || !$therapist_id || !$date || !$time) {
                header('Location: /public/index.php?page=admin_add_appointment&error=' . urlencode('Sila lengkapkan semua maklumat.'));
                exit;
            }
            
            // Validate date and time - prevent past appointments
            $selected_datetime = strtotime($date . ' ' . $time);
            $current_datetime = time();
            $buffer_time = $current_datetime + (60 * 60); // 1 hour buffer
            
            if ($selected_datetime < $buffer_time) {
                header('Location: /public/index.php?page=admin_add_appointment&error=' . urlencode('Cannot book appointments for past date/time. Please select a future date and time.'));
                exit;
            }
            
            // Validate date format
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                header('Location: /public/index.php?page=admin_add_appointment&error=' . urlencode('Invalid date format.'));
                exit;
            }
            
            // Validate time format
            if (!preg_match('/^\d{2}:\d{2}$/', $time)) {
                header('Location: /public/index.php?page=admin_add_appointment&error=' . urlencode('Invalid time format.'));
                exit;
            }
            
            // Check if date is not in the past
            $today = date('Y-m-d');
            if ($date < $today) {
                header('Location: /public/index.php?page=admin_add_appointment&error=' . urlencode('Cannot book appointments for past dates.'));
                exit;
            }
            
            // Check if time is valid for today
            if ($date === $today) {
                $current_time = date('H:i');
                $buffer_hour = date('H:i', strtotime('+1 hour'));
                if ($time <= $buffer_hour) {
                    header('Location: /public/index.php?page=admin_add_appointment&error=' . urlencode('Cannot book appointments within 1 hour from now. Please select a later time.'));
                    exit;
                }
            }
            
            // Randomly assign therapist if 'any' is selected
            if ($therapist_id === 'any') {
                    $approved_therapists = Therapist::getAllApproved();
                    require_once '../app/models/Service.php';
                    $service = Service::getById($service_id);
                    $service_duration = isset($service['service_duration']) ? (int)$service['service_duration'] : 0;
                    $available_therapists = [];
                    foreach ($approved_therapists as $therapist) {
                        $booked_times = Appointment::getBookedTimes($therapist['therapist_id'], $date);
                        $is_available = true;
                        $requested_start = strtotime($time);
                        $requested_end = $requested_start + ($service_duration * 60);
                        foreach ($booked_times as $bt) {
                            $bt_start = strtotime($bt['time']);
                            $bt_end = $bt_start + ($bt['duration'] * 60);
                            // Check for overlap
                            if ($requested_start < $bt_end && $requested_end > $bt_start) {
                                $is_available = false;
                                break;
                            }
                        }
                        if ($is_available) {
                            $available_therapists[] = $therapist['therapist_id'];
                        }
                    }
                    if (!empty($available_therapists)) {
                        $therapist_id = $available_therapists[array_rand($available_therapists)];
                    } else {
                        header('Location: /public/index.php?page=admin_add_appointment&error=' . urlencode('No therapist available for the selected time.'));
                        exit;
                    }
                }
            $appointment_id = Appointment::create($name, $email, $phone, $service_id, $therapist_id, $date, $time, $addon_therapist, $addon_service);
            if ($appointment_id) {
                header('Location: /public/index.php?page=admin_add_appointment&success=1');
                exit;
            } else {
                header('Location: /public/index.php?page=admin_add_appointment&error=' . urlencode('Appointment failed. Please try again.'));
                exit;
            }
        }
        include '../app/views/admin/add_appointment.php';
    }
} 