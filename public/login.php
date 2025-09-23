<?php

require_once __DIR__ . "/../autoload.php";
use App\Database\DB;
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $pass = $_POST['password'];

  $db = DB::get();
  $st = $db->prepare("SELECT * FROM users WHERE email=?");
  $st->bind_param("s", $email);
  $st->execute();
  $res = $st->get_result()->fetch_assoc();

  if ($res && password_verify($pass, $res['password'])) {
    $_SESSION['user_id'] = $res['id'];
    $_SESSION['user_name'] = $res['name']; 
    header("Location: index.php");
    exit;
  } else {
    $msg = "Invalid credentials!";
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Login</title>
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

<h1>Login</h1>

<form method="post">
  <input name="email" type="email" placeholder="Email" required>
  <input name="password" type="password" placeholder="Password" required>
  <button>Login</button>
</form>

<?php if ($msg): ?>
  <p class="error"><?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<a href="signup.php">Signup</a>

</body>
</html>
