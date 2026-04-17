<?php
session_start();
require_once 'includes/header.php';
require_once 'config/database.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = "Preenche todos os campos.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM utilizadores WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $erro = "Este email já está registado.";
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO utilizadores (nome, email, senha) VALUES (?, ?, ?)");
            $stmt->execute([$nome, $email, $senha_hash]);
            $sucesso = "Conta criada com sucesso! <a href='login.php'>Fazer login</a>";
        }
    }
}
?>

<h2>Criar conta</h2>

<?php if ($erro): ?>
    <p class="erro"><?= $erro ?></p>
<?php endif; ?>

<?php if ($sucesso): ?>
    <p class="sucesso"><?= $sucesso ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="nome" placeholder="O teu nome">
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="senha" placeholder="Senha">
    <button type="submit">Registar</button>
</form>

<p>Já tens conta? <a href="login.php">Fazer login</a></p>