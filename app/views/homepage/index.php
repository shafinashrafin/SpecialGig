<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-content">
        <div style="max-width:800px">
            <p style="color:#818cf8;font-weight:600;font-size:.875rem;text-transform:uppercase;letter-spacing:.1em;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem">
                <span style="display:inline-flex;gap:.25rem"><span style="width:.375rem;height:.375rem;border-radius:50%;background:#818cf8;animation:pulse 2s infinite"></span> Premium Micro Job Marketplace</span>
            </p>
            <h1 class="hero-title">Earn Money Online<br><span>Completing Simple Tasks</span></h1>
            <p class="hero-subtitle">Join thousands of workers earning real income by completing micro jobs. Businesses find talent, workers earn rewards. Simple, secure, and fast.</p>
            <form action="/jobs/browse" method="GET" class="hero-search">
                <i class="fas fa-search" style="color:rgba(255,255,255,.25);margin-left:.5rem"></i>
                <input type="text" name="q" placeholder="Search for jobs..." value="<?= e($_GET['q'] ?? '') ?>">
                <select name="category" style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.1);padding:.625rem 1rem;border-radius:.5rem;color:rgba(255,255,255,.7);font-size:.875rem;min-width:160px">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat->id ?>"><?= e($cat->name) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary">Search Jobs</button>
            </form>
            <div style="display:flex;align-items:center;gap:2rem;margin-top:2rem;flex-wrap:wrap">
                <div style="display:flex;align-items:center;gap:.5rem">
                    <div style="display:flex">
                        <img src="https://ui-avatars.com/api/?name=JD&size=32&background=6366f1&color=fff" style="width:2rem;height:2rem;border-radius:50%;border:2px solid #0f172a;margin-right:-.5rem">
                        <img src="https://ui-avatars.com/api/?name=SK&size=32&background=8b5cf6&color=fff" style="width:2rem;height:2rem;border-radius:50%;border:2px solid #0f172a;margin-right:-.5rem">
                        <img src="https://ui-avatars.com/api/?name=AR&size=32&background=a78bfa&color=fff" style="width:2rem;height:2rem;border-radius:50%;border:2px solid #0f172a;margin-right:-.5rem">
                        <img src="https://ui-avatars.com/api/?name=MT&size=32&background=c084fc&color=fff" style="width:2rem;height:2rem;border-radius:50%;border:2px solid #0f172a">
                    </div>
                    <span style="color:rgba(255,255,255,.5);font-size:.875rem">Trusted by <strong style="color:#fff">10,000+</strong> users</span>
                </div>
                <a href="/how-it-works" style="color:rgba(255,255,255,.6);font-size:.875rem;display:flex;align-items:center;gap:.5rem;text-decoration:none">
                    Learn how it works <i class="fas fa-arrow-right" style="font-size:.75rem"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background:#eef2ff;color:#6366f1"><i class="fas fa-briefcase"></i></div>
                <div>
                    <div class="stat-value"><?= number_format($stats['active_jobs']) ?></div>
                    <div class="stat-label">Active Jobs</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#dbeafe;color:#3b82f6"><i class="fas fa-users"></i></div>
                <div>
                    <div class="stat-value"><?= number_format($stats['workers']) ?></div>
                    <div class="stat-label">Workers</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#d1fae5;color:#10b981"><i class="fas fa-user-tie"></i></div>
                <div>
                    <div class="stat-value"><?= number_format($stats['buyers']) ?></div>
                    <div class="stat-label">Buyers</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#fef3c7;color:#f59e0b"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-value"><?= number_format($stats['completed_jobs']) ?></div>
                    <div class="stat-label">Completed Jobs</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#ede9fe;color:#8b5cf6"><i class="fas fa-dollar-sign"></i></div>
                <div>
                    <div class="stat-value">$<?= number_format($stats['total_paid']) ?></div>
                    <div class="stat-label">Total Paid</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#fce7f3;color:#ec4899"><i class="fas fa-globe"></i></div>
                <div>
                    <div class="stat-value"><?= number_format($stats['countries']) ?></div>
                    <div class="stat-label">Countries</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
<section style="padding:4rem 0">
    <div class="container">
        <div style="text-align:center;margin-bottom:3rem">
            <p style="color:#6366f1;font-weight:600;font-size:.875rem;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem">Categories</p>
            <h2>Browse Jobs by Category</h2>
            <p style="color:#64748b;margin-top:.5rem">Find micro jobs in your preferred category</p>
        </div>
        <div class="categories-grid">
            <?php foreach ($categories as $cat): ?>
            <a href="/jobs/browse?category=<?= $cat->id ?>" class="category-card">
                <div class="cat-icon" style="background:#eef2ff;color:#6366f1">
                    <i class="fas fa-<?= e($cat->icon ?? 'layer-group') ?>"></i>
                </div>
                <span><?= e($cat->name) ?></span>
                <span class="cat-count"><?= $cat->job_count ?? 0 ?> jobs</span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Jobs -->
