<section style="padding:4rem 0">
    <div class="container" style="max-width:800px">
        <div style="text-align:center;margin-bottom:3rem">
            <h1 style="font-size:2.5rem;margin-bottom:.5rem">Frequently Asked Questions</h1>
            <p style="color:#64748b">Find answers to common questions</p>
        </div>
        <div style="display:flex;flex-direction:column;gap:.75rem">
            <?php if (empty($faqs)): ?>
            <p style="text-align:center;color:#94a3b8;padding:2rem">No FAQs available yet.</p>
            <?php else: ?>
            <?php foreach ($faqs as $faq): ?>
            <div style="background:#fff;border:1px solid #f1f5f9;border-radius:.75rem;overflow:hidden" x-data="{ open: false }">
                <button @click="open = !open" style="width:100%;padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;background:none;border:none;cursor:pointer;font-weight:600;font-size:.875rem;color:#0f172a;text-align:left">
                    <span><?= e($faq->question) ?></span>
                    <i class="fas fa-chevron-down" style="color:#94a3b8;transition:transform .3s" :class="{ 'rotate-180': open }"></i>
                </button>
                <div x-show="open" style="padding:0 1.25rem 1rem;font-size:.875rem;color:#64748b;line-height:1.7">
                    <?= $faq->answer ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
