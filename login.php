<?php
session_start();
include 'config/koneksi.php';

if (isset($_POST['login'])) {
  $username = $koneksi->real_escape_string($_POST['username']);
  $password = md5($_POST['password']);

  $query = "SELECT * FROM users WHERE username='$username' AND password='$password';";
  $result = mysqli_query($koneksi, $query);

  if (mysqli_num_rows($result) > 0) {
    $_SESSION['username'] = $username;
    header('Location: admin.php');
    exit();
  } else {
    $error_message = "Username atau password salah.";
  }
}
?>

<?php include 'templates/header.php'; ?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-5">
      <div class="card product-card">
        <div class="card-body p-5">
          <h2 class="card-title text-center mb-4">Admin Login</h2>
          <?php if(isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
              <?= $error_message ?>
            </div>
          <?php endif; ?>
          <form action="" method="POST">
            <div class="mb-4">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control form-control-lg" id="username" name="username" required>
            </div>
            <div class="mb-4">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control form-control-lg" id="password" name="password" required>
            </div>
            <div class="d-grid">
              <button type="submit" name="login" class="btn btn-primary btn-lg">Login</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'templates/footer.php'; ?>