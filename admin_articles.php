<?php
// ============================================================
//  CONFIG – Adaptez ces valeurs à votre environnement
// ============================================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'organisation_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('UPLOAD_DIR', 'uploads/');

// ============================================================
//  CONNEXION PDO
// ============================================================
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',
            DB_USER, DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
    return $pdo;
}

// ============================================================
//  CRÉATION DE LA TABLE (à exécuter une seule fois)
// ============================================================
// CREATE TABLE IF NOT EXISTS articles (
//   id          INT AUTO_INCREMENT PRIMARY KEY,
//   titre       VARCHAR(255)  NOT NULL,
//   categorie   VARCHAR(100)  NOT NULL DEFAULT 'Actualité',
//   contenu     LONGTEXT      NOT NULL,
//   extrait     TEXT,
//   image       VARCHAR(300),
//   statut      ENUM('publie','brouillon') DEFAULT 'publie',
//   created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
//   updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

// ============================================================
//  TRAITEMENT FORMULAIRE
// ============================================================
$message_retour = '';
$type_retour    = '';
$article_edit   = null;

// --- Suppression ---
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    try {
        $stmt = getDB()->prepare('DELETE FROM articles WHERE id = ?');
        $stmt->execute([$_GET['delete']]);
        $message_retour = 'Article supprimé avec succès.';
        $type_retour    = 'success';
    } catch (Exception $e) {
        $message_retour = 'Erreur lors de la suppression : '.$e->getMessage();
        $type_retour    = 'danger';
    }
}

// --- Chargement pour édition ---
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $stmt = getDB()->prepare('SELECT * FROM articles WHERE id = ?');
    $stmt->execute([$_GET['edit']]);
    $article_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// --- Enregistrement (INSERT / UPDATE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = intval($_POST['id'] ?? 0);
    $titre     = trim($_POST['titre']     ?? '');
    $categorie = trim($_POST['categorie'] ?? 'Actualité');
    $contenu   = $_POST['contenu']  ?? '';
    $extrait   = trim($_POST['extrait']   ?? '');
    $statut    = $_POST['statut']   ?? 'publie';
    $image_path = '';

    // Upload image
    if (!empty($_FILES['image']['name'])) {
        $ext_ok = ['jpg','jpeg','png','gif','webp'];
        $ext    = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $ext_ok) && $_FILES['image']['size'] < 5_000_000) {
            if (!is_dir(UPLOAD_DIR)) { mkdir(UPLOAD_DIR, 0755, true); }
            $filename   = uniqid('img_').'.'.$ext;
            $image_path = UPLOAD_DIR.$filename;
            move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        } else {
            $message_retour = 'Image invalide (JPG/PNG/GIF/WEBP, max 5 Mo).';
            $type_retour    = 'warning';
        }
    }

    if ($titre && $contenu && !$message_retour) {
        try {
            if ($id > 0) {
                // UPDATE
                $sql = 'UPDATE articles SET titre=?, categorie=?, contenu=?, extrait=?, statut=?';
                $params = [$titre, $categorie, $contenu, $extrait, $statut];
                if ($image_path) { $sql .= ', image=?'; $params[] = $image_path; }
                $sql .= ' WHERE id=?'; $params[] = $id;
                getDB()->prepare($sql)->execute($params);
                $message_retour = 'Article mis à jour avec succès !';
            } else {
                // INSERT
                getDB()->prepare(
                    'INSERT INTO articles (titre, categorie, contenu, extrait, image, statut)
                     VALUES (?, ?, ?, ?, ?, ?)'
                )->execute([$titre, $categorie, $contenu, $extrait, $image_path, $statut]);
                $message_retour = 'Article publié avec succès !';
            }
            $type_retour  = 'success';
            $article_edit = null;
        } catch (Exception $e) {
            $message_retour = 'Erreur BD : '.$e->getMessage();
            $type_retour    = 'danger';
        }
    } elseif (!$titre || !$contenu) {
        $message_retour = 'Le titre et le contenu sont obligatoires.';
        $type_retour    = 'warning';
    }
}

