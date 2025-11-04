CREATE DATABASE industria_alimenticia_db;
USE industria_alimenticia_db;

CREATE TABLE usuario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE tarefas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idUsuario INT NOT NULL,
    nomeSetor VARCHAR(100) NOT NULL,
    descricao VARCHAR(100) NOT NULL,
    dataCadastro DATE NOT NULL,
    status ENUM ('fazer', 'feito', 'pronto') NOT NULL,
    FOREIGN KEY (idUsuario) REFERENCES usuario(id),
    prioridade ENUM ('baixa', 'media', 'grande')NOT NULL
);

INSERT INTO usuario (nome, email, senha) VALUES
('Carla Souza', 'carla.souza@alimentos.com', '$2y$10$examplehashedpassword1'),
('Rodrigo Felippe', 'rodrigo.felippe@alimentos.com', '$2y$10$examplehashedpassword2'),
('Daniel Melo', 'daniel.melo@alimentos.com', '$2y$10$examplehashedpassword3');

INSERT INTO tarefas (idUsuario, nomeSetor, descricao, dataCadastro, status, prioridade) VALUES
(1, 'Produção', 'Verificar o estoque de farinha para o lote 123.', '2025-12-20', 'fazer', 'media'),
(1, 'Produção', 'Ajustar a temperatura do forno para biscoitos sabor queijo.', '2025-01-21', 'pronto', 'grande'),
(2, 'Qualidade', 'Realizar análise de pH do novo lote de suco de laranja.', '2025-03-25', 'feito', 'grande'),
(2, 'Qualidade', 'Auditoria de higiene na linha de embalagens primárias.', '2025-10-20', 'fazer', 'media'),
(3, 'Logística', 'Preparar o embarque de 500 caixas de leite para o Nordeste.', '2025-10-30', 'fazer', 'grande'),
(3, 'Logística', 'Inventário de paletes no armazém refrigerado.', '2025-12-25', 'feito', 'baixa');