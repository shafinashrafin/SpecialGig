<div class="page-header">
    <h1 class="page-title" style="color:#fff">Settings</h1>
</div>

<div style="display:flex;gap:.5rem;margin-bottom:1.5rem;flex-wrap:wrap">
    <?php foreach (['general', 'payment', 'referral', 'security', 'appearance'] as $g): ?>
    <a href="/admin/settings?group=<?= $g ?>" class="btn <?= $currentGroup === $g ? 'btn-primary' : '' ?>" style="<?= $currentGroup === $g ? '' : 'background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);border:1px solid rgba(255,255,255,.1)' ?>"><?= ucfirst($g) ?></a>
    <?php endforeach; ?>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="/admin/settings?group=<?= $currentGroup ?>">
            <?php foreach ($settings as $setting): ?>
            <div class="form-group">
                <label class="form-label"><?= ucfirst(str_replace('_', ' ', $setting->key)) ?></label>
                <?php if ($setting->type === 'boolean'): ?>
                    <label class="form-switch">
                        <input type="checkbox" name="setting_<?= e($setting->key) ?>" value="1" <?= $setting->value == '1' ? 'checked' : '' ?>>
                        <span class="slider"></span>
                    </label>
                <?php elseif ($setting->type === 'text' && strlen($setting->value ?? '') > 100): ?>
                    <textarea name="setting_<?= e($setting->key) ?>" class="form-textarea"><?= e($setting->value ?? '') ?></textarea>
                <?php else: ?>
                    <input type="<?= $setting->type === 'number' ? 'number' : 'text' ?>" name="setting_<?= e($setting->key) ?>" class="form-input" value="<?= e($setting->value ?? '') ?>" step="<?= $setting->type === 'number' ? '0.01' : '' ?>">
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</div>
