-- Criar o banco de dados
CREATE DATABASE IF NOT EXISTS plantas_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  
-- ESSA PORRA RESOLVE O PROBLEMA DEMONIACO MALDITO MILENAR INRESOSIVEL AAAA DE ACENTUAÇÃO DO BANCO DE DADOS 
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

SET GLOBAL character_set_server=utf8mb4;
SET GLOBAL collation_server=utf8mb4_unicode_ci;

USE plantas_db;

-- Criar a tabela plantas
CREATE TABLE plantas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    nome_cientifico VARCHAR(150),
    descricao TEXT,
    caracteristicas TEXT,
    uso TEXT,
    imagem VARCHAR(255)  -- adicionada pois seu PHP usa esta coluna
);

-- Inserir dados
INSERT INTO plantas (nome, nome_cientifico, descricao, caracteristicas, uso) VALUES
('Alecrim', 'Rosmarinus officinalis', 'Arbusto aromático muito usado na culinária e fitoterapia.', 'Estimula a memória, tem ação antioxidante e anti-inflamatória.', 'Melhora a concentração, usado em óleos essenciais e chás.'),
('Camomila','Matricaria chamomilla','Planta com flores pequenas, usada para chás calmantes.','Calmante, digestiva, anti-inflamatória.','Tratamento de insônia, ansiedade, cólicas e irritações leves.'),
('Hortelã','Mentha piperita','Erva aromática comum em chás e remédios naturais.','Estimulante digestivo, alivia náuseas e tem efeito refrescante.','Usada em chás, xaropes e aromaterapia.'),
('Erva-cidreira','Melissa officinalis','Planta de folhas aromáticas usada para relaxamento.','Calmante, ansiolítica, antiespasmódica.','Combate insônia, ansiedade, tensão e cólicas.'),
('Boldo','Peumus boldus','Usado tradicionalmente para problemas digestivos e hepáticos.','Estimulante hepático, digestivo e levemente laxativo.','Utilizado em infusões para má digestão e problemas do fígado.'),
('Arnica','Arnica montana','Usada externamente para contusões e dores musculares.','Anti-inflamatória, cicatrizante, analgésica.','Aplicação tópica em hematomas, torções e dores musculares.'),
('Babosa (Aloe Vera)','Aloe vera','Planta suculenta com gel medicinal nas folhas.','Cicatrizante, hidratante, anti-inflamatória.','Tratamento de queimaduras, feridas e uso cosmético.'),
('Guaco','Mikania glomerata','Planta trepadeira com folhas aromáticas.','Expectorante, broncodilatadora.','Utilizado no alívio da tosse e doenças respiratórias.'),
('Gengibre','Zingiber officinale','Rizoma muito usado como tempero e remédio natural.','Antioxidante, anti-inflamatório, digestivo.','Combate náuseas, resfriados e problemas gastrointestinais.'),
('Urtiga','Urtica dioica','Planta com folhas urticantes, rica em nutrientes.','Diurética, anti-inflamatória.','Usada no tratamento de artrite, anemia e problemas urinários.');