<?php if (!empty($featuredJobs)): ?>
<section style="padding:4rem 0;background:#f8fafc">
    <div class="container">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem">
            <div>
                <p style="color:#6366f1;font-weight:600;font-size:.875rem;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem">Featured Jobs</p>
                <h2>Latest Opportunities</h2>
            </div>
            <a href="/jobs/browse" class="btn btn-outline">View All Jobs <i class="fas fa-arrow-right" style="font-size:.75rem"></i></a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(350px,1fr));gap:1rem">
            <?php foreach ($featuredJobs as $job): ?>
            <div class="job-card">
                <div style="display:flex;align-items:flex-start;justify-content:space-between">
                    <div style="display:flex;align-items:center;gap:.75rem">
                        <img src="<?= get_avatar($job) ?>" alt="" class="avatar">
                        <div>
                            <p style="font-weight:600;font-size:.875rem;color:#0f172a"><?= e($job->full_name ?: $job->username) ?></p>
                            <p style="font-size:.75rem;color:#94a3b8"><?= e($job->category_name) ?></p>
                        </div>
                    </div>
                    <?php if ($job->is_featured): ?>
                        <span class="badge" style="background:#eef2ff;color:#6366f1;font-size:.6875rem">Featured</span>
                    <?php endif; ?>
                </div>
                <h3 style="font-size:1rem;font-weight:700;color:#0f172a;margin-top:.5rem">
                    <a href="/jobs/<?= e($job->slug) ?>" style="text-decoration:none;color:inherit"><?= e($job->title) ?></a>
                </h3>
                <div class="reward"><?= format_currency($job->reward) ?> <span style="font-size:.875rem;font-weight:500;color:#94a3b8">per task</span></div>
                <div class="job-meta">
                    <span><i class="far fa-clock"></i> <?= $job->completion_time_limit ? $job->completion_time_limit . 'h' : 'Flexible' ?></span>
                    <span><i class="fas fa-users"></i> <?= $job->available_slots - $job->filled_slots ?> slots left</span>
                    <span class="badge <?= get_difficulty_color($job->difficulty) ?>"><?= ucfirst($job->difficulty) ?></span>
                    <?php if ($job->country_restriction): ?>
                        <span><i class="fas fa-globe"></i> <?= e($job->country_restriction) ?></span>
                    <?php endif; ?>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:auto;padding-top:.75rem;border-top:1px solid #f1f5f9">
                    <div class="stars"><?= get_stars(round($job->rating)) ?></div>
                    <a href="/jobs/<?= e($job->slug) ?>" class="btn btn-primary btn-sm">Apply Now</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- How It Works -->
<section style="padding:4rem 0">
    <div class="container">
        <div style="text-align:center;margin-bottom:3rem">
            <p style="color:#6366f1;font-weight:600;font-size:.875rem;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem">How It Works</p>
            <h2>Getting Started is Easy</h2>
            <p style="color:#64748b;margin-top:.5rem">Follow these simple steps to begin your journey</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem">
            <div class="step-card">
                <div class="step-number">1</div>
                <h3 style="font-size:1rem;font-weight:700;margin-bottom:.5rem">Create Account</h3>
                <p style="font-size:.875rem;color:#64748b;line-height:1.6">Sign up as a Buyer or Worker in seconds. Free registration with email verification.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h3 style="font-size:1rem;font-weight:700;margin-bottom:.5rem">Fund or Browse</h3>
                <p style="font-size:.875rem;color:#64748b;line-height:1.6">Buyers deposit funds. Workers browse thousands of available micro jobs.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h3 style="font-size:1rem;font-weight:700;margin-bottom:.5rem">Work & Earn</h3>
                <p style="font-size:.875rem;color:#64748b;line-height:1.6">Workers complete tasks. Buyers review and approve submitted proof.</p>
            </div>
            <div class="step-card">
                <div class="step-number">4</div>
                <h3 style="font-size:1rem;font-weight:700;margin-bottom:.5rem">Get Paid</h3>
                <p style="font-size:.875rem;color:#64748b;line-height:1.6">Earnings are released instantly. Withdraw via PayPal, Stripe, bKash, and more.</p>
            </div>
        </div>
    </div>
</section>

<!-- Top Workers -->
<?php if (!empty($topWorkers)): ?>
<section style="padding:4rem 0;background:#f8fafc">
    <div class="container">
        <div style="text-align:center;margin-bottom:3rem">
            <p style="color:#6366f1;font-weight:600;font-size:.875rem;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem">Top Workers</p>
            <h2>Our Highest Earners</h2>
            <p style="color:#64748b;margin-top:.5rem">Meet our top-performing workers</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.5rem">
            <?php foreach ($topWorkers as $i => $worker): ?>
            <div style="text-align:center;padding:1.5rem;background:#fff;border-radius:1rem;border:1px solid #f1f5f9;transition:all .3s;position:relative">
                <div style="width:2rem;height:2rem;border-radius:50%;background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.75rem;position:absolute;top:-.5rem;left:-.5rem"><?= $i + 1 ?></div>
                <img src="<?= get_avatar($worker, 80) ?>" alt="" style="width:4rem;height:4rem;border-radius:50%;margin:0 auto .75rem;object-fit:cover">
                <h4 style="font-size:.875rem;font-weight:700"><?= e($worker->full_name ?: $worker->username) ?></h4>
                <p style="font-size:.75rem;color:#94a3b8"><?= $worker->jobs_done ?? 0 ?> jobs done</p>
                <div class="stars" style="justify-content:center;margin:.5rem 0"><?= get_stars(round($worker->avg_rating ?? 0)) ?></div>
                <p style="font-size:1rem;font-weight:700;color:#6366f1">$<?= number_format($worker->earnings ?? 0, 0) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Testimonials -->
