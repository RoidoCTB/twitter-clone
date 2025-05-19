<?php
require_once 'Database.php';  
require_once 'Handler.php';   

$pdo = Database::getInstance()->getConnection(); 
$userHandler = new UserHandler($pdo);           

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;  // Get user ID from query string safely
$user = $userHandler->getUserById($user_id);              // Fetch user data by ID

if (!$user) {
    die("User does not exist.");  // Stop if user not found
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Profile</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 col-md-6">
      <h2 class="mb-3 text-center">Edit Profile</h2>
      <form action="code.php" method="post">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" name="username" class="form-control" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">New Password</label>
          <input type="password" name="password" class="form-control" id="password" required>
        </div>
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        <input type="hidden" name="old_username" value="<?php echo htmlspecialchars($user['username']); ?>">
        <button type="submit" name="update" class="btn btn-primary w-100">Update</button>
      </form>

      <!-- Back Button -->
      <a href="index.php?user=<?php echo urlencode($user['username']); ?>" class="btn btn-secondary w-100 mt-3">Back</a>
    </div>
  </div>
</body>
</html>

