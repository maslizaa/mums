<?php
class TherapistController {
    public function dashboard() {
        require_once '../app/models/Appointment.php';
        session_start();
        $therapist = $_SESSION['user'] ?? null;
        $appointments = [];
        $status = isset($_GET['status']) ? $_GET['status'] : 'all';
        if ($therapist) {
            // Update status completed untuk appointment yang sudah lepas
            Appointment::updateStatusCompletedIfPast($therapist['therapist_id']);
            // Fetch ikut status
            $appointments = Appointment::getByTherapistAndStatus($therapist['therapist_id'], $status);
        }
        include '../app/views/therapist/dashboard.php';
    }
    public function profile() {
        require_once '../app/models/Therapist.php';
        session_start();
        $therapist = $_SESSION['user'] ?? null;
        $msg = '';
        // Edit profile
        if (isset($_POST['edit_profile'])) {
            $name = trim($_POST['therapist_name']);
            $phone = trim($_POST['therapist_phone_number']);
            // Normalize phone number to local format
            if (strpos($phone, '+60') === 0) {
                $phone = '0' . substr($phone, 3);
            } else if (strpos($phone, '60') === 0) {
                $phone = '0' . substr($phone, 2);
            }
            if ($therapist && Therapist::updateProfile($therapist['therapist_id'], $name, $phone)) {
                $_SESSION['user']['therapist_name'] = $name;
                $_SESSION['user']['therapist_phone_number'] = $phone;
                $msg = '<div style="color:green;margin-bottom:1rem;">Profile updated successfully.</div>';
            } else {
                $msg = '<div style="color:red;margin-bottom:1rem;">Failed to update profile.</div>';
            }
        }
        // Change profile image
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $target = '/public/assets/images/therapist_' . $therapist['therapist_id'] . '_' . time() . '.' . $ext;
            $absTarget = $_SERVER['DOCUMENT_ROOT'] . $target;
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $absTarget)) {
                Therapist::updatePhoto($therapist['therapist_id'], $target);
                $_SESSION['user']['therapist_photo'] = $target;
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
                require_once '../app/models/Therapist.php';
                global $pdo;
                $stmt = $pdo->prepare('SELECT therapist_password FROM therapists WHERE therapist_id = ?');
                $stmt->execute([$therapist['therapist_id']]);
                $row = $stmt->fetch();
                if ($row && password_verify($current, $row['therapist_password'])) {
                    Therapist::updatePassword($therapist['therapist_id'], $new);
                    $msg = '<div style="color:green;margin-bottom:1rem;">Password changed successfully.</div>';
                } else {
                    $msg = '<div style="color:red;margin-bottom:1rem;">Current password is incorrect.</div>';
                }
            }
        }
        include '../app/views/therapist/profile.php';
    }
    public function change_password() {
        session_start();
        $msg = '';
        $therapist = $_SESSION['user'] ?? null;
        if (!$therapist) {
            header('Location: /public/index.php?page=login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current = $_POST['current_password'] ?? '';
            $new = $_POST['new_password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            if (!$current || !$new || !$confirm) {
                $msg = '<div style="color:red;margin-bottom:1rem;">All password fields required.</div>';
            } else if ($new !== $confirm) {
                $msg = '<div style="color:red;margin-bottom:1rem;">New passwords do not match.</div>';
            } else {
                require_once '../app/models/Therapist.php';
                global $pdo;
                $stmt = $pdo->prepare('SELECT therapist_password FROM therapists WHERE therapist_id = ?');
                $stmt->execute([$therapist['therapist_id']]);
                $row = $stmt->fetch();
                if ($row && password_verify($current, $row['therapist_password'])) {
                    Therapist::updatePassword($therapist['therapist_id'], $new);
                    $msg = '<div style="color:green;margin-bottom:1rem;">Password changed successfully.</div>';
                } else {
                    $msg = '<div style="color:red;margin-bottom:1rem;">Current password is incorrect.</div>';
                }
            }
        }
        include '../app/views/therapist/change_password.php';
    }
} 