// --- Liste des articles ---
$articles = [];
try {
    $articles = getDB()
        ->query('SELECT id, titre, categorie, statut, image, created_at FROM articles ORDER BY created_at DESC')
        ->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) { /* table peut-être inexistante */ }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Administration – Articles & Activités</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
<!-- Quill Rich Text Editor -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

<style>
/* ── Variables ─────────────────────────────── */
:root {
  --bg:       #0d0f14;
  --surface:  #14171f;
  --card:     #1b1f2b;
  --border:   #262b3a;
  --accent:   #4f8cff;
  --accent2:  #a78bfa;
  --green:    #34d399;
  --yellow:   #fbbf24;
  --red:      #f87171;
  --text:     #e2e8f0;
  --muted:    #64748b;
  --font-h:   'Syne', sans-serif;
  --font-b:   'DM Sans', sans-serif;
}

/* ── Base ───────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; }
body {
  margin: 0;
  background: var(--bg);
  color: var(--text);
  font-family: var(--font-b);
  font-size: .95rem;
  min-height: 100vh;
}

/* ── Sidebar ─────────────────────────────────── */
.sidebar {
  position: fixed; top: 0; left: 0; bottom: 0;
  width: 240px;
  background: var(--surface);
  border-right: 1px solid var(--border);
  display: flex; flex-direction: column;
  z-index: 100;
  padding: 0;
  transition: transform .3s ease;
}
.sidebar-brand {
  padding: 1.6rem 1.4rem 1.2rem;
  border-bottom: 1px solid var(--border);
}
.sidebar-brand h1 {
  font-family: var(--font-h);
  font-size: 1.15rem;
  font-weight: 800;
  letter-spacing: -.5px;
  margin: 0;
  color: #fff;
}
.sidebar-brand span {
  display: inline-block;
  width: 8px; height: 8px;
  background: var(--accent);
  border-radius: 50%;
  margin-right: 6px;
  animation: pulse 2s infinite;
}
@keyframes pulse {
  0%,100%{opacity:1;transform:scale(1)}
  50%{opacity:.5;transform:scale(.8)}
}
.sidebar-brand small {
  font-size: .72rem;
  color: var(--muted);
  letter-spacing: .08em;
  text-transform: uppercase;
}
.sidebar-nav { padding: 1rem 0; flex: 1; }
.nav-label {
  font-size: .65rem;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: var(--muted);
  padding: .6rem 1.4rem .3rem;
}
.sidebar-nav a {
  display: flex; align-items: center; gap: 10px;
  padding: .55rem 1.4rem;
  color: var(--muted);
  text-decoration: none;
  font-size: .88rem;
  border-left: 3px solid transparent;
  transition: color .2s, border-color .2s, background .2s;
}
.sidebar-nav a:hover,
.sidebar-nav a.active {
  color: var(--text);
  background: rgba(79,140,255,.07);
  border-left-color: var(--accent);
}
.sidebar-nav a i { font-size: 1rem; }
.sidebar-footer {
  padding: 1rem 1.4rem;
  border-top: 1px solid var(--border);
  font-size: .78rem;
  color: var(--muted);
}

/* ── Main layout ─────────────────────────────── */
.main-wrap {
  margin-left: 240px;
  min-height: 100vh;
  display: flex; flex-direction: column;
}
.topbar {
  position: sticky; top: 0; z-index: 50;
  background: rgba(13,15,20,.9);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid var(--border);
  padding: .8rem 2rem;
  display: flex; align-items: center; justify-content: space-between;
}
.topbar-title {
  font-family: var(--font-h);
  font-weight: 700;
  font-size: 1.05rem;
  color: #fff;
}
.topbar-right { display: flex; align-items: center; gap: 10px; }
.avatar {
  width: 32px; height: 32px;
  background: linear-gradient(135deg, var(--accent), var(--accent2));
  border-radius: 50%;
  display: grid; place-items: center;
  font-size: .8rem; font-weight: 700; color: #fff;
}
.content { padding: 2rem; flex: 1; }

