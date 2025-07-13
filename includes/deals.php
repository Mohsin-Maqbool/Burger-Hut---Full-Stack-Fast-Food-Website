<section class="special-deals py-5 bg-warning bg-gradient" id="deals">
  <div class="container">
    <h2 class="text-center text-dark fw-bold display-5 mb-5" style="font-family: 'Lobster', cursive;">
      🔥 Hot Deals & Promotions 🔥
    </h2>
    <div class="row g-4">
      <!-- Deal 1 -->
      <div class="col-md-6">
        <div class="card text-center deal-card border-0 shadow-lg p-4 h-100" id="deal1">
          <div class="card-body">
            <h5 class="card-title fs-3 text-danger">Buy 1 Get 1 Free</h5>
            <p class="card-text text-dark">Order any Classic Burger and get another absolutely FREE. Today only!</p>
            <div class="countdown text-primary fw-bold fs-4" id="timer1">00:00:00</div>
            <div class="mt-3">
              <span class="badge bg-danger fs-6 p-2 rounded">Use Code: BOGO🔥</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Deal 2 with QR Code + Scanner -->
      <div class="col-md-6">
        <div class="card text-center deal-card border-0 shadow-lg p-4 h-100" id="deal2">
          <div class="card-body">
            <h5 class="card-title fs-3 text-danger">Free Fries with Every Meal</h5>
            <p class="card-text text-dark">Get crispy fries on the house when you order a Burger + Drink combo!</p>

            <!-- Static QR Code -->
            <img src="https://api.qrserver.com/v1/create-qr-code/?data=http://localhost/burgerhut_site/deal2.php&size=150x150" />


            <div class="mt-2">
              <span class="badge bg-dark text-warning fs-6 p-2 rounded">Scan to Redeem</span>
            </div>

            <!-- Live QR Scanner -->
            <div id="reader" style="width: 100%; margin-top: 20px;"></div>
            <div id="qr-result" class="text-success mt-3 fw-bold"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>