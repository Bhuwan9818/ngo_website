<?php require_once 'villages_data.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Become a Member – Sahara Foundation</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="membership.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>

<!-- ===== NAVBAR ===== -->
<header class="navbar" id="navbar">
  <div class="container nav-inner">
    <a href="index.html" class="logo">
      <img src="logo.jpeg" alt="" class="logo-icon" />
    </a>
    <nav class="nav-links" id="navLinks">
      <a href="index.html">Home</a>
      <a href="about.html">About Us</a>
      <a href="index.html#causes">Our Causes</a>
      <a href="index.html#impact">Impact</a>
      <a href="membership.php" class="active">Membership</a>
      <a href="index.html#contact">Contact</a>
    </nav>
    <a href="index.html#donate" class="btn btn-primary nav-donate">Donate Now</a>
    <button class="hamburger" id="hamburger" aria-label="Toggle menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>

<div class="mobile-menu" id="mobileMenu">
  <a href="index.html">Home</a>
  <a href="about.html">About Us</a>
  <a href="index.html#causes">Our Causes</a>
  <a href="index.html#impact">Impact</a>
  <a href="membership.php">Membership</a>
  <a href="index.html#contact">Contact</a>
</div>

<!-- ===== MEMBERSHIP HERO ===== -->
<section class="member-hero">
  <div class="container">
    <span class="section-label" style="color:#fff;">Join The Movement</span>
    <h1>Become a <span class="text-accent">Member</span></h1>
    <p>Register as a member of Sahara Foundation and join hands with us to bring change to villages across Namakkal and Salem districts.</p>
  </div>
</section>

<!-- ===== MEMBERSHIP FORM ===== -->
<section class="member-section">
  <div class="container member-wrap">

    <div class="member-form-card">
      <h3><i class="fa-solid fa-user-plus"></i> Membership Registration Form</h3>
      <p class="member-subtext">Fill in your details below. Fields marked <span class="req">*</span> are required.</p>

      <!-- Success / Error message box -->
      <div id="formMessage" class="form-message" style="display:none;"></div>

      <form id="membershipForm" autocomplete="off">

        <label class="form-label">Full Name <span class="req">*</span></label>
        <input type="text" name="full_name" class="form-input" placeholder="Enter your full name" required />

        <label class="form-label">Email Address <span class="req">*</span></label>
        <input type="email" name="email" class="form-input" placeholder="you@example.com" required />

        <label class="form-label">Phone Number <span class="req">*</span></label>
        <input type="tel" name="phone" class="form-input" placeholder="10-digit mobile number" pattern="[0-9]{10}" maxlength="10" required />

        <label class="form-label">State</label>
        <input type="text" name="state" class="form-input" value="Delhi" readonly />

        <label class="form-label">Location <span class="req">*</span></label>
        <select name="location" id="location" class="form-input" required>
          <option value="" selected disabled>-- Select Location --</option>
          <?php foreach ($delhi_locations as $loc): ?>
            <option value="<?php echo htmlspecialchars($loc); ?>"><?php echo htmlspecialchars($loc); ?></option>
          <?php endforeach; ?>
        </select>

        <label class="form-label">District <span class="req">*</span></label>
        <select name="district" id="district" class="form-input" required>
          <option value="" selected disabled>-- Select District --</option>
          <?php foreach ($villages_data as $district => $villages): ?>
            <option value="<?php echo htmlspecialchars($district); ?>"><?php echo htmlspecialchars($district); ?></option>
          <?php endforeach; ?>
        </select>

        <label class="form-label">Village <span class="req">*</span></label>
        <select name="village" id="village" class="form-input" required>
          <option value="" selected disabled>-- Select District First --</option>
        </select>

        <button type="submit" class="btn btn-primary btn-full" id="submitBtn">
          <i class="fa-solid fa-paper-plane"></i> Submit Registration
        </button>
      </form>
    </div>

    <div class="member-info">
      <span class="section-label">Why Join</span>
      <h2>Be Part Of <span class="text-green">Real Change</span></h2>
      <ul class="member-benefits">
        <li><i class="fa-solid fa-check-circle"></i> Get a unique Membership / Registration ID</li>
        <li><i class="fa-solid fa-check-circle"></i> Receive updates on village-level programs</li>
        <li><i class="fa-solid fa-check-circle"></i> Opportunities to volunteer in your district</li>
        <li><i class="fa-solid fa-check-circle"></i> Confirmation sent instantly to your email</li>
      </ul>
      <div class="member-note">
        <i class="fa-solid fa-circle-info"></i>
        <p>After submitting, you'll receive a confirmation email with your Registration ID. Please keep it safe for future reference.</p>
      </div>
    </div>

  </div>
</section>

<!-- ===== FOOTER ===== -->
<footer class="footer">
  <div class="footer-bottom">
    <div class="container footer-bottom-inner">
      <span>© 2025 Sahara Foundation. All rights reserved.</span>
      <span>Made with <i class="fa-solid fa-heart" style="color:#e74c3c;"></i> for Humanity</span>
    </div>
  </div>
</footer>

<script src="script.js"></script>
<script>
  // District -> Villages data, passed from PHP to JS
  const villagesData = <?php echo json_encode($villages_data); ?>;

  const districtSelect = document.getElementById('district');
  const villageSelect = document.getElementById('village');

  districtSelect.addEventListener('change', function () {
    const district = this.value;
    villageSelect.innerHTML = '';

    if (!district || !villagesData[district]) {
      villageSelect.innerHTML = '<option value="" selected disabled>-- Select District First --</option>';
      return;
    }

    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.disabled = true;
    placeholder.selected = true;
    placeholder.textContent = '-- Select Village --';
    villageSelect.appendChild(placeholder);

    villagesData[district].forEach(function (village) {
      const opt = document.createElement('option');
      opt.value = village;
      opt.textContent = village;
      villageSelect.appendChild(opt);
    });
  });

  // ===== AJAX Form Submission =====
  const form = document.getElementById('membershipForm');
  const formMessage = document.getElementById('formMessage');
  const submitBtn = document.getElementById('submitBtn');

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Submitting...';

    const formData = new FormData(form);

    fetch('submit_membership.php', {
      method: 'POST',
      body: formData
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        formMessage.style.display = 'block';
        if (data.status === 'success') {
          formMessage.className = 'form-message success';
          formMessage.innerHTML =
            '<i class="fa-solid fa-circle-check"></i> Registration successful! Your Registration ID is <strong>' +
            data.registration_id +
            '</strong>. A confirmation email has been sent to you.';
          form.reset();
          villageSelect.innerHTML = '<option value="" selected disabled>-- Select District First --</option>';
        } else {
          formMessage.className = 'form-message error';
          formMessage.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ' + data.message;
        }
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Submit Registration';
        formMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
      })
      .catch(function () {
        formMessage.style.display = 'block';
        formMessage.className = 'form-message error';
        formMessage.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Something went wrong. Please try again.';
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Submit Registration';
      });
  });
</script>
</body>
</html>