<section style="padding:4rem 0">
    <div class="container">
        <div style="text-align:center;margin-bottom:3rem">
            <p style="color:#6366f1;font-weight:600;font-size:.875rem;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem">Testimonials</p>
            <h2>What Our Users Say</h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem">
            <div style="padding:2rem;background:#fff;border-radius:1rem;border:1px solid #f1f5f9">
                <div class="stars" style="margin-bottom:1rem"><?= get_stars(5) ?></div>
                <p style="font-size:.875rem;color:#64748b;line-height:1.7;margin-bottom:1rem">"SpecialGig has completely changed how I earn online. The tasks are simple and the payments are always on time. I've made over $2,000 in my first month!"</p>
                <div style="display:flex;align-items:center;gap:.75rem">
                    <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&size=40&background=6366f1&color=fff" style="width:2.5rem;height:2.5rem;border-radius:50%">
                    <div>
                        <p style="font-weight:600;font-size:.875rem">Sarah Johnson</p>
                        <p style="font-size:.75rem;color:#94a3b8">Top Worker</p>
                    </div>
                </div>
            </div>
            <div style="padding:2rem;background:#fff;border-radius:1rem;border:1px solid #f1f5f9">
                <div class="stars" style="margin-bottom:1rem"><?= get_stars(5) ?></div>
                <p style="font-size:.875rem;color:#64748b;line-height:1.7;margin-bottom:1rem">"As a business owner, I needed a reliable way to get micro tasks done. SpecialGig delivered exactly what I needed. The workers are professional and the platform is easy to use."</p>
                <div style="display:flex;align-items:center;gap:.75rem">
                    <img src="https://ui-avatars.com/api/?name=Mike+Chen&size=40&background=8b5cf6&color=fff" style="width:2.5rem;height:2.5rem;border-radius:50%">
                    <div>
                        <p style="font-weight:600;font-size:.875rem">Mike Chen</p>
                        <p style="font-size:.75rem;color:#94a3b8">Verified Buyer</p>
                    </div>
                </div>
            </div>
            <div style="padding:2rem;background:#fff;border-radius:1rem;border:1px solid #f1f5f9">
                <div class="stars" style="margin-bottom:1rem"><?= get_stars(5) ?></div>
                <p style="font-size:.875rem;color:#64748b;line-height:1.7;margin-bottom:1rem">"The referral program is amazing! I've earned extra income just by inviting my friends. The platform is secure, payments are fast, and the support team is very helpful."</p>
                <div style="display:flex;align-items:center;gap:.75rem">
                    <img src="https://ui-avatars.com/api/?name=Emily+Rodriguez&size=40&background=a78bfa&color=fff" style="width:2.5rem;height:2.5rem;border-radius:50%">
                    <div>
                        <p style="font-weight:600;font-size:.875rem">Emily Rodriguez</p>
                        <p style="font-size:.75rem;color:#94a3b8">Referral King</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<?php if (!empty($faqs)): ?>
<section style="padding:4rem 0;background:#f8fafc">
    <div class="container" style="max-width:800px">
        <div style="text-align:center;margin-bottom:3rem">
            <p style="color:#6366f1;font-weight:600;font-size:.875rem;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem">FAQ</p>
            <h2>Frequently Asked Questions</h2>
        </div>
        <div style="display:flex;flex-direction:column;gap:.75rem">
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
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Newsletter -->
<section style="padding:4rem 0;background:linear-gradient(135deg,#6366f1,#8b5cf6)">
    <div class="container" style="text-align:center;max-width:600px">
        <h2 style="color:#fff;font-size:2rem;margin-bottom:.75rem">Stay Updated</h2>
        <p style="color:rgba(255,255,255,.7);margin-bottom:2rem">Subscribe to our newsletter for new job alerts and platform updates</p>
        <form action="/newsletter" method="POST" style="display:flex;gap:.5rem;max-width:480px;margin:0 auto">
            <input type="email" name="email" placeholder="Enter your email address" required style="flex:1;padding:.875rem 1.25rem;border-radius:.75rem;border:none;font-size:.875rem;outline:none">
            <button type="submit" class="btn" style="background:#fff;color:#6366f1;font-weight:700">Subscribe</button>
        </form>
    </div>
</section>

<style>
    .rotate-180 { transform: rotate(180deg) }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.3} }
</style>
