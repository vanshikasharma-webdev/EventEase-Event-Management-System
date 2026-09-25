<?php
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<style>
    .contact-section {
        padding: 60px 0;
    }

    .contact-heading {
        font-weight: 700;
        margin-bottom: 12px;
    }

    .contact-intro {
        max-width: 650px;
        margin: 0 auto 40px;
        color: #6c757d;
    }

    .contact-card {
        height: 100%;
        padding: 25px;
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
    }

    .contact-card:hover {
        transform: translateY(-5px);
    }

    .contact-icon {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #f0eaff;
        color: #6f42c1;
        font-size: 23px;
        margin-bottom: 18px;
    }

    .contact-card h5 {
        font-weight: 600;
        margin-bottom: 10px;
    }

    .contact-card p,
    .contact-card a {
        color: #6c757d;
        margin-bottom: 0;
        text-decoration: none;
        overflow-wrap: anywhere;
    }

    .contact-card a:hover {
        color: #6f42c1;
    }

    .contact-form-card {
        border-radius: 18px;
        padding: 35px;
        background: #fff;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
    }

    .contact-form-card h2 {
        font-weight: 700;
        margin-bottom: 10px;
    }

    .contact-form-card .form-label {
        font-weight: 500;
    }

    .contact-form-card .form-control,
    .contact-form-card .form-select {
        padding: 12px 14px;
        border-radius: 9px;
    }

    .contact-form-card .form-control:focus,
    .contact-form-card .form-select:focus {
        border-color: #9b7be0;
        box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.12);
    }

    .contact-submit-btn {
        background: #6f42c1;
        color: #fff;
        border: none;
        padding: 12px 28px;
        border-radius: 9px;
        font-weight: 600;
    }

    .contact-submit-btn:hover {
        background: #59339d;
        color: #fff;
    }

    @media (max-width: 576px) {
        .contact-section {
            padding: 35px 0;
        }

        .contact-form-card {
            padding: 22px;
        }
    }
</style>

<section class="contact-section">
    <div class="container">

        <!-- Page Heading -->
        <div class="text-center">
            <h1 class="contact-heading">Contact Us</h1>

            <p class="contact-intro">
                Have a question about an event or your booking?
                We are here to help. Get in touch with us using
                the contact details below.
            </p>
        </div>

        <!-- Contact Information -->
        <div class="row g-4 mb-5">

            <!-- Email -->
            <div class="col-lg-4 col-md-6">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="bi bi-envelope-fill"></i>
                    </div>

                    <h5>Email Us</h5>

                    <p>
                        <a href="mailto:YOUR_EMAIL@example.com">
                            YOUR_EMAIL@example.com
                        </a>
                    </p>
                </div>
            </div>

            <!-- Phone -->
            <div class="col-lg-4 col-md-6">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </div>

                    <h5>Call Us</h5>

                    <p>
                        <a href="tel:+91XXXXXXXXXX">
                            +91 XXXXXXXXXX
                        </a>
                    </p>
                </div>
            </div>

            <!-- Location -->
            <div class="col-lg-4 col-md-6">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>

                    <h5>Our Location</h5>

                    <p>
                        YOUR CITY, YOUR STATE
                    </p>
                </div>
            </div>

        </div>

        <!-- Contact Form -->
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <div class="contact-form-card">

                    <h2>Send Us a Message</h2>

                    <p class="text-secondary mb-4">
                        Have a query, suggestion, or feedback?
                        Share your message with us.
                    </p>

                    <form method="post">

                        <div class="row g-3">

                            <!-- Name -->
                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    Full Name
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    placeholder="Enter your full name"
                                    required
                                >
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    Email Address
                                </label>

                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    placeholder="Enter your email address"
                                    required
                                >
                            </div>

                            <!-- Subject -->
                            <div class="col-12">
                                <label for="subject" class="form-label">
                                    Subject
                                </label>

                                <select
                                    class="form-select"
                                    id="subject"
                                    name="subject"
                                    required
                                >
                                    <option value="" selected disabled>
                                        Select a subject
                                    </option>

                                    <option value="Event Enquiry">
                                        Event Enquiry
                                    </option>

                                    <option value="Booking Related">
                                        Booking Related
                                    </option>

                                    <option value="Payment Related">
                                        Payment Related
                                    </option>

                                    <option value="Feedback">
                                        Feedback
                                    </option>

                                    <option value="Other">
                                        Other
                                    </option>
                                </select>
                            </div>

                            <!-- Message -->
                            <div class="col-12">
                                <label for="message" class="form-label">
                                    Your Message
                                </label>

                                <textarea
                                    class="form-control"
                                    id="message"
                                    name="message"
                                    rows="5"
                                    placeholder="Write your message here..."
                                    required
                                ></textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12 mt-4">
                                <button
                                    type="submit"
                                    class="btn contact-submit-btn"
                                    disabled
                                >
                                    <i class="bi bi-send-fill me-2"></i>
                                    Send Message
                                </button>

                                <p class="small text-secondary mt-3 mb-0">
                                    The message submission feature will be
                                    available once the contact form backend
                                    is connected.
                                </p>
                            </div>

                        </div>
                    </form>

                </div>

            </div>
        </div>

    </div>
</section>

<?php
require_once '../includes/footer.php';
?>