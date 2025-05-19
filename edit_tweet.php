<?php
require_once 'Database.php';
require_once 'Handler.php';

$pdo = Database::getInstance()->getConnection();
$tweetHandler = new TweetHandler($pdo);

// Get the tweet ID from the URL
$tweet_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$tweet = $tweetHandler->getTweetById($tweet_id);

// Ensure the tweet exists before continuing
if (!$tweet) {
    die("Tweet does not exist.");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Tweet</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 col-md-6">
      <h2 class="mb-3 text-center">Edit Tweet</h2>
      <form action="code.php" method="post">
        <input type="hidden" name="id" value="<?php echo $tweet['id']; ?>">
        <div class="mb-3">
          <label for="tweet-body" class="form-label">Update your tweet</label>
          <textarea name="tweet-body" class="form-control" id="tweet-body" rows="4" required><?php echo htmlspecialchars($tweet['text']); ?></textarea>
        </div>
        <button type="submit" name="update_tweet" class="btn btn-primary w-100">Update</button>
      </form>
      <!-- Back Button -->
      <a href="index.php?user=<?php echo urlencode($tweet['username']); ?>" class="btn btn-secondary w-100 mt-3">Back</a>
    </div>
  </div>
</body>
</html>
