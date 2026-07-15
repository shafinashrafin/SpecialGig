<section style="padding:4rem 0">
    <div class="container" style="max-width:700px">
        <div style="text-align:center;margin-bottom:3rem">
            <h1 style="font-size:2.5rem;margin-bottom:.5rem">Contact Us</h1>
            <p style="color:#64748b">Have a question? We'd love to hear from you</p>
        </div>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="/contact">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem">
                        <div class="form-group">
                            <label class="form-label">Your Name</label>
                            <input type="text" name="name" class="form-input" required placeholder="John Doe">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Your Email</label>
                            <input type="email" name="email" class="form-input" required placeholder="john@example.com">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-input" placeholder="How can we help?">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-textarea" required placeholder="Your message..." style="min-height:150px"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                </form>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:2rem;text-align:center">
            <div>
                <i class="fas fa-envelope" style="font-size:1.5rem;color:#6366f1;margin-bottom:.5rem"></i>
                <p style="font-weight:600;font-size:.875rem">Email</p>
                <p style="font-size:.8125rem;color:#64748b">support@specialgig.com</p>
            </div>
            <div>
                <i class="fas fa-comment" style="font-size:1.5rem;color:#6366f1;margin-bottom:.5rem"></i>
                <p style="font-weight:600;font-size:.875rem">Live Chat</p>
                <p style="font-size:.8125rem;color:#64748b">Coming soon</p>
            </div>
            <div>
                <i class="fas fa-question-circle" style="font-size:1.5rem;color:#6366f1;margin-bottom:.5rem"></i>
                <p style="font-weight:600;font-size:.875rem">FAQ</p>
                <p style="font-size:.8125rem;color:#64748b"><a href="/faq" style="color:#6366f1;text-decoration:none">Browse FAQ</a></p>
            </div>
        </div>
    </div>
</section>
