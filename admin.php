<?php
// admin/messages.php – View contact form submissions
// Change ADMIN_PASS before deploying!
define('ADMIN_PASS', 'yourpassword');  // ← CHANGE THIS

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_pass'])) {
    if ($_POST['login_pass'] === ADMIN_PASS) $_SESSION['admin'] = true;
    else $login_error = 'Incorrect password.';
}
if (isset($_GET['logout'])) { session_destroy(); header('Location: messages.php'); exit; }

if (!isset($_SESSION['admin'])):
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Admin Login</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:monospace;background:#0a0c10;color:#e8eaf0;display:flex;align-items:center;justify-content:center;min-height:100vh}
form{background:#131720;border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:2.5rem;width:320px}
h2{margin-bottom:1.5rem;color:#00e5ff;font-size:1.2rem}
input{width:100%;padding:.75rem 1rem;background:#0e1118;border:1px solid rgba(255,255,255,.1);border-radius:8px;color:#e8eaf0;font-family:monospace;font-size:.9rem;outline:none;box-sizing:border-box;margin-bottom:1rem}
button{width:100%;padding:.75rem;background:#00e5ff;color:#000;border:none;border-radius:8px;font-weight:700;cursor:pointer;font-size:.95rem}
.err{color:#ff7070;font-size:.85rem;margin-bottom:.75rem}
</style></head><body>
<form method="POST">
  <h2>📡 Admin Access</h2>
  <?php if(isset($login_error)) echo '<p class="err">' . htmlspecialchars($login_error) . '</p>'; ?>
  <input type="password" name="login_pass" placeholder="Admin password" autofocus>
  <button type="submit">Login</button>
</form>
</body></html>
<?php
else:
require_once '../config.php';
$db = db();

if (isset($_GET['read'])   && is_numeric($_GET['read']))   {
    $db->prepare("UPDATE contact_messages SET is_read=1 WHERE id=?")->execute([$_GET['read']]);
    header('Location: messages.php'); exit;
}
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $db->prepare("DELETE FROM contact_messages WHERE id=?")->execute([$_GET['delete']]);
    header('Location: messages.php'); exit;
}

$msgs   = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
$unread = array_filter($msgs, fn($m) => !$m['is_read']);
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Messages – Admin</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',sans-serif;background:#0a0c10;color:#e8eaf0;padding:2rem}
h1{color:#00e5ff;font-size:1.4rem;margin-bottom:1.5rem}
.meta{color:#7a8499;font-size:.8rem;margin-bottom:1.5rem}
.card{background:#131720;border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:1.25rem 1.5rem;margin-bottom:1rem;border-left:3px solid transparent}
.card.unread{border-left-color:#00e5ff}
.card-head{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:.6rem;gap:1rem;flex-wrap:wrap}
.sender{font-weight:600;font-size:.95rem}
.sender small{font-weight:400;color:#7a8499;margin-left:.5rem}
.date{color:#4a5568;font-size:.78rem}
.subject{font-size:.85rem;color:#00e5ff;margin-bottom:.5rem}
.msg-body{font-size:.9rem;color:#9aacbe;white-space:pre-wrap;line-height:1.6}
.actions{display:flex;gap:.5rem;margin-top:.9rem}
.btn{padding:.3rem .85rem;border:1px solid rgba(255,255,255,.15);border-radius:6px;background:transparent;color:#e8eaf0;font-size:.78rem;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center}
.btn:hover{background:rgba(255,255,255,.06)}
.btn-read{border-color:rgba(0,229,255,.3);color:#00e5ff}
.btn-del{border-color:rgba(255,80,80,.3);color:#ff7070}
.badge{display:inline-block;padding:.18rem .55rem;border-radius:4px;font-size:.7rem;font-family:monospace;margin-left:.4rem}
.badge-new{background:rgba(0,229,255,.1);color:#00e5ff;border:1px solid rgba(0,229,255,.25)}
.empty{color:#4a5568;text-align:center;padding:4rem;font-family:monospace}
.logout{float:right;font-size:.8rem;color:#7a8499;text-decoration:none}
.logout:hover{color:#00e5ff}
</style></head><body>
<h1>📬 Contact Messages
  <a href="messages.php?logout=1" class="logout">Logout</a>
</h1>
<p class="meta">
  Total: <?= count($msgs) ?> &nbsp;·&nbsp;
  Unread: <strong style="color:#00e5ff"><?= count($unread) ?></strong>
  &nbsp;·&nbsp;<a href="../" style="color:#7a8499;font-size:.8rem">← Back to Portfolio</a>
</p>

<?php if(empty($msgs)): ?>
<div class="empty">No messages yet.</div>
<?php else: ?>
<?php foreach($msgs as $m): ?>
<div class="card <?= !$m['is_read'] ? 'unread' : '' ?>">
  <div class="card-head">
    <div>
      <span class="sender">
        <?= htmlspecialchars($m['name']) ?>
        <small><?= htmlspecialchars($m['email']) ?></small>
      </span>
      <?php if(!$m['is_read']): ?><span class="badge badge-new">NEW</span><?php endif; ?>
    </div>
    <span class="date"><?= date('M d, Y · H:i', strtotime($m['created_at'])) ?></span>
  </div>
  <?php if($m['subject']): ?>
  <p class="subject">Re: <?= htmlspecialchars($m['subject']) ?></p>
  <?php endif; ?>
  <p class="msg-body"><?= htmlspecialchars($m['message']) ?></p>
  <div class="actions">
    <a href="mailto:<?= htmlspecialchars($m['email']) ?>" class="btn">Reply</a>
    <?php if(!$m['is_read']): ?>
    <a href="?read=<?= $m['id'] ?>" class="btn btn-read">Mark read</a>
    <?php endif; ?>
    <a href="?delete=<?= $m['id'] ?>" class="btn btn-del" onclick="return confirm('Delete this message?')">Delete</a>
  </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</body></html>
<?php endif; ?>