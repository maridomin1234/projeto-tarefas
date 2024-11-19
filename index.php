<?php
include 'conecta.php';

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$message = '';

function executeQuery($sql, $params, $types) {
    global $conn;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    return $stmt->execute() ? "Operação realizada com sucesso" : "Erro na operação";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usu_nome = trim($_POST["usu_nome"] ?? '');
    $usu_email = trim($_POST["usu_email"] ?? '');

    if (!empty($usu_nome) && !empty($usu_email)) {
        $sql = "INSERT INTO usuarios (usu_nome, usu_email) VALUES (?, ?)";
        $message = executeQuery($sql, [$usu_nome, $usu_email], 'ss');
    } else {
        $message = "Por favor, preencha todos os campos.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Tarefas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #007bff;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            color: white;
        }
        .navbar h1 {
            margin: 0;
        }
        .menu {
            display: flex;
            gap: 20px;
        }
        .menu a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .content {
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 15px;
            font-weight: bold;
            color: green;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Gerenciamento de Tarefas</h1>
        <div class="menu">
            <a href="#">Cadastro de Usuários</a>
            <a href="cadastro_de_tarefas.php">Cadastro de Tarefas</a>
            <a href="gerenciamento_de_tarefas.php">Gerenciar Tarefas</a>
        </div>
    </div>
    <div class="content">
        <h2>Cadastro de Usuários</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="usu_nome">Nome:</label>
                <input type="text" id="usu_nome" name="usu_nome">
            </div>
            <div class="form-group">
                <label for="usu_email">Email:</label>
                <input type="email" id="usu_email" name="usu_email">
            </div>
            <button type="submit" name="add_usuario" class="btn">Cadastrar</button>
        </form>
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
