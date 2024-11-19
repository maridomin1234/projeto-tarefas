<?php
include 'conecta.php';

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$message = '';

// Função para executar uma query
function executeQuery($sql, $params, $types) {
    global $conn;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    return $stmt->execute() ? "Operação realizada com sucesso" : "Erro na operação";
}

// Carregar usuários para o dropdown
$usuarios = [];
$sql = "SELECT usu_id, usu_nome FROM usuarios";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tarefa_setor = trim($_POST["tarefa_setor"] ?? '');
    $tarefa_prioridade = trim($_POST["tarefa_prioridade"] ?? '');
    $tarefa_descricao = trim($_POST["tarefa_descricao"] ?? '');
    $tarefa_status = trim($_POST["tarefa_status"] ?? '');
    $usu_id = trim($_POST["usu_id"] ?? '');

    if (!empty($tarefa_setor) && !empty($tarefa_prioridade) && !empty($tarefa_descricao) && !empty($tarefa_status) && !empty($usu_id)) {
        $sql = "INSERT INTO tarefas (tarefa_setor, tarefa_prioridade, tarefa_descricao, tarefa_status, usu_id) VALUES (?, ?, ?, ?, ?)";
        $message = executeQuery($sql, [$tarefa_setor, $tarefa_prioridade, $tarefa_descricao, $tarefa_status, $usu_id], 'ssssi');
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
        .form-group input, .form-group select {
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
            <a href="index.php">Cadastro de Usuários</a>
            <a href="cadastro_de_tarefas.php">Cadastro de Tarefas</a>
            <a href="gerenciamento_de_tarefas.php">Gerenciar Tarefas</a>
        </div>
    </div>
    <div class="content">
        <h2>Cadastro de Tarefas</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="tarefa_setor">Setor:</label>
                <input type="text" id="tarefa_setor" name="tarefa_setor">
            </div>
            <div class="form-group">
                <label for="tarefa_prioridade">Prioridade:</label>
                <select id="tarefa_prioridade" name="tarefa_prioridade" required>
                    <option value="">Selecione a prioridade</option>
                    <option value="alta">Alta</option>
                    <option value="media">Média</option>
                    <option value="baixa">Baixa</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tarefa_descricao">Descrição:</label>
                <input type="text" id="tarefa_descricao" name="tarefa_descricao">
            </div>
            <div class="form-group">
                <label for="tarefa_status">Status:</label>
                <select id="tarefa_status" name="tarefa_status" required>
                    <option value="">Selecione o Status</option>
                    <option value="Em andamento">Em andamento</option>
                    <option value="Pendente">Pendente</option>
                    <option value="Finalizado">Finalizado</option>
                </select>
            </div>
            <div class="form-group">
                <label for="usu_id">Usuário:</label>
                <select id="usu_id" name="usu_id" required>
                    <option value="">Selecione um usuário</option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?php echo $usuario['usu_id']; ?>">
                            <?php echo $usuario['usu_nome']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="add_tarefa" class="btn">Cadastrar</button>
        </form>
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
