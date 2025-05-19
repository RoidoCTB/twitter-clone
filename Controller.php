<?php
// Controller.php
require_once 'Database.php';
require_once 'Handler.php';

session_start(); // Start session for handling error messages

class Controller {
    private $pdo;
    private $userHandler;
    private $tweetHandler;
    
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
        $this->userHandler = new UserHandler($this->pdo);
        $this->tweetHandler = new TweetHandler($this->pdo);
    }
    
    public function handleRequest() {
        // Registration (No password hashing)
        if (isset($_POST["register"])) {
            $username = $_POST['username'];
            $password = $_POST['password']; // Store plain text password

            if (!$this->userHandler->register($username, $password)) {
                $_SESSION['register_error'] = "User already exists or registration failed.";
                header("Location: signup.php");
            } else {
                header("Location: login.php");
            }
            exit();
        }
        
        // Login (Session-based warning, plain text password comparison)
        if (isset($_POST["login"])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->userHandler->login($username, $password);

            if (!$user || $user['password'] !== $password) { // Direct string comparison (No Hashing)
                $_SESSION['login_error'] = "Incorrect username or password."; // Store error in session
                header("Location: login.php"); // Redirect back to login page
            } else {
                unset($_SESSION['login_error']); // Clear previous errors
                header("Location: index.php?user=" . urlencode($username));
            }
            exit();
        }
        
        // Posting a Tweet
        if (isset($_POST["tweet"])) {
            $username = $_POST['username'];
            $tweetBody = $_POST['tweet-body'];
            
            if (!$this->tweetHandler->postTweet($username, $tweetBody)) {
                echo "Tweet not created.";
            } else {
                header("Location: index.php?user=" . urlencode($username));
            }
            exit();
        }
        
        // Deleting a Tweet
        if (isset($_POST["delete"])) {
            $tweet_id = $_POST['delete'];
            $username = $_POST['username'];
            
            if (!$this->tweetHandler->deleteTweet($tweet_id)) {
                echo "Tweet not deleted!";
            } else {
                header("Location: index.php?user=" . urlencode($username));
            }
            exit();
        }
        
        // Updating User Profile (No password hashing)
        if (isset($_POST["update"])) {
            $username    = $_POST['username'];
            $password    = $_POST['password']; // Store plain text password
            $oldUsername = $_POST['old_username'];
            $user_id     = $_POST['id'];
            
            if (!$this->userHandler->update($user_id, $username, $password, $oldUsername)) {
                echo "Update failed or username already exists.";
            } else {
                header("Location: index.php?user=" . urlencode($username));
            }
            exit();
        }

        // Updating a Tweet
        if (isset($_POST["update_tweet"])) {
            $tweet_id = intval($_POST["id"]);
            $tweet_body = $_POST["tweet-body"];

            if (!$this->tweetHandler->updateTweet($tweet_id, $tweet_body)) {
                echo "Failed to update tweet.";
            } else {
                header("Location: index.php?user=" . urlencode($this->tweetHandler->getTweetById($tweet_id)['username']));
            }
            exit();
        }

        // If no action matches, return a warning.
        echo "No valid request found.";
    }
}
?>