/* ── Cards ────────────────────────────────────── */
.panel {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 14px;
  padding: 1.6rem;
  margin-bottom: 1.5rem;
}
.panel-head {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 1.3rem;
  padding-bottom: .9rem;
  border-bottom: 1px solid var(--border);
}
.panel-head h2 {
  font-family: var(--font-h);
  font-size: 1rem;
  font-weight: 700;
  color: #fff;
  margin: 0;
}
.panel-head .badge-count {
  background: rgba(79,140,255,.15);
  color: var(--accent);
  border-radius: 20px;
  padding: .15rem .65rem;
  font-size: .75rem;
  font-weight: 600;
}

/* ── Form controls ────────────────────────────── */
.form-label {
  font-size: .8rem;
  font-weight: 500;
  letter-spacing: .04em;
  color: var(--muted);
  text-transform: uppercase;
  margin-bottom: .35rem;
}
.form-control, .form-select {
  background: var(--surface) !important;
  border: 1px solid var(--border) !important;
  color: var(--text) !important;
  border-radius: 8px !important;
  font-family: var(--font-b) !important;
  font-size: .9rem !important;
  transition: border-color .2s, box-shadow .2s;
}
.form-control:focus, .form-select:focus {
  border-color: var(--accent) !important;
  box-shadow: 0 0 0 3px rgba(79,140,255,.15) !important;
  outline: none !important;
}
.form-control::placeholder { color: var(--muted) !important; }
textarea.form-control { resize: vertical; }

/* Quill override */
.ql-toolbar {
  background: var(--surface) !important;
  border: 1px solid var(--border) !important;
  border-bottom: none !important;
  border-radius: 8px 8px 0 0 !important;
}
.ql-container {
  background: var(--surface) !important;
  border: 1px solid var(--border) !important;
  border-top: none !important;
  border-radius: 0 0 8px 8px !important;
  font-family: var(--font-b) !important;
  font-size: .95rem !important;
  color: var(--text) !important;
  min-height: 260px;
}
.ql-editor { min-height: 240px; color: var(--text) !important; }
.ql-toolbar .ql-stroke { stroke: var(--muted) !important; }
.ql-toolbar .ql-fill   { fill:   var(--muted) !important; }
.ql-toolbar button:hover .ql-stroke,
.ql-toolbar button.ql-active .ql-stroke { stroke: var(--accent) !important; }
.ql-toolbar button:hover .ql-fill,
.ql-toolbar button.ql-active .ql-fill   { fill:   var(--accent) !important; }
.ql-toolbar .ql-picker-label { color: var(--muted) !important; }
.ql-picker-options {
  background: var(--card) !important;
  border: 1px solid var(--border) !important;
}
.ql-picker-options .ql-picker-item { color: var(--text) !important; }

/* Image drop zone */
.drop-zone {
  border: 2px dashed var(--border);
  border-radius: 10px;
  padding: 2rem;
  text-align: center;
  cursor: pointer;
  transition: border-color .25s, background .25s;
  position: relative;
}
.drop-zone:hover, .drop-zone.drag-over {
  border-color: var(--accent);
  background: rgba(79,140,255,.04);
}
.drop-zone input[type="file"] {
  position: absolute; inset: 0; opacity: 0; cursor: pointer;
}
.drop-zone i { font-size: 2.2rem; color: var(--muted); }
.drop-zone p { margin: .5rem 0 0; font-size: .85rem; color: var(--muted); }
#preview-img {
  max-height: 160px;
  border-radius: 8px;
  margin-top: .8rem;
  display: none;
  border: 1px solid var(--border);
}

/* ── Buttons ──────────────────────────────────── */
.btn-primary-custom {
  background: var(--accent);
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: .6rem 1.4rem;
  font-family: var(--font-h);
  font-weight: 600;
  font-size: .9rem;
  letter-spacing: .02em;
  cursor: pointer;
  transition: opacity .2s, transform .15s;
  display: inline-flex; align-items: center; gap: 6px;
}
.btn-primary-custom:hover { opacity: .88; transform: translateY(-1px); }
.btn-ghost {
  background: transparent;
  color: var(--muted);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: .55rem 1.1rem;
  font-size: .85rem;
  cursor: pointer;
  transition: color .2s, border-color .2s;
  display: inline-flex; align-items: center; gap: 6px;
}
.btn-ghost:hover { color: var(--text); border-color: var(--muted); }

