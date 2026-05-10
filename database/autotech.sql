-- ============================================================
--  AutoTech Pro – Sistema de Gestão de Oficina Mecânica
--  Banco de Dados MySQL  |  Versão 2.0
--
--  Como importar no MySQL Workbench:
--    File > Open SQL Script > selecione este arquivo > ⚡ Execute
-- ============================================================

CREATE DATABASE IF NOT EXISTS autotech
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE autotech;

-- ============================================================
-- users
-- ============================================================
CREATE TABLE users (
    id             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name           VARCHAR(150) NOT NULL,
    email          VARCHAR(150) NOT NULL UNIQUE,
    password       VARCHAR(255) NOT NULL,
    role           ENUM('gerente','atendente','mecanico','cliente') NOT NULL DEFAULT 'cliente',
    remember_token VARCHAR(100) NULL,
    created_at     TIMESTAMP NULL,
    updated_at     TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- clientes
-- ============================================================
CREATE TABLE clientes (
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    BIGINT UNSIGNED NULL,
    nome       VARCHAR(150) NOT NULL,
    cpf        VARCHAR(14)  NOT NULL UNIQUE,
    telefone   VARCHAR(20)  NOT NULL,
    email      VARCHAR(150) NULL,
    endereco   VARCHAR(255) NULL,
    cidade     VARCHAR(100) NULL,
    estado     CHAR(2)      NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_cli_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- veiculos
-- ============================================================
CREATE TABLE veiculos (
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cliente_id BIGINT UNSIGNED NOT NULL,
    placa      VARCHAR(10)  NOT NULL UNIQUE,
    marca      VARCHAR(80)  NOT NULL,
    modelo     VARCHAR(80)  NOT NULL,
    ano        SMALLINT     NOT NULL,
    cor        VARCHAR(50)  NULL,
    chassi     VARCHAR(50)  NULL,
    km_atual   INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_vei_cli FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- mecanicos
-- ============================================================
CREATE TABLE mecanicos (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id       BIGINT UNSIGNED NULL,
    nome          VARCHAR(150) NOT NULL,
    cpf           VARCHAR(14)  NULL UNIQUE,
    telefone      VARCHAR(20)  NULL,
    especialidade VARCHAR(100) NULL,
    ativo         TINYINT(1)   NOT NULL DEFAULT 1,
    created_at    TIMESTAMP NULL,
    updated_at    TIMESTAMP NULL,
    CONSTRAINT fk_mec_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- servicos  (catálogo de serviços)
-- ============================================================
CREATE TABLE servicos (
    id             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome           VARCHAR(150)  NOT NULL,
    descricao      TEXT          NULL,
    categoria      VARCHAR(80)   NULL COMMENT 'mecanica | eletrica | funilaria | etc',
    valor_mao_obra DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    tempo_estimado INT           NULL     COMMENT 'em minutos',
    ativo          TINYINT(1)    NOT NULL DEFAULT 1,
    created_at     TIMESTAMP NULL,
    updated_at     TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- pecas  (estoque)
-- ============================================================
CREATE TABLE pecas (
    id             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome           VARCHAR(150)  NOT NULL,
    codigo         VARCHAR(80)   NULL UNIQUE,
    fabricante     VARCHAR(100)  NULL,
    preco_custo    DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    preco_venda    DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    estoque        INT           NOT NULL DEFAULT 0,
    estoque_minimo INT           NOT NULL DEFAULT 5,
    unidade        VARCHAR(20)   NOT NULL DEFAULT 'un',
    ativo          TINYINT(1)    NOT NULL DEFAULT 1,
    created_at     TIMESTAMP NULL,
    updated_at     TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- ordens_servico
-- ============================================================
CREATE TABLE ordens_servico (
    id                 BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    numero             VARCHAR(20)   NOT NULL UNIQUE COMMENT 'ex: OS-20260420-0001',
    cliente_id         BIGINT UNSIGNED NOT NULL,
    veiculo_id         BIGINT UNSIGNED NOT NULL,
    mecanico_id        BIGINT UNSIGNED NULL,
    status             ENUM(
                           'aberta',
                           'em_diagnostico',
                           'aguardando_aprovacao',
                           'aprovada',
                           'em_execucao',
                           'aguardando_pecas',
                           'finalizada',
                           'cancelada'
                       ) NOT NULL DEFAULT 'aberta',
    sintomas           TEXT          NULL,
    diagnostico        TEXT          NULL,
    observacoes        TEXT          NULL,
    km_entrada         INT UNSIGNED  NULL,
    valor_servicos     DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    valor_pecas        DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    valor_desconto     DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    valor_total        DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    aprovado_cliente   TINYINT(1)    NOT NULL DEFAULT 0,
    data_aprovacao     TIMESTAMP NULL,
    data_previsao      DATE      NULL,
    data_conclusao     TIMESTAMP NULL,
    created_at         TIMESTAMP NULL,
    updated_at         TIMESTAMP NULL,
    CONSTRAINT fk_os_cli FOREIGN KEY (cliente_id)  REFERENCES clientes(id),
    CONSTRAINT fk_os_vei FOREIGN KEY (veiculo_id)  REFERENCES veiculos(id),
    CONSTRAINT fk_os_mec FOREIGN KEY (mecanico_id) REFERENCES mecanicos(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- itens_os  (serviços e peças aplicados na OS)
-- ============================================================
CREATE TABLE itens_os (
    id             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    os_id          BIGINT UNSIGNED NOT NULL,
    tipo           ENUM('servico','peca') NOT NULL,
    servico_id     BIGINT UNSIGNED NULL,
    peca_id        BIGINT UNSIGNED NULL,
    descricao      VARCHAR(255)  NOT NULL,
    quantidade     DECIMAL(10,3) NOT NULL DEFAULT 1.000,
    valor_unitario DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    valor_total    DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    created_at     TIMESTAMP NULL,
    updated_at     TIMESTAMP NULL,
    CONSTRAINT fk_item_os  FOREIGN KEY (os_id)      REFERENCES ordens_servico(id) ON DELETE CASCADE,
    CONSTRAINT fk_item_svc FOREIGN KEY (servico_id) REFERENCES servicos(id)       ON DELETE SET NULL,
    CONSTRAINT fk_item_pca FOREIGN KEY (peca_id)    REFERENCES pecas(id)          ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- fotos_os  (entrada e saída — RN004)
-- ============================================================
CREATE TABLE fotos_os (
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    os_id      BIGINT UNSIGNED NOT NULL,
    path       VARCHAR(255) NOT NULL,
    tipo       ENUM('entrada','saida','processo') NOT NULL DEFAULT 'entrada',
    lado       ENUM('frontal','traseira','lateral_dir','lateral_esq','interior','outro') NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_foto_os FOREIGN KEY (os_id) REFERENCES ordens_servico(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- garantias  (RN001 – 90 dias mão de obra)
-- ============================================================
CREATE TABLE garantias (
    id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    os_id            BIGINT UNSIGNED NOT NULL,
    descricao        TEXT NOT NULL,
    data_inicio      DATE NOT NULL,
    data_fim         DATE NOT NULL,
    acionada         TINYINT(1) NOT NULL DEFAULT 0,
    data_acionamento TIMESTAMP NULL,
    observacao       TEXT NULL,
    created_at       TIMESTAMP NULL,
    updated_at       TIMESTAMP NULL,
    CONSTRAINT fk_gar_os FOREIGN KEY (os_id) REFERENCES ordens_servico(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tabelas internas do Laravel
-- ============================================================
CREATE TABLE password_reset_tokens (
    email      VARCHAR(150) PRIMARY KEY,
    token      VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE sessions (
    id            VARCHAR(255) PRIMARY KEY,
    user_id       BIGINT UNSIGNED NULL,
    ip_address    VARCHAR(45) NULL,
    user_agent    TEXT NULL,
    payload       LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX idx_sess_user     (user_id),
    INDEX idx_sess_activity (last_activity)
) ENGINE=InnoDB;

CREATE TABLE cache (
    `key`      VARCHAR(255) PRIMARY KEY,
    value      MEDIUMTEXT NOT NULL,
    expiration INT NOT NULL
) ENGINE=InnoDB;

-- ============================================================
-- Índices adicionais para performance
-- ============================================================
CREATE INDEX idx_os_status    ON ordens_servico(status);
CREATE INDEX idx_os_created   ON ordens_servico(created_at);
CREATE INDEX idx_peca_estoque ON pecas(estoque, estoque_minimo);

-- ============================================================
-- LIMPEZA (dados inconsistentes)
-- ============================================================
-- Remove usuários com role='cliente' que não possuem registro correspondente em `clientes`.
-- Isso evita o erro do sistema: "Usuário não tem perfil de cliente".
START TRANSACTION;

DELETE FROM users u
WHERE u.role = 'cliente'
  AND NOT EXISTS (
    SELECT 1 FROM clientes c WHERE c.user_id = u.id
  );

COMMIT;


-- ============================================================
-- DADOS INICIAIS
-- ============================================================

-- Usuário gerente padrão  (senha: password)
INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES
('Gerente AutoTech', 'gerente@autotech.com',
 '$2y$12$IWDNz1GFVJlXP0PH7a5YqO8fHGHkJvvgPlXqiYOQQwz6mYuT5WS7K',
 'gerente', NOW(), NOW());


-- Serviços
INSERT INTO servicos (nome, categoria, valor_mao_obra, tempo_estimado, ativo, created_at, updated_at) VALUES
('Troca de óleo e filtro',     'mecanica',  80.00,  30, 1, NOW(), NOW()),
('Alinhamento e balanceamento','mecanica', 120.00,  60, 1, NOW(), NOW()),
('Revisão de freios',          'mecanica', 150.00,  90, 1, NOW(), NOW()),
('Diagnóstico elétrico',       'eletrica', 100.00,  60, 1, NOW(), NOW()),
('Troca de correia dentada',   'mecanica', 200.00, 120, 1, NOW(), NOW()),
('Funilaria e pintura',        'funilaria',500.00, 480, 1, NOW(), NOW()),
('Revisão completa 10.000 km', 'mecanica', 350.00, 180, 1, NOW(), NOW()),
('Troca de velas',             'mecanica',  60.00,  40, 1, NOW(), NOW()),
('Higienização do ar-cond.',   'eletrica',  90.00,  45, 1, NOW(), NOW()),
('Troca de amortecedores',     'mecanica', 180.00, 100, 1, NOW(), NOW());

-- Peças
INSERT INTO pecas (nome, codigo, fabricante, preco_custo, preco_venda, estoque, estoque_minimo, ativo, created_at, updated_at) VALUES
('Filtro de óleo',       'FO-001', 'Fram',    15.00,  28.00, 20, 5, 1, NOW(), NOW()),
('Pastilha de freio',    'PF-001', 'Bosch',   45.00,  89.00, 10, 3, 1, NOW(), NOW()),
('Correia dentada',      'CD-001', 'Gates',   80.00, 150.00,  8, 2, 1, NOW(), NOW()),
('Vela de ignição',      'VI-001', 'NGK',     12.00,  22.00, 30, 8, 1, NOW(), NOW()),
('Filtro de ar',         'FA-001', 'Mann',    18.00,  35.00, 15, 4, 1, NOW(), NOW()),
('Fluido de freio 500ml','FF-001', 'Bosch',   12.00,  25.00, 12, 4, 1, NOW(), NOW()),
('Óleo 5W30 sintético',  'OL-001', 'Mobil',   28.00,  55.00, 25, 6, 1, NOW(), NOW()),
('Amortecedor diant.',   'AM-001', 'Monroe',  95.00, 180.00,  6, 2, 1, NOW(), NOW()),
('Disco de freio',       'DF-001', 'Fremax',  60.00, 115.00,  8, 2, 1, NOW(), NOW()),
('Filtro de combustível','FC-001', 'Mann',    22.00,  42.00, 10, 3, 1, NOW(), NOW());
