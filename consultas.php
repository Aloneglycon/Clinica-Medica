<?php include 'conexao.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas</title>
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
    <h2>Lista de Consultas</h2>

    <?php
    // Lógica para excluir consulta
    if (isset($_GET['opcao'])) {
        if ($_GET['opcao'] == 'excluir' && isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "DELETE FROM consultas WHERE id = $id";
            if ($conn->query($sql)) {
                echo "<div class='alert alert-success'>Consulta excluída com sucesso!</div>";
            } else {
                echo "<div class='alert alert-danger'>Erro ao excluir consulta!</div>";
            }
        }
    }

    // Lógica para editar consulta
    if (isset($_GET['opcao'])) {
        if ($_GET['opcao'] == 'editar' && isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM consultas WHERE id = $id";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $paciente_id = $row['paciente_id'];
                $medico_id = $row['medico_id'];
                $data_hora = $row['data_hora'];
                $status = $row['status'];
            }
        }
    }

    // Lógica para salvar/atualizar consulta
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Verifica se o formulário enviado é de consulta ou exame
        if (isset($_POST['paciente_id']) && isset($_POST['medico_id'])) {
            // Formulário de consulta
            $paciente_id = $_POST['paciente_id'];
            $medico_id = $_POST['medico_id'];
            $data_hora = $_POST['data_hora'];
            $status = $_POST['status'];
            $id = $_POST['id'];

            if ($id) {
                // Atualizar consulta
                $sql = "UPDATE consultas SET paciente_id = '$paciente_id', medico_id = '$medico_id', data_hora = '$data_hora', status = '$status' WHERE id = $id";
            } else {
                // Inserir nova consulta
                $sql = "INSERT INTO consultas (paciente_id, medico_id, data_hora, status) VALUES ('$paciente_id', '$medico_id', '$data_hora', '$status')";
            }

            if ($conn->query($sql)) {
                echo "<div class='alert alert-success'>Consulta salva com sucesso!</div>";
            } else {
                echo "<div class='alert alert-danger'>Erro ao salvar consulta!</div>";
            }
        } elseif (isset($_POST['tipo_exame_id'])) {
            // Formulário de exame
            $paciente_id = $_POST['paciente_id'];
            $tipo_exame_id = $_POST['tipo_exame_id'];
            $data_hora = date('Y-m-d H:i:s'); // Data e hora atuais
            $status = 'agendado'; // Status padrão para exames

            // Inserir novo exame
            $sql = "INSERT INTO exames (paciente_id, tipo_exame_id, data_hora, status) VALUES ('$paciente_id', '$tipo_exame_id', '$data_hora', '$status')";

            if ($conn->query($sql)) {
                echo "<div class='alert alert-success'>Exame marcado com sucesso!</div>";
            } else {
                echo "<div class='alert alert-danger'>Erro ao marcar exame!</div>";
            }
        }
    }
    ?>

    <!-- Formulário de Consulta -->
    <form action="consultas.php" method="POST">
        <div class="form-group">
            <label for="paciente_id">Paciente</label>
            <select name="paciente_id" class="form-control" required>
                <option value="">Selecione um paciente</option>
                <?php
                $sql = "SELECT * FROM pacientes";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $selected = (isset($paciente_id) && $paciente_id == $row['id']) ? "selected" : "";
                    echo "<option value='{$row['id']}' $selected>{$row['nome']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="medico_id">Médico</label>
            <select name="medico_id" class="form-control" required>
                <option value="">Selecione um médico</option>
                <?php
                $sql = "SELECT * FROM medicos";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $selected = (isset($medico_id) && $medico_id == $row['id']) ? "selected" : "";
                    echo "<option value='{$row['id']}' $selected>{$row['nome']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="data_hora">Data/Hora</label>
            <input required value="<?php if (isset($data_hora)) echo $data_hora; ?>" type="datetime-local" class="form-control" id="data_hora" name="data_hora">
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control" required>
                <option value="agendada" <?php if (isset($status) && $status == 'agendada') echo "selected"; ?>>Agendada</option>
                <option value="realizada" <?php if (isset($status) && $status == 'realizada') echo "selected"; ?>>Realizada</option>
                <option value="cancelada" <?php if (isset($status) && $status == 'cancelada') echo "selected"; ?>>Cancelada</option>
            </select>
        </div>
        <input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>">
        <br>
        <input type="submit" class="btn btn-primary" value="Salvar">
        <a href="consultas.php" class="btn btn-success mb-0">Limpar</a>
    </form>

     <!-- Seção de Prontuário -->
     <div class="row mt-4">
        <h2>Prontuário do Paciente</h2>
        <form method="GET" action="consultas.php">
            <label for="paciente_id">Selecione um paciente:</label>
            <select name="paciente_id" class="form-control" onchange="this.form.submit()">
                <option value="">Escolha um paciente</option>
                <?php
                $sql = "SELECT * FROM pacientes";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $selected = (isset($_GET['paciente_id']) && $_GET['paciente_id'] == $row['id']) ? "selected" : "";
                    echo "<option value='{$row['id']}' $selected>{$row['nome']}</option>";
                }
                ?>
            </select>
        </form>

        <?php
        if (isset($_GET['paciente_id']) && !empty($_GET['paciente_id'])) {
            $paciente_id = $_GET['paciente_id'];
            $sql = "SELECT consultas.data_hora, consultas.status, prontuario.diagnostico 
                    FROM consultas 
                    LEFT JOIN prontuario ON consultas.id = prontuario.consulta_id 
                    WHERE consultas.paciente_id = $paciente_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table class='table mt-3'>
                        <tr>
                            <th>Data/Hora</th>
                            <th>Status</th>
                            <th>Diagnóstico</th>
                        </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['data_hora']}</td>
                            <td>{$row['status']}</td>
                            <td>{$row['diagnostico']}</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='mt-3'>Nenhum prontuário encontrado para este paciente.</p>";
            }
        }
        ?>
    </div>

    <!-- Seção de Exames -->
    <div class="row mt-4">
        <h2>Marcar Exame</h2>
        <form action="consultas.php" method="POST">
            <input type="hidden" name="paciente_id" value="<?php if (isset($paciente_id)) echo $paciente_id; ?>">
            <select name="tipo_exame_id" class="form-control" required>
                <option value="">Selecione o exame</option>
                <?php
                $sql = "SELECT * FROM tipos_exames";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                }
                ?>
            </select>
            <input type="submit" class="btn btn-primary mt-2" value="Marcar Exame">
        </form>
    </div>

    <!-- Seção de Faturas -->
    <div class="row mt-4">
        <h2>Faturas</h2>
        <?php
        if (isset($_GET['paciente_id'])) {
            $paciente_id = $_GET['paciente_id'];
            $sql = "SELECT f.*, c.nome AS convenio FROM faturas f JOIN convenios c ON f.convenio_id = c.id WHERE f.paciente_id = $paciente_id";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<p>Convênio: {$row['convenio']} - Valor: R$ {$row['valor']} - Status: {$row['status']}</p>";
                }
            } else {
                echo "<p>Nenhuma fatura encontrada para este paciente.</p>";
            }
        }
        ?>
    </div>

    <!-- Lista de Consultas Agendadas -->
    <div class="row" style="margin-top: 20px;">
        <h2 class="display-6">Consultas Agendadas</h2>
        <table class="table">
            <tr>
                <th>Paciente</th>
                <th>Médico</th>
                <th>Data/Hora</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
            <?php
            $sql = "SELECT consultas.id, pacientes.nome AS paciente, medicos.nome AS medico, consultas.data_hora, consultas.status 
                    FROM consultas
                    JOIN pacientes ON consultas.paciente_id = pacientes.id
                    JOIN medicos ON consultas.medico_id = medicos.id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['paciente']}</td>
                        <td>{$row['medico']}</td>
                        <td>{$row['data_hora']}</td>
                        <td>{$row['status']}</td>
                        <td>
                            <a href='consultas.php?opcao=editar&id={$row['id']}' class='btn btn-warning btn-sm'>Editar</a>
                            <a href='consultas.php?opcao=excluir&id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza?\")'>Excluir</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>Nenhuma consulta agendada.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>