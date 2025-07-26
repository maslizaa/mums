<div style="background:#f7f7f7;min-height:70vh;padding:3rem 0;">
    <h2 style="text-align:center;margin-bottom:2.5rem;font-size:2.5rem;font-weight:bold;">Our Services</h2>
    <div style="display:flex;gap:2.5rem;flex-wrap:wrap;justify-content:center;">
        <?php if (!empty($services)) foreach ($services as $service): ?>
            <div style="background:#fff;border-radius:20px;box-shadow:0 2px 12px rgba(0,0,0,0.07);padding:2.5rem 2rem;width:350px;text-align:left;display:flex;flex-direction:column;justify-content:space-between;">
                <div>
                    <?php if (!empty($service['service_image'])): ?>
                        <img src="/public/assets/images/<?= htmlspecialchars($service['service_image']) ?>" alt="<?= htmlspecialchars($service['service_name']) ?>" style="width:110px;height:110px;object-fit:cover;border-radius:14px;margin-bottom:1rem;">
                    <?php endif; ?>
                    <h3 style="color:#9c6b53;font-size:1.5rem;font-weight:bold;margin-bottom:0.5rem;"><?= htmlspecialchars($service['service_name']) ?></h3>
                    <?php if (!empty($service['service_duration'])): ?>
                        <div style="color:#9c6b53;font-size:1.05rem;margin-bottom:0.5rem;">
                            <i class="fa fa-clock"></i> <?= htmlspecialchars($service['service_duration']) ?> minit
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($service['service_description'])): ?>
                        <p style="color:#444;margin-bottom:1.5rem;"><?= htmlspecialchars($service['service_description']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($service['service_price'])): ?>
                        <div style="font-weight:bold;font-size:1.2rem;">RM <?= number_format($service['service_price'], 2) ?></div>
                    <?php endif; ?>
                </div>
                <a href="/public/index.php?service_id=<?= urlencode($service['service_id']) ?>#booking-form" style="margin-top:2rem;background:#9c6b53;color:#fff;padding:1rem 0;border-radius:30px;text-align:center;font-weight:bold;font-size:1.1rem;text-decoration:none;">Book Now</a>
            </div>
        <?php endforeach; ?>
    </div>
</div> 