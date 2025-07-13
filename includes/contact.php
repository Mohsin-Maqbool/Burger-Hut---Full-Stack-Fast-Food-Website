<!-- Contact Section -->
<section class="contact-order py-5 mt-5 my-5 bg-dark text-white" id="contact">
  <div class="container">
    <h2 class="text-center fw-bold display-5 mb-4" style="font-family: 'Lobster', cursive; color: #ffc107;">
      📞 Get in Touch or Order Now
    </h2>
    <div class="row g-4">

      <!-- Contact Form -->
      <div class="col-md-6">
        <form class="bg-light text-dark rounded p-4 shadow-lg" id="orderForm">
          <div class="mb-3">
            <label for="name" class="form-label fw-bold">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label fw-bold">Phone</label>
            <input type="tel" class="form-control" id="phone" name="phone" placeholder="03XX-XXXXXXX" required>
          </div>
          <div class="mb-3">
            <label for="message" class="form-label fw-bold">Message / Special Instructions</label>
            <textarea class="form-control" id="message" name="message" rows="4" placeholder="Any custom burger requests or notes?"></textarea>
          </div>
          <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold">🚀 Contact Us</button>
          <div id="formMessage" class="mt-3"></div>
        </form>
      </div>

      <!-- Map & Info -->
      <div class="col-md-6">
        <div class="map-wrapper rounded shadow-lg overflow-hidden mb-3">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d27224.168962272186!2d71.49126875!3d30.1574572!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x393b342823b6e5fb%3A0xc3f93f4bc4f6a1a!2sMultan!5e0!3m2!1sen!2s!4v1684205403771!5m2!1sen!2s"
            width="100%" height="280" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="d-flex flex-column align-items-start text-warning">
          <h5 class="fw-bold">📱 Phone: <a href="tel:+923014486345" class="text-warning">+92 301 4486345</a></h5>
          <h5 class="fw-bold mt-2">💬 WhatsApp: <a href="https://wa.me/923014486345" target="_blank" class="text-warning">Chat Now</a></h5>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- JavaScript to Handle Form Submission -->
<script>
document.getElementById('orderForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const name = document.getElementById('name').value.trim();
  const phone = document.getElementById('phone').value.trim();
  const message = document.getElementById('message').value.trim();

  fetch('process-order.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `name=${encodeURIComponent(name)}&phone=${encodeURIComponent(phone)}&message=${encodeURIComponent(message)}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      document.getElementById('formMessage').innerHTML = `<div class="alert alert-success">${data.msg}</div>`;
      document.getElementById('orderForm').reset();
      setTimeout(() => {
        window.open(data.whatsapp, '_blank');
      }, 1000);
    } else {
      document.getElementById('formMessage').innerHTML = `<div class="alert alert-danger">${data.msg}</div>`;
    }
  })
  .catch(error => {
    document.getElementById('formMessage').innerHTML = `<div class="alert alert-danger">Something went wrong. Please try again.</div>`;
  });
});
</script>
