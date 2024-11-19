<?php
include 'conecta.php';

// Deletar tarefa
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM tarefas WHERE tarefa_id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Tarefa deletada com sucesso!'); window.location.href = 'gerenciamento_de_tarefas.php';</script>";
    } else {
        echo "Erro ao deletar: " . $conn->error;
    }
    $stmt->close();
}

// Atualizar status da tarefa
if (isset($_POST['update_status'])) {
    $tarefa_id = $_POST['tarefa_id'];
    $novo_status = $_POST['novo_status'];
    $stmt = $conn->prepare("UPDATE tarefas SET tarefa_status = ? WHERE tarefa_id = ?");
    $stmt->bind_param("si", $novo_status, $tarefa_id);
    if ($stmt->execute()) {
        echo "<script>alert('Status atualizado com sucesso!'); window.location.href = 'gerenciamento_de_tarefas.php';</script>";
    } else {
        echo "Erro ao atualizar status: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
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
            background-color: #e0f7ff;
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn.delete {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Gerenciamento de Tarefas</h1>
        <div class="menu">
            <a href="index.php">Cadastro usuarios</a>
            <a href="cadastro_de_tarefas.php">Cadastro de tarefas</a>
        </div>
    </div>
    <div class="content">
        <h2>Gerenciamento de Tarefas</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Setor</th>
                <th>Prioridade</th>
                <th>Descrição</th>
                <th>Status</th>
                <th>Usuário</th>
                <th>Ações</th>
            </tr>
            <?php
            $sql = "SELECT tarefas.tarefa_id, tarefas.tarefa_setor, tarefas.tarefa_prioridade, tarefas.tarefa_descricao, tarefas.tarefa_status, usuarios.usu_nome 
                    FROM tarefas 
                    JOIN usuarios ON tarefas.usu_id = usuarios.usu_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['tarefa_id'] . "</td>";
                    echo "<td>" . $row['tarefa_setor'] . "</td>";
                    echo "<td>" . $row['tarefa_prioridade'] . "</td>";
                    echo "<td>" . $row['tarefa_descricao'] . "</td>";
                    echo "<td>" . $row['tarefa_status'] . "</td>";
                    echo "<td>" . $row['usu_nome'] . "</td>";
                    echo "<td>
                            <form method='post' style='display:inline-block;'>
                                <input type='hidden' name='tarefa_id' value='" . $row['tarefa_id'] . "'>
                                <select name='novo_status'>
                                    <option value='Pendente'" . ($row['tarefa_status'] == 'Pendente' ? ' selected' : '') . ">Pendente</option>
                                    <option value='Em andamento'" . ($row['tarefa_status'] == 'Em andamento' ? ' selected' : '') . ">Em andamento</option>
                                    <option value='Concluída'" . ($row['tarefa_status'] == 'Concluída' ? ' selected' : '') . ">Concluída</option>
                                </select>
                                <button type='submit' name='update_status' class='btn'>Atualizar</button>
                            </form>
                            <a href='?delete_id=" . $row['tarefa_id'] . "' class='btn delete' onclick='return confirm('Tem certeza que deseja deletar esta tarefa?')'>Deletar</a>
                        </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Nenhuma tarefa encontrada</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
