<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container-fluid bg-light vh-100 d-flex justify-content-center align-items-center">
    <div class="card shadow-lg p-4 rounded" style="max-width: 400px; width: 100%;">
      <h2 class="text-center mb-3">Register</h2>
      <form action="code.php" method="post">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" name="username" class="form-control" id="username" required />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" class="form-control" id="password" required />
        </div>
        <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
      </form>
      <hr>
      <div class="mt-3 text-center">
        <p class="mb-0">Already have an account?</p>
        <a href="login.php" class="btn btn-outline-primary w-100 mt-2">Login</a>
      </div>
    </div>
  </div>
</body>
</html>

