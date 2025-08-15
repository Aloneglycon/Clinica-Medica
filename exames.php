<?php include 'conexao.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exames</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

<div class="container mt-4">
    <h2>Lista de Exames</h2>

    <?php
    // Lógica para excluir exame
    if (isset($_GET['opcao'])) {
        if ($_GET['opcao'] == 'excluir' && isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "DELETE FROM exames WHERE id = $id";
            if ($conn->query($sql)) {
                echo "<div class='alert alert-success'>Exame excluído com sucesso!</div>";
            } else {
                echo "<div class='alert alert-danger'>Erro ao excluir exame!</div>";
            }
        }

        // Lógica para alterar o status do exame
        if ($_GET['opcao'] == 'alterar_status' && isset($_GET['id']) && isset($_GET['status'])) {
            $id = $_GET['id'];
            $status = $_GET['status']; // 'realizado' ou 'cancelado'
            $sql = "UPDATE exames SET status = '$status' WHERE id = $id";
            if ($conn->query($sql)) {
                echo "<div class='alert alert-success'>Status do exame alterado para '$status' com sucesso!</div>";
            } else {
                echo "<div class='alert alert-danger'>Erro ao alterar status do exame!</div>";
            }
        }
    }

    // Lógica para salvar/atualizar exame
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['paciente_id'])) {
        // Formulário de exame
        $paciente_id = $_POST['paciente_id'];
        $tipo_exame_id = $_POST['tipo_exame_id'];
        $data_hora = date('Y-m-d H:i:s'); // Data e hora atuais
        $status = 'agendado'; // Status padrão para exames

        // Inserir novo exame
        $sql = "INSERT INTO exames (paciente_id, tipo_exame_id, data_hora, status) 
                VALUES ('$paciente_id', '$tipo_exame_id', '$data_hora', '$status')";

        if ($conn->query($sql)) {
            echo "<div class='alert alert-success'>Exame agendado com sucesso!</div>";
        } else {
            echo "<div class='alert alert-danger'>Erro ao agendar exame!</div>";
        }
    }
    ?>

    <!-- Formulário para Marcar Exame -->
    <form action="exames.php" method="POST">
        <div class="form-group">
            <label for="paciente_id">Paciente</label>
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

        <div class="form-group">
            <label for="tipo_exame_id">Tipo de Exame</label>
            <select name="tipo_exame_id" class="form-control" required>
                <option value="">Selecione um tipo de exame</option>
                <?php
                $sql = "SELECT * FROM tipos_exames";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                }
                ?>
            </select>
        </div>

        <input type="submit" class="btn btn-primary mt-2" value="Agendar Exame">
    </form>

    <!-- Exibição de Exames Agendados -->
    <h2 class="mt-4">Exames Agendados</h2>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Tipo de Exame</th>
                <th>Data/Hora</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT exames.id, pacientes.nome AS paciente, tipos_exames.nome AS tipo_exame, exames.data_hora, exames.status
                    FROM exames
                    JOIN pacientes ON exames.paciente_id = pacientes.id
                    JOIN tipos_exames ON exames.tipo_exame_id = tipos_exames.id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['paciente']}</td>
                        <td>{$row['tipo_exame']}</td>
                        <td>{$row['data_hora']}</td>
                        <td>{$row['status']}</td>
                        <td>
                            <a href='exames.php?opcao=alterar_status&id={$row['id']}&status=realizado' class='btn btn-success btn-sm'>Realizado</a>
                            <a href='exames.php?opcao=alterar_status&id={$row['id']}&status=cancelado' class='btn btn-warning btn-sm'>Cancelar</a>
                            <a href='exames.php?opcao=excluir&id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza?\")'>Excluir</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>Nenhum exame agendado.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
