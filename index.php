<?php
require_once 'Database.php';
require_once 'Handler.php';

// Get PDO connection and instantiate our handlers.
$pdo = Database::getInstance()->getConnection();
$userHandler = new UserHandler($pdo);
$tweetHandler = new TweetHandler($pdo);

$username = isset($_GET['user']) ? $_GET['user'] : '';
$user = $userHandler->getUserByUsername($username);

if (!$user) {
    die("User does not exist.");
}

$tweets = $tweetHandler->getAllTweets();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <!-- Navbar with Edit Profile and Sign Out buttons -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand" href="#">
        <i class="fa-brands fa-twitter"> Clone </i>
      </a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="edit.php?id=<?php echo htmlspecialchars($user['id']); ?>">Edit Profile</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login.php">Sign Out</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  
  <!-- Main Container -->
  <div class="container mt-5 pt-3">
    <h2 class="mb-4 text-center">Welcome, <?php echo htmlspecialchars($user['username']); ?></h2>
    
    <!-- Tweet Form -->
    <div class="card mb-4">
      <div class="card-body">
        <form action="code.php" method="post">
          <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
          <div class="mb-3">
            <textarea name="tweet-body" class="form-control" rows="3" placeholder="What's happening?" required></textarea>
          </div>
          <div class="d-flex justify-content-end">
            <button type="submit" name="tweet" class="btn btn-primary">Tweet</button>
          </div>
        </form>
      </div>
    </div>
    
    <!-- Tweets List -->
    <h3 class="mb-3">Tweets</h3>
    <?php if (!empty($tweets)): ?>
      <?php foreach ($tweets as $tweet): ?>
        <div class="card mb-3 bg-primary text-white">
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($tweet['username']); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars($tweet['text']); ?></p>
            <?php if ($tweet['username'] == $user['username']): ?>
              <div class="d-flex justify-content-end">
              <a href="edit_tweet.php?id=<?php echo $tweet['id']; ?>" class="btn btn-warning btn-sm me-2">
  <i class="fas fa-edit"></i> Edit
</a>
                <form action="code.php" method="post" onsubmit="return confirm('Are you sure you want to delete this tweet?');" class="d-inline">
                  <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                  <button type="submit" name="delete" value="<?php echo $tweet['id']; ?>" class="btn btn-danger btn-sm">Delete</button>
                </form>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="alert alert-info" role="alert">
        No tweets available.
      </div>
    <?php endif; ?>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



