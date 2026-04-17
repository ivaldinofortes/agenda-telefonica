<?php
session_start();
require_once 'config/database.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        $erro = "Preenche todos os campos.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE email = ?");
        $stmt->execute([$email]);
        $utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilizador && password_verify($senha, $utilizador['senha'])) {
            $_SESSION['utilizador_id'] = $utilizador['id'];
            $_SESSION['utilizador_nome'] = $utilizador['nome'];
            header("Location: agenda.php");
            exit;
        } else {
            $erro = "Email ou senha incorrectos.";
        }
    }
}

require_once 'includes/header.php';
?>

<h2>Entrar</h2>

<?php if ($erro): ?>
    <p class="erro"><?= $erro ?></p>
<?php endif; ?>

<form method="POST">
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="senha" placeholder="Senha">
    <button type="submit">Entrar</button>
</form>

<p>Não tens conta? <a href="registo.php">Criar conta</a></p>