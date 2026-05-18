<?php
// Connexió a la base de dades
$host = getenv('DB_HOST') ?: 'db';
$dbname = getenv('DB_NAME') ?: 'taskmanager';
$user = getenv('DB_USER') ?: 'taskuser';
$pass = getenv('DB_PASS') ?: 'taskpass';

$conn = null;
for ($i = 0; $i < 30; $i++) {
    $conn = mysqli_connect($host, $user, $pass, $dbname);
    if ($conn) break;
    sleep(1);
}
if (!$conn) {
    die("Error de connexió: " . mysqli_connect_error());
}

// Afegir tasca
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['text'])) {
    $text = trim($_POST['text']);
    $tag = $_POST['tag'] ?? 'Nova';
    if (!empty($text)) {
        $text = mysqli_real_escape_string($conn, $text);
        $tag = mysqli_real_escape_string($conn, $tag);
        $sql = "INSERT INTO tasques (text, tag) VALUES ('$text', '$tag')";
        mysqli_query($conn, $sql);
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Toggle tasca
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $sql = "UPDATE tasques SET done = 1 - done WHERE id = $id";
    mysqli_query($conn, $sql);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Obtenir tasques
$sql = "SELECT * FROM tasques ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$tasks = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tasks[] = $row;
}

// Separar pendents i completades
$pending = array_filter($tasks, fn($t) => !$t['done']);
$done = array_filter($tasks, fn($t) => $t['done']);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="ca">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Task Manager – DAWe</title>
  <link rel="stylesheet" href="style.css" />
</head>

<body>

  <header>
    <div class="header-inner">
      <span class="logo">✅</span>
      <h1>Task Manager</h1>
      <span class="badge">DAWe · RA6</span>
    </div>
  </header>

  <main>
    <section class="add-task">
      <h2>Nova tasca</h2>
      <form class="task-form" method="post">
        <input type="text" placeholder="Escriu una nova tasca..." class="task-input" name="text" autocomplete="off" />
        <select class="task-tag-select" name="tag">
          <option value="Nova">Nova</option>
          <option value="Part 1">Part 1</option>
          <option value="Part 2">Part 2</option>
          <option value="Part 3">Part 3</option>
          <option value="Part 4">Part 4</option>
        </select>
        <button type="submit" class="btn-add">Afegir</button>
      </form>
      <p class="hint">Fes clic en qualsevol tasca per marcar-la com a solucionada (o per desmarcar-la).</p>
    </section>

    <section class="task-list-section">
      <h2 id="heading-pending">Tasques pendents (<?= count($pending) ?>)</h2>
      <ul class="task-list" id="pending-list">
        <?php foreach ($pending as $task): ?>
          <li class="task-item pending">
            <span class="task-status pending-icon">○</span>
            <span class="task-text"><?= htmlspecialchars($task['text']) ?></span>
            <span class="task-tag"><?= htmlspecialchars($task['tag']) ?></span>
            <a href="?toggle=<?= $task['id'] ?>" class="toggle-link" title="Fes clic per marcar com a solucionada"></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>

    <section class="task-list-section">
      <h2 id="heading-done">Tasques completades (<?= count($done) ?>)</h2>
      <ul class="task-list" id="done-list">
        <?php foreach ($done as $task): ?>
          <li class="task-item done">
            <span class="task-status done-icon">✓</span>
            <span class="task-text" style="text-decoration: line-through;"><?= htmlspecialchars($task['text']) ?></span>
            <span class="task-tag done-tag">Fet</span>
            <a href="?toggle=<?= $task['id'] ?>" class="toggle-link" title="Fes clic per marcar com a pendent"></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>
  </main>

  <footer>
    <p>Mòdul 0614 – Desplegament d'Aplicacions Web · Institut Tecnològic de Barcelona</p>
  </footer>

</body>

</html>