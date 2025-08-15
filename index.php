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

<!-- Falta coisa para adicionar, pontos principais inclementados mas falta coisas para ser totalmente funcional -->

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

<!-- Seção do Cabeçalho -->
<div class="hero-section">
    <div class="container">
        <h1>Bem-vindo à Clínica Médica</h1>
        <p>Cuidando da sua saúde com excelência e dedicação.</p>
    </div>
</div>

<!-- Seção Sobre Nós -->
<div class="section bg-light">
    <div class="container">
        <h2>Sobre Nós</h2>
        <p>
            A Clínica Médica é referência em atendimento de qualidade, oferecendo serviços de saúde com profissionais altamente qualificados e tecnologia de ponta. Nosso compromisso é proporcionar o melhor cuidado para você e sua família.
        </p>
    </div>
</div>

<!-- Seção Nossos Serviços -->
<div class="section">
    <div class="container">
        <h2>Nossos Serviços</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <img src="images/consulta.png" class="card-img-top" alt="Consulta Médica">
                    <div class="card-body">
                        <h5 class="card-title">Consultas Médicas</h5>
                        <p class="card-text">Agende consultas com especialistas em diversas áreas da medicina.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="images/exames.png" class="card-img-top" alt="Exames">
                    <div class="card-body">
                        <h5 class="card-title">Exames</h5>
                        <p class="card-text">Realizamos exames laboratoriais e de imagem com precisão e agilidade.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="images/vacina.jpg" class="card-img-top" alt="Vacinação">
                    <div class="card-body">
                        <h5 class="card-title">Vacinação</h5>
                        <p class="card-text">Mantenha sua saúde em dia com nosso serviço de vacinação.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Seção Equipe Médica -->
<div class="section bg-light">
    <div class="container">
        <h2>Nossa Equipe</h2>
        <div class="row">
            
            <div class="col-md-4">
                <div class="card">
                    <img src="images/coringa2.png" class="card-img-top" alt="Coringa">
                    <div class="card-body">
                        <h5 class="card-title">Jack Oswald White</h5>
                        <p class="card-text">Especialista em medicina avançada e tecnologia médica.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <img src="images/strange.jpg" class="card-img-top" alt="Stephen Strange">
                    <div class="card-body">
                        <h5 class="card-title">Stephen Strange</h5>
                        <p class="card-text">Neurologista e pesquisadora em neurociência.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <img src="images/house.jpg" class="card-img-top" alt="Dr. House">
                    <div class="card-body">
                        <h5 class="card-title">Dr. House</h5>
                        <p class="card-text">Especialista em diagnósticos complexos e medicina interna.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rodapé -->
<footer class="bg-primary text-white text-center py-3">
    <p>&copy; 2025 Clínica Médica. Todos os direitos reservados.</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>