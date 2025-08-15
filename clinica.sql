CREATE DATABASE clinica_medica;
USE clinica_medica;

-- Tabela de Usuários 
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,  -- Armazenar hash da senha
    tipo ENUM('admin', 'medico', 'recepcionista') NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Convênios 
CREATE TABLE convenios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Pacientes (
CREATE TABLE pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    data_nascimento DATE NOT NULL,
    telefone VARCHAR(20),
    endereco TEXT,
    convenio_id INT,  
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (convenio_id) REFERENCES convenios(id) ON DELETE SET NULL
);

-- Inserção de Convênios
INSERT INTO convenios (nome, descricao) VALUES
('Unimed', 'Maior operadora de planos de saúde do Brasil.'),
('Amil', 'Uma das maiores operadoras de saúde do país.'),
('SulAmérica', 'Oferece planos de saúde individuais e empresariais.'),
('Bradesco Saúde', 'Planos de saúde com ampla rede credenciada.'),
('NotreDame Intermédica', 'Atua em todo o território nacional.'),
('Hapvida', 'Uma das maiores operadoras de saúde do Norte e Nordeste.'),
('Golden Cross', 'Tradicional operadora de planos de saúde.'),
('Prevent Senior', 'Foco em atendimento para a terceira idade.'),
('São Francisco Saúde', 'Atua principalmente na região Sudeste.'),
('Medial Saúde', 'Oferece planos de saúde com foco em qualidade.');

-- Tabela de Especialidades
CREATE TABLE especialidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
);

-- Inserção de Especialidades Médicas Comuns
INSERT INTO especialidades (nome) VALUES
('Cardiologia'),
('Dermatologia'),
('Endocrinologia'),
('Gastroenterologia'),
('Geriatria'),
('Ginecologia'),
('Hematologia'),
('Infectologia'),
('Nefrologia'),
('Neurologia'),
('Oftalmologia'),
('Oncologia'),
('Ortopedia'),
('Otorrinolaringologia'),
('Pediatria'),
('Psiquiatria'),
('Reumatologia'),
('Urologia'),
('Anestesiologia'),
('Cirurgia Geral'),
('Cirurgia Plástica'),
('Cirurgia Vascular'),
('Medicina do Trabalho'),
('Medicina Esportiva'),
('Medicina de Família e Comunidade'),
('Medicina Intensiva'),
('Radiologia'),
('Patologia'),
('Nutrologia');

-- Tabela de Médicos
CREATE TABLE medicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    crm VARCHAR(20) UNIQUE NOT NULL,
    telefone VARCHAR(20),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Relacionamento entre Médicos e Especialidades
CREATE TABLE medico_especialidade (
    medico_id INT NOT NULL,
    especialidade_id INT NOT NULL,
    PRIMARY KEY (medico_id, especialidade_id),
    FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE CASCADE,
    FOREIGN KEY (especialidade_id) REFERENCES especialidades(id) ON DELETE CASCADE
);

-- Tabela de Consultas
CREATE TABLE consultas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    medico_id INT NOT NULL,
    data_hora DATETIME NOT NULL,
    status ENUM('agendada', 'realizada', 'cancelada') DEFAULT 'agendada',
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE,
    FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE CASCADE
);

-- Tabela de Prontuário
CREATE TABLE prontuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    consulta_id INT NOT NULL,
    diagnostico TEXT,
    prescricao TEXT,
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (consulta_id) REFERENCES consultas(id) ON DELETE CASCADE
);

-- Criação da tabela tipos_exames
CREATE TABLE tipos_exames (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    condicoes TEXT
);

-- Inserção de Tipos de Exames
INSERT INTO tipos_exames (nome, condicoes) VALUES
('Hemograma Completo', 'Jejum de 8 horas.'),
('Glicemia em Jejum', 'Jejum de 8 horas.'),
('Colesterol Total e Frações', 'Jejum de 12 horas.'),
('Triglicerídeos', 'Jejum de 12 horas.'),
('Exame de Urina', 'Trazer urina em frasco estéril.'),
('Exame de Fezes', 'Trazer amostra em frasco específico.'),
('Eletrocardiograma (ECG)', 'Não é necessário jejum.'),
('Ultrassonografia Abdominal', 'Jejum de 6 horas.'),
('Ressonância Magnética', 'Não utilizar objetos metálicos.'),
('Tomografia Computadorizada', 'Jejum de 4 horas.'),
('Raio-X de Tórax', 'Não é necessário jejum.'),
('Teste Ergométrico', 'Usar roupas confortáveis e tênis.'),
('Mamografia', 'Não utilizar desodorante no dia do exame.'),
('Densitometria Óssea', 'Não é necessário jejum.'),
('Endoscopia Digestiva Alta', 'Jejum de 8 horas.'),
('Colonoscopia', 'Dieta líquida no dia anterior e jejum de 8 horas.'),
('Exame de Sangue Oculto nas Fezes', 'Trazer amostra em frasco específico.'),
('Teste de Gravidez (Beta HCG)', 'Não é necessário jejum.'),
('Exame de TSH e T4 Livre', 'Jejum de 4 horas.'),
('Exame de PSA', 'Jejum de 4 horas.'),
('Exame de Vitamina D', 'Não é necessário jejum.'),
('Exame de Ferritina', 'Jejum de 8 horas.'),
('Exame de Ácido Úrico', 'Jejum de 8 horas.'),
('Exame de Função Hepática', 'Jejum de 8 horas.'),
('Exame de Função Renal', 'Jejum de 8 horas.'),
('Exame de Coagulação', 'Jejum de 8 horas.');

-- Tabela de Exames
CREATE TABLE exames (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    tipo_exame_id INT NOT NULL,
    data_hora DATETIME NOT NULL,
    status ENUM('agendado', 'realizado', 'cancelado') DEFAULT 'agendado',
    resultado TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE,
    FOREIGN KEY (tipo_exame_id) REFERENCES tipos_exames(id) ON DELETE CASCADE
);

-- Tabela de Faturas
CREATE TABLE faturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    convenio_id INT NOT NULL,
    paciente_id INT NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    data_emissao DATE NOT NULL,
    status ENUM('pago', 'pendente') DEFAULT 'pendente',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (convenio_id) REFERENCES convenios(id) ON DELETE CASCADE,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE
);

-- Tabela de Pagamentos
CREATE TABLE pagamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fatura_id INT NOT NULL,
    valor_pago DECIMAL(10, 2) NOT NULL,
    data_pagamento DATE NOT NULL,
    forma_pagamento ENUM('dinheiro', 'cartão de crédito', 'cartão de débito', 'PIX', 'boleto') NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (fatura_id) REFERENCES faturas(id) ON DELETE CASCADE
);