/* ── Status badges ────────────────────────────── */
.badge-publie  { background: rgba(52,211,153,.15); color: var(--green); }
.badge-brouillon { background: rgba(251,191,36,.15); color: var(--yellow); }
.badge-statut {
  border-radius: 20px;
  padding: .2rem .7rem;
  font-size: .72rem;
  font-weight: 600;
  letter-spacing: .04em;
  text-transform: uppercase;
}

/* ── Table ────────────────────────────────────── */
.table-custom {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 6px;
}
.table-custom thead th {
  font-size: .72rem;
  font-weight: 600;
  letter-spacing: .08em;
  text-transform: uppercase;
  color: var(--muted);
  padding: .5rem 1rem;
  border: none;
}
.table-custom tbody tr {
  background: var(--surface);
  border-radius: 8px;
  transition: background .2s;
}
.table-custom tbody tr:hover { background: rgba(255,255,255,.03); }
.table-custom tbody td {
  padding: .75rem 1rem;
  vertical-align: middle;
  border-top: 1px solid var(--border);
  border-bottom: 1px solid var(--border);
}
.table-custom tbody td:first-child {
  border-left: 1px solid var(--border);
  border-radius: 8px 0 0 8px;
}
.table-custom tbody td:last-child {
  border-right: 1px solid var(--border);
  border-radius: 0 8px 8px 0;
}
.thumb-img {
  width: 42px; height: 42px;
  object-fit: cover;
  border-radius: 6px;
  border: 1px solid var(--border);
}
.thumb-placeholder {
  width: 42px; height: 42px;
  background: var(--border);
  border-radius: 6px;
  display: grid; place-items: center;
  color: var(--muted); font-size: .75rem;
}
.action-btn {
  background: none; border: none; padding: .3rem .4rem;
  border-radius: 6px; cursor: pointer; transition: background .2s;
  font-size: .9rem; line-height: 1;
}
.action-btn:hover { background: rgba(255,255,255,.07); }
.action-edit  { color: var(--accent); }
.action-del   { color: var(--red); }

/* ── Alert ────────────────────────────────────── */
.alert-custom {
  border-radius: 10px;
  padding: .85rem 1.1rem;
  margin-bottom: 1.2rem;
  font-size: .88rem;
  display: flex; align-items: center; gap: 10px;
  border: 1px solid transparent;
}
.alert-success { background: rgba(52,211,153,.1); border-color: rgba(52,211,153,.3); color: var(--green); }
.alert-danger   { background: rgba(248,113,113,.1); border-color: rgba(248,113,113,.3); color: var(--red); }
.alert-warning  { background: rgba(251,191,36,.1); border-color: rgba(251,191,36,.3); color: var(--yellow); }

