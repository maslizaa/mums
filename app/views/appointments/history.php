<div style="max-width:800px;margin:3rem auto 2rem auto;padding:2.5rem 2rem;background:#fff;border-radius:2rem;box-shadow:0 2px 12px rgba(0,0,0,0.07);">
    <h2 style="text-align:center;font-size:2.2rem;font-weight:bold;margin-bottom:0.5rem;">Appointment History</h2>
    <div style="text-align:center;color:#bfa76a;margin-bottom:2rem;">Check Your Appointment History Here</div>
    <form method="get" action="/public/index.php" style="display:flex;justify-content:center;align-items:center;gap:1.5rem;margin-bottom:2rem;">
        <input type="hidden" name="page" value="appointments">
        <input type="text" name="phone" placeholder="Enter Your Phone Number" style="padding:1rem 1.2rem;border-radius:12px;border:1.5px solid #eee;font-size:1.1rem;width:320px;">
        <button type="submit" style="background:#33291a;color:#fff;padding:1rem 2.5rem;border-radius:2rem;font-size:1.1rem;font-weight:bold;border:none;display:flex;align-items:center;gap:0.5rem;">
            <span style="font-size:1.3rem;">&#128269;</span> FIND
        </button>
    </form>
    <?php if (isset($_GET['feedback']) && $_GET['feedback'] === 'success'): ?>
        <div style="background:#eafbe7;color:#388e3c;padding:1rem 1.5rem;border-radius:10px;margin-bottom:1.5rem;font-size:1.15rem;text-align:center;box-shadow:0 2px 8px rgba(56,142,60,0.07);">
            Thank you for your feedback!
        </div>
    <?php endif; ?>
    <table style="width:100%;border-collapse:collapse;font-size:1.1rem;">
        <thead>
            <tr style="background:#fafafa;">
                <th style="padding:0.8rem 0.5rem;border:1px solid #e0e0e0;">#</th>
                <th style="padding:0.8rem 0.5rem;border:1px solid #e0e0e0;">Date</th>
                <th style="padding:0.8rem 0.5rem;border:1px solid #e0e0e0;">Time</th>
                <th style="padding:0.8rem 0.5rem;border:1px solid #e0e0e0;">Service</th>
                <th style="padding:0.8rem 0.5rem;border:1px solid #e0e0e0;">Status</th>
                <th style="padding:0.8rem 0.5rem;border:1px solid #e0e0e0;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($appointments)): ?>
                <?php foreach ($appointments as $i => $a): ?>
                    <tr>
                        <td style="padding:0.7rem 0.5rem;border:1px solid #e0e0e0;"> <?= $i+1 ?> </td>
                        <td style="padding:0.7rem 0.5rem;border:1px solid #e0e0e0;"> <?= htmlspecialchars($a['date']) ?> </td>
                        <td style="padding:0.7rem 0.5rem;border:1px solid #e0e0e0;"> <?= htmlspecialchars($a['time']) ?> </td>
                        <td style="padding:0.7rem 0.5rem;border:1px solid #e0e0e0;"> <?= htmlspecialchars($a['service_name']) ?> </td>
                        <td style="padding:0.7rem 0.5rem;border:1px solid #e0e0e0;"> <?= htmlspecialchars($a['status']) ?> </td>
                        <td style="padding:0.7rem 0.5rem;border:1px solid #e0e0e0;text-align:center;">
                            <?php
                            require_once '../app/models/Feedback.php';
                            $hasFeedback = Feedback::hasFeedback($a['appointment_id']);
                            // Check if appointment is in the past
                            $now = new DateTime();
                            $apptDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $a['date'].' '.$a['time']);
                            if (!$apptDateTime) $apptDateTime = DateTime::createFromFormat('Y-m-d H:i', $a['date'].' '.$a['time']);
                            $isPast = $apptDateTime && $apptDateTime < $now;
                            ?>
                            <?php if ($hasFeedback): ?>
                                <a href="/public/index.php?page=feedback&appointment_id=<?= urlencode($a['appointment_id']) ?>&view=1"
                                   style="background:#eafbe7;color:#388e3c;padding:0.5rem 1.5rem;border-radius:2rem;font-weight:bold;text-decoration:none;font-size:1rem;letter-spacing:1px;display:inline-block;">
                                    &#10003; View
                                </a>
                            <?php elseif ($isPast): ?>
                                <a href="/public/index.php?page=feedback&appointment_id=<?= urlencode($a['appointment_id']) ?>"
                                   style="background:#f6b94d;color:#fff;padding:0.5rem 1.5rem;border-radius:2rem;font-weight:bold;text-decoration:none;font-size:1rem;letter-spacing:1px;display:inline-block;">
                                    FEEDBACK
                                </a>
                            <?php else: ?>
                                <span style="background:#eee;color:#aaa;padding:0.5rem 1.5rem;border-radius:2rem;font-weight:bold;font-size:1rem;letter-spacing:1px;display:inline-block;cursor:not-allowed;" title="You can only give feedback after your appointment is completed.">FEEDBACK</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center;padding:2rem;">
                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.8rem;">
                            <div style="font-size:2.5rem;color:#bfa76a;">&#128197;</div>
                            <div style="color:#bfa76a;font-size:1.1rem;font-weight:bold;">No appointments found for this phone number.</div>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div> 