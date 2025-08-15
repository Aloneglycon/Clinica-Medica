<?php 
include 'conexao.php';

// Lógica para alterar o status da fatura
if (isset($_GET['opcao']) && $_GET['opcao'] == 'alterar_status' && isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status']; // 'pendente' ou 'pago'
    
    // Atualizando o status da fatura
    $sql = "UPDATE faturas SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Status da fatura alterado para $status com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao alterar o status da fatura.');</script>";
    }
    $stmt->close();
}

// Lógica para salvar a fatura
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paciente_id = $_POST['paciente_id'];
    $convenio_id = $_POST['convenio_id'];
    $valor = $_POST['valor'];
    $data_emissao = $_POST['data_emissao'];
    $status = $_POST['status'];
    
    $sql = "INSERT INTO faturas (convenio_id, paciente_id, valor, data_emissao, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iidss", $convenio_id, $paciente_id, $valor, $data_emissao, $status);
    
    if ($stmt->execute()) {
        echo "<script>alert('Fatura registrada com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao registrar fatura.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Médica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet"> 
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Clínica Médica</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="pacientes.php">Pacientes</a></li>
                <li class="nav-item"><a class="nav-link" href="medicos.php">Médicos</a></li>
                <li class="nav-item"><a class="nav-link" href="consultas.php">Consultas</a></li>
                <li class="nav-item"><a class="nav-link" href="faturas.php">Faturamento</a></li>
                <li class="nav-item"><a class="nav-link" href="exames.php">Exames</a></li>
            </ul>
        </div>
    </div>
</nav>


<!-- Conteúdo de Faturas -->
<div class="container mt-4">
    <h2>Registrar Fatura</h2>
    <form method="POST" action="faturas.php">
        <div class="mb-3">
            <label class="form-label">Paciente</label>
            <select name="paciente_id" class="form-control" required>
                <option value="">Selecione um paciente</option>
                <?php
                $sql = "SELECT * FROM pacientes";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Convênio</label>
            <select name="convenio_id" class="form-control" required>
                <option value="">Selecione um convênio</option>
                <?php
                $sql = "SELECT * FROM convenios";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Valor</label>
            <input type="number" step="0.01" name="valor" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Data de Emissão</label>
            <input type="date" name="data_emissao" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="pendente">Pendente</option>
                <option value="pago">Pago</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>

    <h2 class="mt-4">Faturas Registradas</h2>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Paciente</th>
            <th>Convênio</th>
            <th>Valor</th>
            <th>Data de Emissão</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        <?php
        $sql = "SELECT f.id, p.nome AS paciente, c.nome AS convenio, f.valor, f.data_emissao, f.status FROM faturas f 
                JOIN pacientes p ON f.paciente_id = p.id 
                JOIN convenios c ON f.convenio_id = c.id 
                ORDER BY f.id DESC";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['paciente']}</td>
                    <td>{$row['convenio']}</td>
                    <td>R$ {$row['valor']}</td>
                    <td>{$row['data_emissao']}</td>
                    <td>{$row['status']}</td>
                    <td>
                        <a href='faturas.php?opcao=alterar_status&id={$row['id']}&status=pendente' class='btn btn-warning btn-sm'>Pendente</a>
                        <a href='faturas.php?opcao=alterar_status&id={$row['id']}&status=pago' class='btn btn-success btn-sm'>Pago</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>
</div>

<!-- Rodapé -->
<footer class="bg-primary text-white text-center py-3">
    <p>&copy; 2025 Clínica Médica. Todos os direitos reservados.</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
