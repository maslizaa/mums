<?php 
require_once __DIR__ . '/../../models/Feedback.php';
require_once __DIR__ . '/../../models/Appointment.php';

$appointment_id = $_GET['appointment_id'] ?? null;
$appointment = null;
if ($appointment_id) {
    $appointment = Appointment::getById($appointment_id);
}
?>
<style>
.feedback-card {
    max-width: 420px;
    margin: 3rem auto 2rem auto;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(156,107,83,0.10);
    padding: 2.5rem 2rem 2rem 2rem;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.feedback-title {
    text-align: center;
    font-size: 2rem;
    font-weight: bold;
    color: #9c6b53;
    margin-bottom: 1.2rem;
    letter-spacing: 1px;
}
.appointment-details-box {
    background: #f8f6f3;
    border-radius: 12px;
    padding: 1.2rem 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(156,107,83,0.07);
    font-size: 1.08rem;
}
.feedback-form-label {
    font-weight: bold;
    color: #9c6b53;
    margin-bottom: 0.5rem;
    display: block;
}
.star-rating {
    direction: rtl;
    display: flex;
    justify-content: center;
    font-size: 2.5rem;
    gap: 0.15em;
    margin-bottom: 1.2rem;
}
.star-rating input[type="radio"] {
    display: none;
}
.star-rating label {
    color: #e0cfc2;
    cursor: pointer;
    transition: color 0.2s;
    padding: 0 2px;
}
.star-rating input[type="radio"]:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #f6b94d;
}
textarea {
    width: 100%;
    height: 110px;
    border-radius: 10px;
    border: 1.5px solid #e0cfc2;
    padding: 1rem;
    font-size: 1.1rem;
    margin-bottom: 1.2rem;
    resize: vertical;
}
.feedback-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.2rem;
}
.feedback-actions label {
    font-size: 1rem;
    color: #9c6b53;
    font-weight: 500;
}
.submit-btn {
    background: #9c6b53;
    color: #fff;
    padding: 0.9rem 2.5rem;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: bold;
    border: none;
    box-shadow: 0 2px 8px rgba(156,107,83,0.07);
    cursor: pointer;
    transition: background 0.2s;
}
.submit-btn:hover {
    background: #7a4c2e;
}
</style>

<div class="feedback-card">
    <div class="feedback-title">Appointment Feedback</div>
    <?php if (!$appointment_id): ?>
        <div style="color:red; font-size:1.1rem; text-align:center; margin:2rem 0;">Sila akses halaman ini melalui appointment yang sah. <br> (Parameter <b>appointment_id</b> tiada dalam URL)</div>
    <?php elseif (!$appointment): ?>
        <div style="color:red; font-size:1.1rem; text-align:center; margin:2rem 0;">Maklumat appointment tidak dijumpai. Sila semak <b>appointment_id</b> dalam URL.</div>
    <?php else: ?>
        <?php
        $isView = isset($_GET['view']) && $_GET['view'] == 1;
        if ($isView) {
            // Fetch feedback for this appointment
            require_once '../app/models/Feedback.php';
            $feedback = Feedback::getByAppointmentId($appointment_id);
            if ($feedback):
        ?>
            <div class="appointment-details-box">
                <div><b>Service:</b> <?= htmlspecialchars($appointment['service_name']) ?></div>
                <div><b>Date:</b> <?= htmlspecialchars($appointment['date']) ?></div>
                <div><b>Time:</b> <?= htmlspecialchars($appointment['time']) ?></div>
            </div>
            <div class="feedback-view-box">
                <div><b>Rating:</b> <?= str_repeat('★', $feedback['rating']) . str_repeat('☆', 5 - $feedback['rating']) ?></div>
                <div><b>Feedback:</b> <?= htmlspecialchars($feedback['comment']) ?></div>
                <div><b>Anonymous:</b> <?= $feedback['is_visible'] ? 'Yes' : 'No' ?></div>
                <div><b>Submitted at:</b> <?= htmlspecialchars($feedback['created_at']) ?></div>
            </div>
        <?php
            else:
                echo '<div style="color:red;">No feedback found for this appointment.</div>';
            endif;
        } else {
            ?>
            <div class="appointment-details-box">
                <div style="font-size:1.08rem;"><b>Service:</b> <?= htmlspecialchars($appointment['service_name']) ?></div>
                <?php if (!empty($appointment['therapist_name'])): ?>
                    <div style="font-size:1.08rem;"><b>Therapist:</b> <?= htmlspecialchars($appointment['therapist_name']) ?></div>
                <?php endif; ?>
                <div style="font-size:1.08rem;"><b>Date:</b> <?= htmlspecialchars($appointment['date']) ?></div>
                <div style="font-size:1.08rem;"><b>Time:</b> <?= htmlspecialchars($appointment['time']) ?></div>
            </div>
            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div style="background:#eafbe7;color:#388e3c;padding:1rem 1.5rem;border-radius:10px;margin-bottom:1.5rem;font-size:1.15rem;text-align:center;box-shadow:0 2px 8px rgba(56,142,60,0.07);">
                    Thank you for your feedback!
                </div>
            <?php endif; ?>
            <?php
            // Example: show all feedback for this appointment
            $allFeedback = Feedback::getAllByAppointmentId($appointment_id);
            // Show feedback if available
            if ($allFeedback && count($allFeedback) > 0):
            ?>
                <div style="margin-bottom:2rem;">
                    <h3 style="color:#9c6b53;margin-bottom:1rem;">Feedback</h3>
                    <?php foreach ($allFeedback as $fb): ?>
                        <div style="background:#f8f6f3;border-radius:10px;padding:1rem 1.2rem;margin-bottom:1rem;box-shadow:0 2px 8px rgba(156,107,83,0.07);">
                            <div><b>Rating:</b> <?= str_repeat('★', $fb['rating']) . str_repeat('☆', 5 - $fb['rating']) ?></div>
                            <div><b>Feedback:</b> <?= htmlspecialchars($fb['comment']) ?></div>
                            <div><b>Anonymous:</b> <?= isset($fb['is_visible']) && $fb['is_visible'] ? 'Yes' : 'No' ?></div>
                            <div style="font-size:0.95rem;color:#888;"><b>Submitted at:</b> <?= htmlspecialchars($fb['created_at']) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form method="post" action="/public/index.php?page=feedback&appointment_id=<?= htmlspecialchars($appointment_id) ?>">
                <label class="feedback-form-label">Rating</label>
                <span class="star-rating">
                    <?php for ($i=5; $i>=1; $i--): ?>
                        <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" required>
                        <label for="star<?= $i ?>">&#9733;</label>
                    <?php endfor; ?>
                </span>
                <label class="feedback-form-label" for="feedback">Feedback</label>
                <textarea id="feedback" name="feedback" required placeholder="Write your feedback..."></textarea>
                <div class="feedback-actions">
                    <label><input type="checkbox" name="anonymous" value="1"> Anonymous</label>
                    <button type="submit" class="submit-btn">Submit Feedback</button>
                </div>
            </form>
        <?php
        }
        ?>
    <?php endif; ?>
</div> 