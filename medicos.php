<?php include 'conexao.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Médicos</title>
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
    <h2>Cadastro de Médicos</h2>
    
    <?php
    // Lógica para excluir médico
    if (isset($_GET['opcao']) && $_GET['opcao'] == 'excluir' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "DELETE FROM medicos WHERE id = $id";
        if ($conn->query($sql)) {
            echo "<div class='alert alert-success'>Médico excluído com sucesso!</div>";
        } else {
            echo "<div class='alert alert-danger'>Erro ao excluir médico!</div>";
        }
    }

    // Lógica para editar médico
    $id = $nome = $crm = $telefone = "";
    $especialidades_selecionadas = [];
    if (isset($_GET['opcao']) && $_GET['opcao'] == 'editar' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM medicos WHERE id = $id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $nome = $row['nome'];
            $crm = $row['crm'];
            $telefone = $row['telefone'];

            // Buscar especialidades do médico
            $sqlEspecialidades = "SELECT especialidade_id FROM medico_especialidade WHERE medico_id = $id";
            $resultEspecialidades = $conn->query($sqlEspecialidades);
            while ($esp = $resultEspecialidades->fetch_assoc()) {
                $especialidades_selecionadas[] = $esp['especialidade_id'];
            }
        }
    }

    // Lógica para salvar/atualizar médico
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $crm = $_POST['crm'];
        $telefone = $_POST['telefone'];
        $especialidades = $_POST['especialidades'] ?? [];
        $id = $_POST['id'];

        if ($id) {
            // Atualizar médico
            $sql = "UPDATE medicos SET nome = '$nome', crm = '$crm', telefone = '$telefone' WHERE id = $id";
            $conn->query($sql);
            
            // Remover especialidades antigas e adicionar as novas
            $conn->query("DELETE FROM medico_especialidade WHERE medico_id = $id");
            foreach ($especialidades as $especialidade_id) {
                $conn->query("INSERT INTO medico_especialidade (medico_id, especialidade_id) VALUES ($id, $especialidade_id)");
            }
        } else {
            // Inserir novo médico
            $sql = "INSERT INTO medicos (nome, crm, telefone) VALUES ('$nome', '$crm', '$telefone')";
            if ($conn->query($sql)) {
                $id = $conn->insert_id;
                foreach ($especialidades as $especialidade_id) {
                    $conn->query("INSERT INTO medico_especialidade (medico_id, especialidade_id) VALUES ($id, $especialidade_id)");
                }
            }
        }

        echo "<div class='alert alert-success'>Médico salvo com sucesso!</div>";
    }
    ?>

    <form action="medicos.php" method="POST">
        <div class="form-group">
            <label for="nome">Nome</label>
            <input required value="<?php echo $nome; ?>" type="text" class="form-control" id="nome" name="nome">
        </div>
        <div class="form-group">
            <label for="crm">CRM</label>
            <input required value="<?php echo $crm; ?>" type="text" class="form-control" id="crm" name="crm">
        </div>
        <div class="form-group">
            <label for="telefone">Telefone</label>
            <input value="<?php echo $telefone; ?>" type="text" class="form-control" id="telefone" name="telefone">
        </div>
        <div class="form-group">
            <label for="especialidades">Especialidades</label>
            <select name="especialidades[]" multiple class="form-control">
                <?php
                $sql = "SELECT * FROM especialidades";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $selected = in_array($row['id'], $especialidades_selecionadas) ? "selected" : "";
                    echo "<option value='{$row['id']}' $selected>{$row['nome']}</option>";
                }
                ?>
            </select>
        </div>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <br>
        <input type="submit" class="btn btn-primary" value="Salvar">
        <a href="medicos.php" class="btn btn-success mb-0">Limpar</a>
    </form>

    <div class="row mt-4">
        <h2>Médicos Cadastrados</h2>
        <table class="table">
            <tr>
                <th>Nome</th>
                <th>CRM</th>
                <th>Telefone</th>
                <th>Especialidades</th>
                <th>Ações</th>
            </tr>
            <?php
            $sql = "SELECT m.*, GROUP_CONCAT(e.nome SEPARATOR ', ') AS especialidades 
                    FROM medicos m
                    LEFT JOIN medico_especialidade me ON m.id = me.medico_id
                    LEFT JOIN especialidades e ON me.especialidade_id = e.id
                    GROUP BY m.id";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['nome']}</td>
                    <td>{$row['crm']}</td>
                    <td>{$row['telefone']}</td>
                    <td>{$row['especialidades']}</td>
                    <td>
                        <a href='medicos.php?opcao=editar&id={$row['id']}' class='btn btn-warning btn-sm'>Editar</a>
                        <a href='medicos.php?opcao=excluir&id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza?\")'>Excluir</a>
                    </td>
                </tr>";
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>