/* ── Stat chips ───────────────────────────────── */
.stat-row { display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
.stat-chip {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 1rem 1.3rem;
  flex: 1; min-width: 120px;
}
.stat-chip .num {
  font-family: var(--font-h);
  font-size: 1.6rem;
  font-weight: 800;
  color: #fff;
  line-height: 1;
}
.stat-chip .lbl { font-size: .73rem; color: var(--muted); margin-top: .2rem; }

/* ── Responsive ───────────────────────────────── */
@media (max-width: 768px) {
  .sidebar { transform: translateX(-240px); }
  .sidebar.open { transform: translateX(0); }
  .main-wrap { margin-left: 0; }
  .content { padding: 1rem; }
}

/* ── Divider ──────────────────────────────────── */
.section-divider {
  display: flex; align-items: center; gap: 1rem;
  margin: 1.5rem 0 1.2rem;
  color: var(--muted); font-size: .75rem;
  text-transform: uppercase; letter-spacing: .08em;
}
.section-divider::before,
.section-divider::after {
  content: ''; flex: 1; height: 1px; background: var(--border);
}
</style>
</head>
<body>

<!-- ════════════ SIDEBAR ════════════ -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <h1><span></span>OrgAdmin</h1>
    <small>Panneau de gestion</small>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-label">Contenu</div>
    <a href="#form-section" class="active"><i class="bi bi-pencil-square"></i> Nouvel article</a>
    <a href="#list-section"><i class="bi bi-journals"></i> Tous les articles</a>
    <div class="nav-label">Organisation</div>
    <a href="#"><i class="bi bi-calendar-event"></i> Activités</a>
    <a href="#"><i class="bi bi-images"></i> Médiathèque</a>
    <a href="#"><i class="bi bi-people"></i> Membres</a>
    <div class="nav-label">Système</div>
    <a href="#"><i class="bi bi-gear"></i> Paramètres</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
  </nav>
  <div class="sidebar-footer">
    <i class="bi bi-circle-fill text-success" style="font-size:.5rem"></i>
    &nbsp;Connecté en tant qu'Admin
  </div>
</aside>

<!-- ════════════ MAIN ════════════ -->
<div class="main-wrap">

  <!-- Topbar -->
  <div class="topbar">
    <button class="btn-ghost d-md-none" onclick="document.getElementById('sidebar').classList.toggle('open')">
      <i class="bi bi-list"></i>
    </button>
    <span class="topbar-title">Articles &amp; Activités</span>
    <div class="topbar-right">
      <span style="font-size:.78rem;color:var(--muted)"><?= date('d/m/Y H:i') ?></span>
      <div class="avatar">AD</div>
    </div>
  </div>

  <div class="content">

    <!-- Alertes -->
    <?php if ($message_retour): ?>
    <div class="alert-custom alert-<?= $type_retour ?>">
      <i class="bi bi-<?= $type_retour==='success'?'check-circle':'exclamation-triangle' ?>-fill"></i>
      <?= htmlspecialchars($message_retour) ?>
    </div>
    <?php endif; ?>

    <!-- Stats -->
    <?php
      $total     = count($articles);
      $publies   = count(array_filter($articles, fn($a) => $a['statut']==='publie'));
      $brouillons = $total - $publies;
    ?>
    <div class="stat-row">
      <div class="stat-chip">
        <div class="num"><?= $total ?></div>
        <div class="lbl"><i class="bi bi-files"></i> Total articles</div>
      </div>
      <div class="stat-chip">
        <div class="num" style="color:var(--green)"><?= $publies ?></div>
        <div class="lbl"><i class="bi bi-check2-circle"></i> Publiés</div>
      </div>
      <div class="stat-chip">
        <div class="num" style="color:var(--yellow)"><?= $brouillons ?></div>
        <div class="lbl"><i class="bi bi-clock"></i> Brouillons</div>
      </div>
    </div>

    <!-- ── FORMULAIRE ── -->
    <div class="panel" id="form-section">
      <div class="panel-head">
        <h2><i class="bi bi-<?= $article_edit ? 'pencil' : 'plus-circle' ?>"></i>
          &nbsp;<?= $article_edit ? 'Modifier l\'article' : 'Créer un article / activité' ?>
        </h2>
        <?php if ($article_edit): ?>
          <a href="?" class="btn-ghost"><i class="bi bi-x"></i> Annuler</a>
        <?php endif; ?>
      </div>

      <form method="POST" enctype="multipart/form-data" id="articleForm">
        <input type="hidden" name="id" value="<?= $article_edit['id'] ?? 0 ?>">

        <!-- Ligne 1 : Titre + Catégorie -->
        <div class="row g-3 mb-3">
          <div class="col-md-7">
            <label class="form-label"><i class="bi bi-type-h1"></i> Titre de l'article *</label>
            <input type="text" name="titre" class="form-control"
                   placeholder="Ex : Assemblée générale annuelle 2025"
                   value="<?= htmlspecialchars($article_edit['titre'] ?? '') ?>" required>
          </div>
          <div class="col-md-3">
            <label class="form-label"><i class="bi bi-tag"></i> Catégorie</label>
            <select name="categorie" class="form-select">
              <?php foreach (['Actualité','Activité','Événement','Annonce','Formation','Rapport'] as $cat): ?>
                <option value="<?= $cat ?>" <?= ($article_edit['categorie']??'Actualité')===$cat?'selected':'' ?>>
                  <?= $cat ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label"><i class="bi bi-toggle-on"></i> Statut</label>
            <select name="statut" class="form-select">
              <option value="publie"    <?= ($article_edit['statut']??'publie')==='publie'    ?'selected':'' ?>>✅ Publié</option>
              <option value="brouillon" <?= ($article_edit['statut']??'')==='brouillon'?'selected':'' ?>>🕐 Brouillon</option>
            </select>
          </div>
        </div>

        <!-- Image -->
        <div class="section-divider">Image de couverture</div>
        <div class="mb-3">
          <label class="form-label"><i class="bi bi-image"></i> Image principale</label>
          <div class="drop-zone" id="dropZone">
            <input type="file" name="image" id="imageInput" accept="image/*">
            <i class="bi bi-cloud-arrow-up"></i>
            <p>Glissez une image ici ou <strong>cliquez pour parcourir</strong></p>
            <p style="font-size:.75rem">JPG, PNG, GIF, WEBP — max 5 Mo</p>
          </div>
          <img id="preview-img" src="" alt="Aperçu">
          <?php if (!empty($article_edit['image'])): ?>
            <div class="mt-2" style="font-size:.8rem;color:var(--muted)">
              <i class="bi bi-image-fill"></i>
              Image actuelle : <?= htmlspecialchars($article_edit['image']) ?><br>
              <img src="<?= htmlspecialchars($article_edit['image']) ?>" style="max-height:80px;margin-top:.4rem;border-radius:6px">
            </div>
          <?php endif; ?>
        </div>

        <!-- Contenu Quill -->
        <div class="section-divider">Contenu principal</div>
        <div class="mb-3">
          <label class="form-label"><i class="bi bi-body-text"></i> Contenu formaté *</label>
          <div id="quill-editor"><?= $article_edit['contenu'] ?? '' ?></div>
          <input type="hidden" name="contenu" id="contenu-hidden">
        </div>

        <!-- Extrait -->
        <div class="mb-3">
          <label class="form-label"><i class="bi bi-chat-left-text"></i> Extrait / Résumé</label>
          <textarea name="extrait" class="form-control" rows="2"
            placeholder="Courte description affichée dans les listes d'articles..."><?=
            htmlspecialchars($article_edit['extrait'] ?? '') ?></textarea>
        </div>

        <!-- Boutons -->
        <div class="d-flex gap-2 flex-wrap mt-4">
          <button type="submit" class="btn-primary-custom">
            <i class="bi bi-<?= $article_edit ? 'check2' : 'send' ?>"></i>
            <?= $article_edit ? 'Mettre à jour' : 'Publier l\'article' ?>
          </button>
          <button type="button" class="btn-ghost" onclick="setSatut('brouillon')">
            <i class="bi bi-floppy"></i> Enregistrer brouillon
          </button>
          <button type="button" class="btn-ghost" onclick="resetForm()">
            <i class="bi bi-x-circle"></i> Réinitialiser
          </button>
        </div>
      </form>
    </div><!-- /panel -->

    <!-- ── LISTE DES ARTICLES ── -->
    <div class="panel" id="list-section">
      <div class="panel-head">
        <h2><i class="bi bi-journals"></i> &nbsp;Articles enregistrés</h2>
        <span class="badge-count"><?= $total ?></span>
      </div>

      <?php if (empty($articles)): ?>
        <div style="text-align:center;padding:2.5rem;color:var(--muted)">
          <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:.5rem"></i>
          Aucun article pour l'instant.
        </div>
      <?php else: ?>
      <div style="overflow-x:auto">
        <table class="table-custom">
          <thead>
            <tr>
              <th>Image</th>
              <th>Titre</th>
              <th>Catégorie</th>
              <th>Statut</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($articles as $a): ?>
            <tr>
              <td>
                <?php if ($a['image']): ?>
                  <img src="<?= htmlspecialchars($a['image']) ?>" class="thumb-img" alt="">
                <?php else: ?>
                  <div class="thumb-placeholder"><i class="bi bi-image"></i></div>
                <?php endif; ?>
              </td>
              <td style="font-weight:500;color:#fff;max-width:260px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                <?= htmlspecialchars($a['titre']) ?>
              </td>
              <td>
                <span style="font-size:.78rem;color:var(--muted)"><?= htmlspecialchars($a['categorie']) ?></span>
              </td>
              <td>
                <span class="badge-statut badge-<?= $a['statut'] ?>">
                  <?= $a['statut']==='publie' ? '✅ Publié' : '🕐 Brouillon' ?>
                </span>
              </td>
              <td style="font-size:.78rem;color:var(--muted);white-space:nowrap">
                <?= date('d/m/Y H:i', strtotime($a['created_at'])) ?>
              </td>
              <td style="white-space:nowrap">
                <a href="?edit=<?= $a['id'] ?>" class="action-btn action-edit" title="Modifier">
                  <i class="bi bi-pencil-fill"></i>
                </a>
                <a href="?delete=<?= $a['id'] ?>" class="action-btn action-del"
                   onclick="return confirm('Supprimer définitivement cet article ?')" title="Supprimer">
                  <i class="bi bi-trash3-fill"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endif; ?>
    </div>

  </div><!-- /content -->
</div><!-- /main-wrap -->

<!-- ══ SCRIPTS ══════════════════════════════════════════ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
// ── Quill init ──────────────────────────────────────────
const quill = new Quill('#quill-editor', {
  theme: 'snow',
  placeholder: 'Rédigez le contenu de votre article ici…',
  modules: {
    toolbar: [
      [{ header: [1, 2, 3, false] }],
      ['bold', 'italic', 'underline', 'strike'],
      [{ color: [] }, { background: [] }],
      [{ align: [] }],
      [{ list: 'ordered' }, { list: 'bullet' }],
      [{ indent: '-1' }, { indent: '+1' }],
      ['blockquote', 'code-block'],
      ['link', 'image'],
      ['clean']
    ]
  }
});

// Synchroniser Quill → input hidden avant soumission
document.getElementById('articleForm').addEventListener('submit', function() {
  document.getElementById('contenu-hidden').value = quill.root.innerHTML;
});

// ── Image preview ───────────────────────────────────────
const imageInput = document.getElementById('imageInput');
const previewImg  = document.getElementById('preview-img');
const dropZone    = document.getElementById('dropZone');

imageInput.addEventListener('change', showPreview);

function showPreview() {
  const file = imageInput.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    previewImg.src = e.target.result;
    previewImg.style.display = 'block';
  };
  reader.readAsDataURL(file);
}

// Drag & Drop
dropZone.addEventListener('dragover',  e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
dropZone.addEventListener('dragleave', ()  => dropZone.classList.remove('drag-over'));
dropZone.addEventListener('drop',      e  => {
  e.preventDefault();
  dropZone.classList.remove('drag-over');
  const dt = e.dataTransfer;
  if (dt.files.length) {
    imageInput.files = dt.files;
    showPreview();
  }
});

// ── Helpers ─────────────────────────────────────────────
function setSatut(val) {
  document.querySelector('[name="statut"]').value = val;
  document.getElementById('articleForm').requestSubmit();
}

function resetForm() {
  if (!confirm('Réinitialiser le formulaire ?')) return;
  document.getElementById('articleForm').reset();
  quill.setText('');
  previewImg.style.display = 'none';
  previewImg.src = '';
}

// Smooth scroll pour les ancres sidebar
document.querySelectorAll('.sidebar-nav a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const target = document.querySelector(a.getAttribute('href'));
    if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
    // Mobile close
    document.getElementById('sidebar').classList.remove('open');
  });
});
</script>
</body>
</html>
