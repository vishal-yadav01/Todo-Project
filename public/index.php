<?php

require_once __DIR__ . "/../autoload.php";
use App\Manager\TodoManager;

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$tm = new TodoManager();
$todos = $tm->all($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['done_id'])) {
        $todo = $tm->get((int)$_POST['done_id'], $_SESSION['user_id']);
        if ($todo && !$todo->is_done) {
            $tm->markDone($todo->id, $_SESSION['user_id']);
        }
        header("Location: index.php");
        exit;
    }

    if (isset($_POST['delete_id'])) {
        $todo = $tm->get((int)$_POST['delete_id'], $_SESSION['user_id']);
        if ($todo && $todo->is_done) {
            $tm->delete($todo->id, $_SESSION['user_id']);
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
  <title>Todo App</title>
  <style>
    body {
      font-family: sans-serif;
      max-width: 600px;
      margin: 20px auto;
      padding: 10px;
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

    h1 { margin-bottom: 10px; }

    ul { list-style: none; padding: 0; }
    li {
      background: #fff;
      margin-bottom: 10px;
      padding: 10px;
      border-radius: 6px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border: 1px solid #ddd;
    }
    li.done span {
      text-decoration: line-through;
      color: #888;
    }
    .todo-actions {
      display: flex;
      gap: 6px;
    }
    button, a {
      border: none;
      padding: 6px 10px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
    }
    button { background: #007BFF; color: #fff; }
    button:hover { background: #0056b3; }
    .delete-btn { background: #dc3545; }
    .delete-btn:hover { background: #b02a37; }
    @media (max-width: 500px) {
      body { padding: 5px; }
      li { flex-direction: column; align-items: flex-start; }
      .todo-actions { margin-top: 8px; }
    }
  </style>
</head>

<body>
<nav>
  <span>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span> |
  <a href="add_todo.php">Add Todo</a> |
  <a href="logout.php">Logout</a>
</nav>

<h1>Your Todos</h1>

<?php if (count($todos) === 0): ?>
    <h2>There is no todo yet!</h2>
<?php endif; ?>

<ul id="todo-list">
  <?php foreach($todos as $t): ?>
    <li class="<?= $t->is_done ? 'done' : '' ?>">
      <span><?= htmlspecialchars($t->title) ?></span>
      <div class="todo-actions">
        <?php if (!$t->is_done): ?>
          <a href="add_todo.php?id=<?= $t->id ?>">Edit</a>
          <form method="post" style="display:inline;">
            <input type="hidden" name="done_id" value="<?= $t->id ?>">
            <button type="submit">Mark Done</button>
          </form>
        <?php endif; ?>

        <?php if ($t->is_done): ?>
          <form method="post" style="display:inline;">
            <input type="hidden" name="delete_id" value="<?= $t->id ?>">
            <button type="submit" class="delete-btn">Delete</button>
          </form>
        <?php endif; ?>
      </div>
    </li>
  <?php endforeach; ?>
</ul>

</body>
</html>
