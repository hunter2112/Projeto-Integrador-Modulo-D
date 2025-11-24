-- Criar o banco de dados
CREATE DATABASE IF NOT EXISTS plantas_db
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

-- Usar o banco
USE plantas_db;

-- Criar a tabela plantas
create table plantas(
    id              int             primary key auto_increment,
    nome            varchar(100)    not null,
    nome_cientifico varchar(150),
    descricao       text,
    caracteristicas text,
    uso             text,
    imagem_url      varchar(255)
);

insert into plantas (nome, nome_cientifico, descricao, caracteristicas, uso, imagem_url) 
values
('Alecrim', 'Rosmarinus officinalis', 'Arbusto aromático muito usado na culinária e fitoterapia.', 'Estimula a memória, tem ação antioxidante e anti-inflamatória.', 'Melhora a concentração, usado em óleos essenciais e chás.', NULL ),
('Camomila','Matricaria chamomilla','Planta com flores pequenas, usada para chás calmantes.','Calmante, digestiva, anti-inflamatória.','Tratamento de insônia, ansiedade, cólicas e irritações leves.',NULL),
('Hortelã','Mentha piperita','Erva aromática comum em chás e remédios naturais.','Estimulante digestivo, alivia náuseas e tem efeito refrescante.','Usada em chás, xaropes e aromaterapia.',NULL),
('Erva-cidreira','Melissa officinalis','Planta de folhas aromáticas usada para relaxamento.','Calmante, ansiolítica, antiespasmódica.','Combate insônia, ansiedade, tensão e cólicas.',NULL),
('Boldo','Peumus boldus','Usado tradicionalmente para problemas digestivos e hepáticos.','Estimulante hepático, digestivo e levemente laxativo.','Utilizado em infusões para má digestão e problemas do fígado.',NULL),
('Arnica','Arnica montana','Usada externamente para contusões e dores musculares.','Anti-inflamatória, cicatrizante, analgésica.','Aplicação tópica em hematomas, torções e dores musculares.',NULL),
('Babosa (Aloe Vera)','Aloe vera','Planta suculenta com gel medicinal nas folhas.','Cicatrizante, hidratante, anti-inflamatória.','Tratamento de queimaduras, feridas e uso cosmético.',NULL),
('Guaco','Mikania glomerata','Planta trepadeira com folhas aromáticas.','Expectorante, broncodilatadora.','Utilizado no alívio da tosse e doenças respiratórias.',NULL),
('Gengibre','Zingiber officinale','Rizoma muito usado como tempero e remédio natural.','Antioxidante, anti-inflamatório, digestivo.','Combate náuseas, resfriados e problemas gastrointestinais.',NULL),
('Urtiga','Urtica dioica','Planta com folhas urticantes, rica em nutrientes.','Diurética, anti-inflamatória.','Usada no tratamento de artrite, anemia e problemas urinários.',NULL);
);
