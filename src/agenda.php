<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['utilizador_id'])) {
    header("Location: login.php");
    exit;
}

$utilizador_id = $_SESSION['utilizador_id'];
$erro = '';
$sucesso = '';

// APAGAR contacto
if (isset($_GET['apagar'])) {
    $stmt = $pdo->prepare("DELETE FROM contactos WHERE id = ? AND utilizador_id = ?");
    $stmt->execute([$_GET['apagar'], $utilizador_id]);
    header("Location: agenda.php");
    exit;
}

// GUARDAR contacto novo ou editado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    $email    = trim($_POST['email']);
    $notas    = trim($_POST['notas']);
    $id_editar = $_POST['id_editar'] ?? null;

    if (empty($nome) || empty($telefone)) {
        $erro = "Nome e telefone são obrigatórios.";
    } else {
        if ($id_editar) {
            $stmt = $pdo->prepare("UPDATE contactos SET nome=?, telefone=?, email=?, notas=? WHERE id=? AND utilizador_id=?");
            $stmt->execute([$nome, $telefone, $email, $notas, $id_editar, $utilizador_id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO contactos (utilizador_id, nome, telefone, email, notas) VALUES (?,?,?,?,?)");
            $stmt->execute([$utilizador_id, $nome, $telefone, $email, $notas]);
        }
        header("Location: agenda.php");
        exit;
    }
}

// CARREGAR contacto para editar
$contacto_editar = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM contactos WHERE id = ? AND utilizador_id = ?");
    $stmt->execute([$_GET['editar'], $utilizador_id]);
    $contacto_editar = $stmt->fetch(PDO::FETCH_ASSOC);
}

// LISTAR contactos
$stmt = $pdo->prepare("SELECT * FROM contactos WHERE utilizador_id = ? ORDER BY nome ASC");
$stmt->execute([$utilizador_id]);
$contactos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Agenda Telefónica</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 40px auto; padding: 0 20px; }
        input, textarea { display: block; width: 100%; padding: 8px; margin: 6px 0; box-sizing: border-box; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; margin-top: 6px; }
        .erro { color: red; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #f4f4f4; }
        a { color: #007bff; text-decoration: none; margin-right: 8px; }
        a.apagar { color: red; }
        nav { margin-bottom: 20px; }
    </style>
</head>
<body>

<nav>
    <strong>Olá, <?= htmlspecialchars($_SESSION['utilizador_nome']) ?></strong>
    &nbsp;|&nbsp;
    <a href="logout.php">Sair</a>
</nav>

<h2><?= $contacto_editar ? 'Editar contacto' : 'Novo contacto' ?></h2>

<?php if ($erro): ?>
    <p class="erro"><?= $erro ?></p>
<?php endif; ?>

<form method="POST">
    <input type="hidden" name="id_editar" value="<?= $contacto_editar['id'] ?? '' ?>">
    <input type="text" name="nome" placeholder="Nome *" value="<?= htmlspecialchars($contacto_editar['nome'] ?? '') ?>">
    <input type="text" name="telefone" placeholder="Telefone *" value="<?= htmlspecialchars($contacto_editar['telefone'] ?? '') ?>">
    <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($contacto_editar['email'] ?? '') ?>">
    <textarea name="notas" placeholder="Notas"><?= htmlspecialchars($contacto_editar['notas'] ?? '') ?></textarea>
    <button type="submit"><?= $contacto_editar ? 'Guardar alterações' : 'Adicionar contacto' ?></button>
    <?php if ($contacto_editar): ?>
        <a href="agenda.php">Cancelar</a>
    <?php endif; ?>
</form>

<h2>Os meus contactos</h2>

<?php if (empty($contactos)): ?>
    <p>Ainda não tens contactos.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Nome</th>
            <th>Telefone</th>
            <th>Email</th>
            <th>Acções</th>
        </tr>
        <?php foreach ($contactos as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c['nome']) ?></td>
            <td><?= htmlspecialchars($c['telefone']) ?></td>
            <td><?= htmlspecialchars($c['email']) ?></td>
            <td>
                <a href="agenda.php?editar=<?= $c['id'] ?>">Editar</a>
                <a class="apagar" href="agenda.php?apagar=<?= $c['id'] ?>" onclick="return confirm('Tens a certeza?')">Apagar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>