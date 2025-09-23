<?php

require_once __DIR__ . "/../autoload.php";
use App\Manager\TodoManager;
use App\Model\Todo;

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$tm = new TodoManager();

$id = $_GET['id'] ?? null; 
$msg = '';

$todo = null;
if ($id) {
    $todo = $tm->get((int)$id, $_SESSION['user_id']);

    if ($todo && $todo->is_done) {
        $msg = "Cannot edit completed task.";
        $todo = null; 
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']); 

    if (strlen($title) < 3) {
        $msg = "Title must be at least 3 characters.";
    } else {
        if ($id && $todo) {
            $todo->title = $title;
            $tm->update($todo);
        } else {
            $newTodo = new Todo([
                'user_id' => $_SESSION['user_id'],
                'title' => $title
            ]);
            $tm->add($newTodo);
        }

        header("Location: index.php");
        exit;
    }
}
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $id ? 'Edit Todo' : 'Add Todo' ?></title>
    <style>
        body {
          font-family: sans-serif;
          max-width: 500px;
          margin: 30px auto;
          padding: 15px;
          background: #f9f9f9;
          color: #333;
        }
        nav {
          margin-bottom: 20px;
          padding: 10px;
          background: #eee;
          border-radius: 6px;
        }
        nav a {
          margin: 0 5px;
          text-decoration: none;
          color: #007BFF;
        }
        nav a:hover { text-decoration: underline; }

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
        .error { color: red; text-align: center; }
    </style>
</head>
<body>

<nav>
    <span>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span> |
    <a href="index.php">Home</a> |
    <a href="logout.php">Logout</a>
</nav>

<h1><?= $id ? 'Edit' : 'Add' ?> Todo</h1>

<?php if ($msg): ?>
    <p class="error"><?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<form method="post">
   <input 
    type="text" 
    name="title" 
    value="<?= htmlspecialchars($todo->title ?? '') ?>" 
    placeholder="Enter todo title" 
    required
>

    <button type="submit"><?= $id ? 'Update' : 'Add' ?></button>
</form>

<p><a href="index.php">Back to Home</a></p>

</body>
</html>
