-- ============================================================
-- Catálogo básico de Marcas & Modelos (para filtrar marca->modelos)
-- Cole este arquivo no final do database/autotech.sql
-- ============================================================

USE autotech;

-- ---------------------------
-- Tabelas
-- ---------------------------

CREATE TABLE IF NOT EXISTS marcas_veiculos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL UNIQUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS modelos_veiculos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    marca_id INT UNSIGNED NOT NULL,
    nome VARCHAR(120) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_mod_vei_marca FOREIGN KEY (marca_id) REFERENCES marcas_veiculos(id) ON DELETE CASCADE,
    UNIQUE KEY uq_mod_vei_nome_por_marca (marca_id, nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------
-- Inserts: 20 marcas famosas + 10 modelos por marca
-- (Observação: nomes em PT-BR/uso comum. Você pode ajustar depois.)
-- ---------------------------

-- Marcas
INSERT IGNORE INTO marcas_veiculos (nome, created_at, updated_at) VALUES
('Fiat', NOW(), NOW()),
('Volkswagen', NOW(), NOW()),
('Chevrolet', NOW(), NOW()),
('Ford', NOW(), NOW()),
('Renault', NOW(), NOW()),
('Toyota', NOW(), NOW()),
('Honda', NOW(), NOW()),
('Nissan', NOW(), NOW()),
('Hyundai', NOW(), NOW()),
('Kia', NOW(), NOW()),
('Peugeot', NOW(), NOW()),
('Citroën', NOW(), NOW()),
('Jeep', NOW(), NOW()),
('Chery', NOW(), NOW()),
('Suzuki', NOW(), NOW()),
('BMW', NOW(), NOW()),
('Mercedes-Benz', NOW(), NOW()),
('Audi', NOW(), NOW()),
('Lexus', NOW(), NOW()),
('Subaru', NOW(), NOW());

-- Modelos
-- Fiat
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (SELECT 'Fiat' AS marca, 'Palio' AS nome UNION ALL SELECT 'Fiat','Uno' UNION ALL SELECT 'Fiat','Punto' UNION ALL SELECT 'Fiat','Argo' UNION ALL SELECT 'Fiat','Cronos' UNION ALL SELECT 'Fiat','Mobi' UNION ALL SELECT 'Fiat','Siena' UNION ALL SELECT 'Fiat','Strada' UNION ALL SELECT 'Fiat','Toro' UNION ALL SELECT 'Fiat','Doblò') v
WHERE m.nome = v.marca;

-- Volkswagen
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (SELECT 'Volkswagen' AS marca, 'Gol' AS nome UNION ALL SELECT 'Volkswagen','Voyage' UNION ALL SELECT 'Volkswagen','Saveiro' UNION ALL SELECT 'Volkswagen','Parati' UNION ALL SELECT 'Volkswagen','Polo' UNION ALL SELECT 'Volkswagen','Virtus' UNION ALL SELECT 'Volkswagen','T-Cross' UNION ALL SELECT 'Volkswagen','Tiguan' UNION ALL SELECT 'Volkswagen','Jetta' UNION ALL SELECT 'Volkswagen','Passat') v
WHERE m.nome = v.marca;

-- Chevrolet
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (SELECT 'Chevrolet' AS marca, 'Onix' AS nome UNION ALL SELECT 'Chevrolet','Prisma' UNION ALL SELECT 'Chevrolet','Cobalt' UNION ALL SELECT 'Chevrolet','Cruze' UNION ALL SELECT 'Chevrolet','Tracker' UNION ALL SELECT 'Chevrolet','Equinox' UNION ALL SELECT 'Chevrolet','S10' UNION ALL SELECT 'Chevrolet','Montana' UNION ALL SELECT 'Chevrolet','Spin' UNION ALL SELECT 'Chevrolet','Joy' ) v
WHERE m.nome = v.marca;

-- Ford
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT m.id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (
    SELECT 'Ka' AS nome
    UNION ALL SELECT 'Ka+'
    UNION ALL SELECT 'Ecosport'
    UNION ALL SELECT 'Focus'
    UNION ALL SELECT 'Fusion'
    UNION ALL SELECT 'Ranger'
    UNION ALL SELECT 'Edge'
    UNION ALL SELECT 'Territory'
    UNION ALL SELECT 'Bronco'
    UNION ALL SELECT 'Mustang'
) v
WHERE m.nome = 'Ford';


-- Renault
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (SELECT 'Renault' AS marca, 'Sandero' AS nome UNION ALL SELECT 'Renault','Logan' UNION ALL SELECT 'Renault','Stepway' UNION ALL SELECT 'Renault','Duster' UNION ALL SELECT 'Renault','Captur' UNION ALL SELECT 'Renault','Kwid' UNION ALL SELECT 'Renault','Oroch' UNION ALL SELECT 'Renault','Fluence' UNION ALL SELECT 'Renault','Megane' UNION ALL SELECT 'Renault','Zoe') v
WHERE m.nome = v.marca;

-- Toyota
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (SELECT 'Toyota' AS marca, 'Corolla' AS nome UNION ALL SELECT 'Toyota','Etios' UNION ALL SELECT 'Toyota','Yaris' UNION ALL SELECT 'Toyota','Prius' UNION ALL SELECT 'Toyota','Hilux' UNION ALL SELECT 'Toyota','SW4' UNION ALL SELECT 'Toyota','RAV4' UNION ALL SELECT 'Toyota','Camry' UNION ALL SELECT 'Toyota','Fielder' UNION ALL SELECT 'Toyota','Land Cruiser') v
WHERE m.nome = v.marca;

-- Honda
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (SELECT 'Honda' AS marca, 'Civic' AS nome UNION ALL SELECT 'Honda','City' UNION ALL SELECT 'Honda','Fit' UNION ALL SELECT 'Honda','HR-V' UNION ALL SELECT 'Honda','CR-V' UNION ALL SELECT 'Honda','Accord' UNION ALL SELECT 'Honda','Civic Type R' UNION ALL SELECT 'Honda','WR-V' UNION ALL SELECT 'Honda','Passport' UNION ALL SELECT 'Honda','Pilot') v
WHERE m.nome = v.marca;

-- Nissan
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (SELECT 'Nissan' AS marca, 'Versa' AS nome UNION ALL SELECT 'Nissan','Sentra' UNION ALL SELECT 'Nissan','Tiida' UNION ALL SELECT 'Nissan','March' UNION ALL SELECT 'Nissan','Kicks' UNION ALL SELECT 'Nissan','Rogue' UNION ALL SELECT 'Nissan','X-Trail' UNION ALL SELECT 'Nissan','Frontier' UNION ALL SELECT 'Nissan','Frontier Attack' UNION ALL SELECT 'Nissan','Leaf') v
WHERE m.nome = v.marca;

-- Hyundai
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (SELECT 'Hyundai' AS marca, 'HB20' AS nome UNION ALL SELECT 'Hyundai','HB20S' UNION ALL SELECT 'Hyundai','Creta' UNION ALL SELECT 'Hyundai','Tucson' UNION ALL SELECT 'Hyundai','Elantra' UNION ALL SELECT 'Hyundai','Veloster' UNION ALL SELECT 'Hyundai','Santa Fe' UNION ALL SELECT 'Hyundai','Azera' UNION ALL SELECT 'Hyundai','i30' UNION ALL SELECT 'Hyundai','Kona') v
WHERE m.nome = v.marca;

-- Kia
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (SELECT 'Kia' AS marca, 'Sportage' AS nome UNION ALL SELECT 'Kia','Sorento' UNION ALL SELECT 'Kia','Seltos' UNION ALL SELECT 'Kia','Cerato' UNION ALL SELECT 'Kia','Rio' UNION ALL SELECT 'Kia','K5' UNION ALL SELECT 'Kia','Stonic' UNION ALL SELECT 'Kia','Telluride' UNION ALL SELECT 'Kia','Mohave' UNION ALL SELECT 'Kia','EV6') v
WHERE m.nome = v.marca;

-- Peugeot
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (SELECT 'Peugeot' AS marca, '208' AS nome UNION ALL SELECT 'Peugeot','2008' UNION ALL SELECT 'Peugeot','3008' UNION ALL SELECT 'Peugeot','408' UNION ALL SELECT 'Peugeot','308' UNION ALL SELECT 'Peugeot','Partner' UNION ALL SELECT 'Peugeot','Rifter' UNION ALL SELECT 'Peugeot','508' UNION ALL SELECT 'Peugeot','208 GT' UNION ALL SELECT 'Peugeot','3008 Allure') v
WHERE m.nome = v.marca;

-- Citroën
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (SELECT 'Citroën' AS marca, 'C3' AS nome UNION ALL SELECT 'Citroën','C4' UNION ALL SELECT 'Citroën','C4 Cactus' UNION ALL SELECT 'Citroën','Aircross' UNION ALL SELECT 'Citroën','C5 Aircross' UNION ALL SELECT 'Citroën','Berlingo' UNION ALL SELECT 'Citroën','Jumpy' UNION ALL SELECT 'Citroën','C3 Picasso' UNION ALL SELECT 'Citroën','Grand C4 Picasso' UNION ALL SELECT 'Citroën','e-C4') v
WHERE m.nome = v.marca;

-- Jeep
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (SELECT 'Jeep' AS marca, 'Renegade' AS nome UNION ALL SELECT 'Jeep','Compass' UNION ALL SELECT 'Jeep','Commander' UNION ALL SELECT 'Jeep','Cherokee' UNION ALL SELECT 'Jeep','Grand Cherokee' UNION ALL SELECT 'Jeep','Wrangler' UNION ALL SELECT 'Jeep','Gladiator' UNION ALL SELECT 'Jeep','Renegade Turbo' UNION ALL SELECT 'Jeep','Compass Trailhawk' UNION ALL SELECT 'Jeep','Willys') v
WHERE m.nome = v.marca;

-- Chery
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (SELECT 'Chery' AS marca, 'Tiggo 2' AS nome UNION ALL SELECT 'Chery','Tiggo 3' UNION ALL SELECT 'Chery','Tiggo 5' UNION ALL SELECT 'Chery','Tiggo 7' UNION ALL SELECT 'Chery','Arrizo 5' UNION ALL SELECT 'Chery','Arrizo 6' UNION ALL SELECT 'Chery','Arrizo 8' UNION ALL SELECT 'Chery','QQ' UNION ALL SELECT 'Chery','Karry' UNION ALL SELECT 'Chery','Face') v
WHERE m.nome = v.marca;

-- Suzuki
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (SELECT 'Suzuki' AS marca, 'Jimny' AS nome UNION ALL SELECT 'Suzuki','Vitara' UNION ALL SELECT 'Suzuki','S-Cross' UNION ALL SELECT 'Suzuki','Swift' UNION ALL SELECT 'Suzuki','Baleno' UNION ALL SELECT 'Suzuki','Grand Vitara' UNION ALL SELECT 'Suzuki','Erzatz' UNION ALL SELECT 'Suzuki','Ignis' UNION ALL SELECT 'Suzuki','Celerio' UNION ALL SELECT 'Suzuki','Carry') v
WHERE m.nome = v.marca;

-- BMW
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (SELECT 'BMW' AS marca, '320i' AS nome UNION ALL SELECT 'BMW','318i' UNION ALL SELECT 'BMW','X1' UNION ALL SELECT 'BMW','X3' UNION ALL SELECT 'BMW','X5' UNION ALL SELECT 'BMW','X6' UNION ALL SELECT 'BMW','Z4' UNION ALL SELECT 'BMW','M3' UNION ALL SELECT 'BMW','M5' UNION ALL SELECT 'BMW','iX') v
WHERE m.nome = v.marca;

-- Mercedes-Benz
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (SELECT 'Mercedes-Benz' AS marca, 'C180' AS nome UNION ALL SELECT 'Mercedes-Benz','C200' UNION ALL SELECT 'Mercedes-Benz','E200' UNION ALL SELECT 'Mercedes-Benz','E300' UNION ALL SELECT 'Mercedes-Benz','GLA' UNION ALL SELECT 'Mercedes-Benz','GLB' UNION ALL SELECT 'Mercedes-Benz','CLA' UNION ALL SELECT 'Mercedes-Benz','GLE' UNION ALL SELECT 'Mercedes-Benz','GLC' UNION ALL SELECT 'Mercedes-Benz','S500') v
WHERE m.nome = v.marca;

-- Audi
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (SELECT 'Audi' AS marca, 'A3' AS nome UNION ALL SELECT 'Audi','A4' UNION ALL SELECT 'Audi','A5' UNION ALL SELECT 'Audi','A6' UNION ALL SELECT 'Audi','Q3' UNION ALL SELECT 'Audi','Q5' UNION ALL SELECT 'Audi','Q7' UNION ALL SELECT 'Audi','TT' UNION ALL SELECT 'Audi','RS3' UNION ALL SELECT 'Audi','E-tron') v
WHERE m.nome = v.marca;

-- Lexus
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (SELECT 'Lexus' AS marca, 'IS' AS nome UNION ALL SELECT 'Lexus','ES' UNION ALL SELECT 'Lexus','GS' UNION ALL SELECT 'Lexus','LS' UNION ALL SELECT 'Lexus','NX' UNION ALL SELECT 'Lexus','RX' UNION ALL SELECT 'Lexus','GX' UNION ALL SELECT 'Lexus','UX' UNION ALL SELECT 'Lexus','LM' UNION ALL SELECT 'Lexus','LX') v
WHERE m.nome = v.marca;

-- Subaru
INSERT IGNORE INTO modelos_veiculos (marca_id, nome, created_at, updated_at)
SELECT id, v.nome, NOW(), NOW()
FROM marcas_veiculos m
JOIN (SELECT 'Subaru' AS marca, 'Impreza' AS nome UNION ALL SELECT 'Subaru','Crosstrek' UNION ALL SELECT 'Subaru','Forester' UNION ALL SELECT 'Subaru','Outback' UNION ALL SELECT 'Subaru','Legacy' UNION ALL SELECT 'Subaru','WRX' UNION ALL SELECT 'Subaru','BRZ' UNION ALL SELECT 'Subaru','Ascent' UNION ALL SELECT 'Subaru','Tribeca' UNION ALL SELECT 'Subaru','XV') v
WHERE m.nome = v.marca;

-- ============================================================
-- Fim
-- ============================================================

