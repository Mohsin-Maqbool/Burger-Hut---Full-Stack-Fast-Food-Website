// ------------------ Countdown Timer ------------------
function startCountdown(duration, display) {
  let timer = duration, hours, minutes, seconds;
  setInterval(() => {
    hours = Math.floor(timer / 3600);
    minutes = Math.floor((timer % 3600) / 60);
    seconds = timer % 60;
    display.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    if (--timer < 0) timer = 0;
  }, 1000);
}

// ------------------ Newsletter Form ------------------
function setupNewsletterForm() {
  const form = document.querySelector('.newsletter form');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      alert("🎉 You're subscribed! Watch your inbox for juicy burger updates.");
      this.reset();
    });
  }
}

// ------------------ Contact Order Form ------------------
function setupOrderForm() {
  const form = document.getElementById('orderForm');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const nameInput = document.getElementById('name');
      const name = nameInput ? nameInput.value : 'Customer';
      alert(`Thanks, ${name}! Your order has been received. We'll call you shortly.`);
      this.reset();
    });
  }
}

// ------------------ Navbar Scroll Active ------------------
function setupScrollSpy() {
  const sections = document.querySelectorAll('section');
  const navLinks = document.querySelectorAll('.nav-link');

  window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(section => {
      const sectionTop = section.offsetTop - 80;
      if (window.pageYOffset >= sectionTop) {
        current = section.getAttribute('id');
      }
    });

    navLinks.forEach(link => {
      link.classList.remove('active');
      if (link.getAttribute('href') === `#${current}`) {
        link.classList.add('active');
      }
    });
  });
}

// ------------------ Load More Products ------------------
function setupLoadMore() {
  let offset = 3;
  const loadMoreBtn = document.getElementById('loadMoreBtn');
  const menuContainer = document.querySelector('.menu .row');

  if (loadMoreBtn && menuContainer) {
    loadMoreBtn.addEventListener('click', function () {
      fetch(`fetchproduct.php?limit=3&offset=${offset}`)
        .then(res => res.json())
        .then(data => {
          if (!Array.isArray(data) || data.length === 0) {
            loadMoreBtn.disabled = true;
            loadMoreBtn.innerText = 'No More Products';
            return;
          }

          data.forEach(product => {
            const col = document.createElement('div');
            col.className = 'col-md-4 mb-4';
            col.innerHTML = `
              <div class="menu-card">
                <img src="uploads/${product.image}" alt="${product.name}" class="img-fluid">
                <h5>${product.name}</h5>
                <p>$${product.price}</p>
                <a href="fetchproduct.php?product_id=${product.id}" class="btn btn-danger">Add to Order</a>
              </div>
            `;
            menuContainer.appendChild(col);
          });

          offset += 3;
        })
        .catch(error => console.error('Error loading products:', error));
    });
  }
}

// ------------------ WhatsApp Auto Redirect ------------------
function autoRedirectWhatsApp() {
  const whatsappURL = window.whatsappURL || ''; // Define globally or in HTML before this script runs
  if (whatsappURL) {
    window.open(whatsappURL, '_blank');
  }
}

// ------------------ DOM Ready Setup ------------------
window.addEventListener("DOMContentLoaded", () => {
  const timer1 = document.getElementById("timer1");
  if (timer1) startCountdown(3600, timer1);

  setupNewsletterForm();
  setupOrderForm();
  setupScrollSpy();
  setupLoadMore();
});

// ------------------ Window Load (for WhatsApp only) ------------------
window.addEventListener("load", () => {
  autoRedirectWhatsApp();
});



// see more 

// let offset = 10; // already loaded 4 items
$('#see-more').click(function () {
  $.ajax({
    url: 'load-more.php',
    type: 'POST',
    data: { offset: offset },
    success: function (response) {
      $('#menu-items').append(response);

      // Animate newly added cards
      gsap.from(".menu-card", {
        y: 50,
        opacity: 0,
        duration: 0.6,
        stagger: 0.1
      });

      offset += 4;
    }
  })
});


// QR Scanner
function onScanSuccess(decodedText, decodedResult) {
    document.getElementById("qr-result").innerText = `Scanned: ${decodedText}`;
    
    // Redirect if the scanned code is a link
    if (decodedText.startsWith("http")) {
      window.location.href = decodedText;
    }

    // Stop scanner after successful scan
    html5QrcodeScanner.clear();
  }

  const html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", 
    { fps: 10, qrbox: 200 },
    false
  );
  html5QrcodeScanner.render(onScanSuccess);

  // testimonial

  gsap.from(".testimonials h2", {
    y: -30,
    opacity: 0,
    duration: 1,
    ease: "power2.out"
  });

  gsap.from(".testimonial-card", {
    opacity: 0,
    y: 40,
    stagger: 0.3,
    duration: 1.2,
    ease: "power4.out"
  });