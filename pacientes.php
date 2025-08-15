<?php include 'conexao.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacientes</title>
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
    <h2>Lista de Pacientes</h2>
    
    <?php
    // Lógica para excluir paciente
    if (isset($_GET['opcao'])) {
        if ($_GET['opcao'] == 'excluir' && isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "DELETE FROM pacientes WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Erro na preparação da consulta: " . $conn->error);
            }
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Paciente excluído com sucesso!</div>";
            } else {
                echo "<div class='alert alert-danger'>Erro ao excluir paciente: " . $stmt->error . "</div>";
            }
        }
    }

    // Lógica para editar paciente
    if (isset($_GET['opcao'])) {
        if ($_GET['opcao'] == 'editar' && isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM pacientes WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Erro na preparação da consulta: " . $conn->error);
            }
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $nome = $row['nome'];
                $cpf = $row['cpf'];
                $data_nascimento = $row['data_nascimento'];
                $telefone = $row['telefone'];
                $endereco = $row['endereco'];
                $convenio_id = $row['convenio_id'];
            }
        }
    }

    // Lógica para salvar/atualizar paciente
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $cpf = $_POST['cpf'];
        $data_nascimento = $_POST['data_nascimento'];
        $telefone = $_POST['telefone'];
        $endereco = $_POST['endereco'];
        $convenio_id = $_POST['convenio'];
        $id = $_POST['id'];

        if ($id) {
            // Atualizar paciente
            $sql = "UPDATE pacientes SET nome = ?, cpf = ?, data_nascimento = ?, telefone = ?, endereco = ?, convenio_id = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Erro na preparação da consulta: " . $conn->error);
            }
            $stmt->bind_param("sssssii", $nome, $cpf, $data_nascimento, $telefone, $endereco, $convenio_id, $id);
        } else {
            // Inserir novo paciente
            $sql = "INSERT INTO pacientes (nome, cpf, data_nascimento, telefone, endereco, convenio_id) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die("Erro na preparação da consulta: " . $conn->error);
            }
            $stmt->bind_param("sssssi", $nome, $cpf, $data_nascimento, $telefone, $endereco, $convenio_id);
        }

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Paciente salvo com sucesso!</div>";
        } else {
            echo "<div class='alert alert-danger'>Erro ao salvar paciente: " . $stmt->error . "</div>";
        }
    }
    ?>

    <form action="pacientes.php" method="POST">
        <div class="form-group">
            <label for="nome">Nome</label>
            <input required value="<?php if (isset($nome)) echo $nome; ?>" type="text" class="form-control" id="nome" name="nome" placeholder="Informe o nome">
        </div>
        <div class="form-group">
            <label for="cpf">CPF</label>
            <input required value="<?php if (isset($cpf)) echo $cpf; ?>" type="text" class="form-control" id="cpf" name="cpf" placeholder="Informe o CPF">
        </div>
        <div class="form-group">
            <label for="data_nascimento">Data de Nascimento</label>
            <input required value="<?php if (isset($data_nascimento)) echo $data_nascimento; ?>" type="date" class="form-control" id="data_nascimento" name="data_nascimento">
        </div>
        <div class="form-group">
            <label for="telefone">Telefone</label>
            <input required value="<?php if (isset($telefone)) echo $telefone; ?>" type="text" class="form-control" id="telefone" name="telefone" placeholder="Informe o telefone">
        </div>
        <div class="form-group">
            <label for="endereco">Endereço</label>
            <textarea required class="form-control" id="endereco" name="endereco" placeholder="Informe o endereço"><?php if (isset($endereco)) echo $endereco; ?></textarea>
        </div>
        <div class="form-group">
            <label for="convenio">Convênio</label>
            <select required class="form-control" id="convenio" name="convenio">
                <option value="">Selecione um convênio</option>
                <?php
                $sql_convenios = "SELECT * FROM convenios";
                $result_convenios = $conn->query($sql_convenios);
                if ($result_convenios === false) {
                    die("Erro na consulta: " . $conn->error);
                }
                if ($result_convenios->num_rows > 0) {
                    while ($row_convenio = $result_convenios->fetch_assoc()) {
                        $selected = (isset($convenio_id) && $convenio_id == $row_convenio['id']) ? 'selected' : '';
                        echo "<option value='{$row_convenio['id']}' $selected>{$row_convenio['nome']}</option>";
                    }
                }
                ?>
            </select>
        </div>
        <input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>">
        <br>
        <input type="submit" class="btn btn-primary" value="Salvar">
        <a href="pacientes.php" class="btn btn-success mb-0">Limpar</a>
    </form>

    <div class="row" style="margin-top: 20px;">
        <h2 class="display-6">Pacientes Cadastrados</h2>
        <table class="table">
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Data de Nascimento</th>
                <th>Telefone</th>
                <th>Endereço</th>
                <th>Convênio</th>
                <th>Ações</th>
            </tr>
            <?php
            $sql = "SELECT p.*, c.nome as convenio_nome FROM pacientes p LEFT JOIN convenios c ON p.convenio_id = c.id";
            $result = $conn->query($sql);

            if ($result === false) {
                die("Erro na consulta: " . $conn->error);
            }

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['nome']}</td>
                        <td>{$row['cpf']}</td>
                        <td>{$row['data_nascimento']}</td>
                        <td>{$row['telefone']}</td>
                        <td>{$row['endereco']}</td>
                        <td>{$row['convenio_nome']}</td>
                        <td>
                            <a href='pacientes.php?opcao=editar&id={$row['id']}' class='btn btn-warning btn-sm'>Editar</a>
                            <a href='pacientes.php?opcao=excluir&id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza?\")'>Excluir</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>Nenhum paciente cadastrado.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>