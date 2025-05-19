<?php

class UserHandler {
    private $pdo;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
    // Register a new user (plain text password storage).
    public function register($username, $password) {
        // Check if the username already exists.
        $sql = "SELECT COUNT(*) as count FROM user WHERE username = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch();
        if ($row['count'] > 0) {
            return false; // Username taken.
        }

        // Insert the new user (NO HASHING).
        $sql = "INSERT INTO user (username, password) VALUES (:username, :password)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':username' => $username, ':password' => $password]);
    }
    
    // Attempt to log in a user (plain text password check).
    public function login($username, $password) {
        $sql = "SELECT * FROM user WHERE username = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        // Compare plain text password (NO HASHING).
        if ($user && $user['password'] === $password) {
            return $user;
        }
        return false;
    }
    
    // Update user details (NO HASHING).
    public function update($id, $username, $password, $oldUsername) {
        // Check that the new username isn’t already taken by another user.
        $sql = "SELECT COUNT(*) as count FROM user WHERE username = :username AND id <> :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':username' => $username, ':id' => $id]);
        $row = $stmt->fetch();
        if ($row['count'] > 0) {
            return false;
        }

        // Update the user's record (NO HASHING).
        $sql = "UPDATE user SET username = :username, password = :password WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([':username' => $username, ':password' => $password, ':id' => $id]);

        if ($result) {
            // Also update tweets to reflect the changed username.
            $sql = "UPDATE tweet SET username = :newUsername WHERE username = :oldUsername";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':newUsername' => $username, ':oldUsername' => $oldUsername]);
        }
        return false;
    }
    
    // Retrieve a user by ID.
    public function getUserById($id) {
        $sql = "SELECT * FROM user WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    // Retrieve a user by username.
    public function getUserByUsername($username) {
        $sql = "SELECT * FROM user WHERE username = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetch();
    }
}

class TweetHandler {
    private $pdo;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
    // Post a new tweet.
    public function postTweet($username, $text) {
        $sql = "INSERT INTO tweet (username, text) VALUES (:username, :text)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':username' => $username, ':text' => $text]);
    }
    
    // Delete a tweet by its id.
    public function deleteTweet($id) {
        $sql = "DELETE FROM tweet WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Get all tweets, ordered from newest to oldest.
    public function getAllTweets() {
        $sql = "SELECT * FROM tweet ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    
    // Get a specific tweet by ID.
    public function getTweetById($tweet_id) {
        $sql = "SELECT * FROM tweet WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $tweet_id]);
        return $stmt->fetch();
    }

    // Update a tweet's content.
    public function updateTweet($tweet_id, $text) {
        $sql = "UPDATE tweet SET text = :text WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':text' => $text, ':id' => $tweet_id]);
    }
}
?>

