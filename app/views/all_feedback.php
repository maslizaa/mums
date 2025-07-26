<?php
include 'layouts/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Feedback</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 2rem; }
        th, td { border: 1px solid #e0cfc2; padding: 0.7rem 1rem; text-align: left; }
        th { background: #f8f6f3; color: #9c6b53; }
        tr:nth-child(even) { background: #fcf9f6; }
    </style>
</head>
<body>
<?php
// Get selected rating from GET parameter
$selected_rating = isset($_GET['rating']) ? (int)$_GET['rating'] : 0;
// Filter feedbacks if a rating is selected
$filtered_feedbacks = $feedbacks;
if ($selected_rating > 0 && $selected_rating <= 5) {
    $filtered_feedbacks = array_filter($feedbacks, function($fb) use ($selected_rating) {
        return $fb['rating'] == $selected_rating;
    });
}
?>
<div style="background:#f7f7f7;min-height:70vh;padding:3rem 0;">
    <h2 style="text-align:center;margin-bottom:2.5rem;font-size:2.5rem;font-weight:bold;">Customer Feedback</h2>
    <form method="get" action="" style="text-align:center;margin-bottom:2.5rem;">
        <input type="hidden" name="page" value="all_feedback">
        <label for="rating" style="font-size:1.1rem;margin-right:1rem;">Filter by Rating:</label>
        <select name="rating" id="rating" onchange="this.form.submit()" style="padding:0.5rem 1.2rem;border-radius:8px;border:1.5px solid #e0cfc2;font-size:1.1rem;">
            <option value="0"<?= $selected_rating === 0 ? ' selected' : '' ?>>All</option>
            <option value="5"<?= $selected_rating === 5 ? ' selected' : '' ?>>★★★★★ (5 stars)</option>
            <option value="4"<?= $selected_rating === 4 ? ' selected' : '' ?>>★★★★☆ (4 stars)</option>
            <option value="3"<?= $selected_rating === 3 ? ' selected' : '' ?>>★★★☆☆ (3 stars)</option>
            <option value="2"<?= $selected_rating === 2 ? ' selected' : '' ?>>★★☆☆☆ (2 stars)</option>
            <option value="1"<?= $selected_rating === 1 ? ' selected' : '' ?>>★☆☆☆☆ (1 star)</option>
        </select>
    </form>
    <div style="display:flex;gap:2.5rem;flex-wrap:wrap;justify-content:center;">
        <?php if (!$filtered_feedbacks || count($filtered_feedbacks) === 0): ?>
            <div style="text-align:center;padding:3rem 2rem;background:#fff;border-radius:20px;box-shadow:0 2px 12px rgba(0,0,0,0.07);max-width:400px;margin:0 auto;">
                <div style="font-size:4rem;color:#bfa76a;margin-bottom:1rem;">&#128172;</div>
                <div style="color:#bfa76a;font-size:1.3rem;font-weight:bold;margin-bottom:0.5rem;">No Feedback Yet</div>
                <div style="color:#888;font-size:1rem;">Be the first to share your experience with our services!</div>
            </div>
        <?php else: ?>
            <?php foreach ($filtered_feedbacks as $fb): ?>
                <div style="background:#fff;border-radius:20px;box-shadow:0 2px 12px rgba(0,0,0,0.07);padding:2.5rem 2rem;width:350px;text-align:left;display:flex;flex-direction:column;justify-content:space-between;">
                    <div>
                        <h3 style="color:#9c6b53;font-size:1.3rem;font-weight:bold;margin-bottom:0.7rem;">Service: <?= htmlspecialchars($fb['service_name']) ?></h3>
                        <div style="color:#9c6b53;font-size:1.05rem;margin-bottom:0.5rem;">
                            <b>Customer:</b> <?= $fb['is_visible'] ? 'Anonymous' : htmlspecialchars($fb['customer_name']) ?>
                        </div>
                        <div style="color:#9c6b53;font-size:1.05rem;margin-bottom:0.5rem;">
                            <b>Date:</b> <?= htmlspecialchars($fb['date']) ?> <b>Time:</b> <?= htmlspecialchars($fb['time']) ?>
                        </div>
                        <div style="margin-bottom:0.7rem;">
                            <b>Rating:</b> <span style="color:#f6b94d;font-size:1.2rem;">
                                <?= str_repeat('★', $fb['rating']) . str_repeat('☆', 5 - $fb['rating']) ?>
                            </span>
                        </div>
                        <div style="color:#444;margin-bottom:1.2rem;"><b>Feedback:</b> <?= htmlspecialchars($fb['comment']) ?></div>
                        <div style="font-size:0.95rem;color:#888;"><b>Submitted at:</b> <?= htmlspecialchars($fb['created_at']) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php include 'layouts/footer.php'; ?> 