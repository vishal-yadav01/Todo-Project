<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $pass = $_POST['password'];

  if (strlen($name) < 3) {
    $msg = "Name must be at least 3 characters.";
  } elseif (strlen($pass) < 6) {
    $msg = "Password must be at least 6 characters.";
  } else {
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $db = new mysqli("localhost", "root", "", "todo_app");

    $st = $db->prepare("INSERT INTO users(name,email,password) VALUES(?,?,?)");
    $st->bind_param("sss", $name, $email, $hash);

    if ($st->execute()) {
      header("Location: login.php");
      exit;
    } else {
      $msg = "Failed (maybe duplicate email)";
    }
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Signup</title>
  <style>
    body {
      font-family: sans-serif;
      max-width: 400px;
      margin: 50px auto;
      padding: 15px;
      background: #f9f9f9;
      color: #333;
    }
    h1 { text-align: center; margin-bottom: 20px; }

    form {
      display: flex;
      flex-direction: column;
      gap: 12px;
      background: #fff;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 6px;
    }
    input {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 14px;
    }
    button {
      background: #007BFF;
      color: #fff;
      border: none;
      padding: 10px;
      border-radius: 4px;
      cursor: pointer;
    }
    button:hover { background: #0056b3; }
    p { margin-top: 10px; text-align: center; }
    a {
      display: block;
      text-align: center;
      margin-top: 10px;
      text-decoration: none;
      color: #007BFF;
    }
    a:hover { text-decoration: underline; }
    .error { color: red; text-align: center; }
  </style>
</head>
<body>

<h1>Signup</h1>

<form method="post">
  <input name="name" type="text" placeholder="Full Name" required>
  <input name="email" type="email" placeholder="Email" required>
  <input name="password" type="password" placeholder="Password" required minlength="6">
  <button>Signup</button>
</form>

<?php if ($msg): ?>
  <p class="error"><?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<a href="login.php">Login</a>

</body>
</html>
