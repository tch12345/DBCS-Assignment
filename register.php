<?php
require "Required/header.php";
?>
<section id="register" class="position-relative padding-large overflow-hidden">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card p-4 shadow-sm rounded-4">
          <div class="card-body">
            <h3 class="mb-4 text-uppercase text-center">Create an Account</h3>

            <form id="register-form">
              <div class="mb-3">
                <label for="fullname" class="form-label text-uppercase">Full Name</label>
                <input type="text" class="form-control shadow-none" id="fullname" placeholder="Enter your full name" required>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label text-uppercase">Email Address</label>
                <input type="email" class="form-control shadow-none" id="email" placeholder="you@example.com" required>
              </div>

              <div class="mb-3">
                <label for="password" class="form-label text-uppercase">Password</label>
                <input type="password" class="form-control shadow-none" id="password" placeholder="********" required>
              </div>

              <div class="mb-3">
                <label for="confirm-password" class="form-label text-uppercase">Confirm Password</label>
                <input type="password" class="form-control shadow-none" id="confirm-password" placeholder="********" required>
              </div>

              <div class="text-end">
                <button type="submit" class="btn btn-dark px-4 text-uppercase">Register</button>
              </div>

              <div class="mt-3 text-center">
                <p class="mb-0">Already have an account? <a href="login.php" class="text-dark">Login</a></p>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
require "Required/footer.php";
?>
