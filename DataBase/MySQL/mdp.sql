DROP DATABASE IF EXISTS Synelia;
CREATE DATABASE IF NOT EXISTS Synelia;

Use Synelia;

CREATE TABLE IF NOT EXISTS User(
    userId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    status ENUM("admin" , "user") NOT NULL,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    birthDay DATE NOT NULL,
    mail VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(150) NOT NULL,
    verified BOOLEAN DEFAULT FALSE,
    urlToVerified VARCHAR(25) NULL ,
    verifieTime DATETIME NOT NULL,
    connectionToken VARCHAR(250) NULL,
    PRIMARY KEY(userId)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Basket(
    basketId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    userId INT UNSIGNED NOT NULL,
    productIds VARCHAR(50),
    PRIMARY KEY(basketId)
)ENGINE=InnoDB; 

CREATE TABLE IF NOT EXISTS Commande(
    commandeId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    quantity INT UNSIGNED DEFAULT 1,
    CommandeStatus ENUM("inDelivery" , "stable" , "delivered") NOT NULL  DEFAULT  "stable", 
    deliveryDate DATETIME NOT NULL,
    PRIMARY KEY(commandeId)
)ENGINE=InnoDB; 
CREATE TABLE IF NOT EXISTS PromotionCode(
    promotionId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    code VARCHAR(100) NOT NULL,
    soldes DECIMAL(3,2),
    expirationDate DATETIME NOT NULL,
    nbToProduct VARCHAR(10) DEFAULT "1",
    PRIMARY KEY(promotionId)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Product(
    produitId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    produitName VARCHAR(100) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    totalStock int UNSIGNED NOT NULL,
    marque VARCHAR(20) NOT NULL,
    description TEXT NULL,
    PRIMARY KEY(produitId)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Avis (
    avisId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    commentaire MEDIUMTEXT NULL,
    note TINYINT(1) UNSIGNED NOT NULL,
    dateAvis DATETIME NOT NULL,
    PRIMARY KEY(avisId)
)ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS Categorie(
    categorieId INT UNSIGNED NOT NULL AUTO_INCREMENT,
    categorieName VARCHAR(30) NOT NULL,
    CategorieDescription TEXT ,
    PRIMARY KEY(categorieId)
)ENGINE=InnoDB;

-- CREATE TABLE IF NOT EXISTS Commander(
--     commandeId INT UNSIGNED ,
--     basketId INT UNSIGNED,
--     PRIMARY KEY(basketId,commandeId)
-- )ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS Choisire(
    commandeId INT UNSIGNED ,
    produitId INT UNSIGNED,
    PRIMARY KEY(produitId,commandeId)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Promotion(
    commandeId INT UNSIGNED ,
    promotionId INT UNSIGNED,
    PRIMARY KEY(PromotionId,commandeId)
)ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS Noter(
    avisId INT UNSIGNED ,
    produitId INT UNSIGNED,
    PRIMARY KEY(produitId,avisId)
)ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS Categoriser(
    categorieId INT UNSIGNED ,
    produitId INT UNSIGNED,
    PRIMARY KEY(produitId,categorieId)
)ENGINE=InnoDB;

ALTER TABLE Basket 
ADD CONSTRAINT FK_Basket_userID   FOREIGN KEY (userId) REFERENCES User(userId) ;



-- ALTER TABLE Commander 
-- ADD CONSTRAINT FK_commander_commandeId 
-- FOREIGN KEY (commandeId) REFERENCES Commande(commandeId) ;

-- ALTER TABLE Commander 
-- ADD CONSTRAINT FK_commander_basketId   FOREIGN KEY (basketId) REFERENCES Basket(basketId) ;


ALTER TABLE Choisire 
ADD CONSTRAINT FK_choisire_commandeId  FOREIGN KEY (commandeId) REFERENCES Commande(commandeId) ;

ALTER TABLE Choisire 
ADD CONSTRAINT FK_choisire_produitId   FOREIGN KEY (produitId) REFERENCES Product(produitId) ;


ALTER TABLE Promotion 
ADD CONSTRAINT FK_promotion_commandeId FOREIGN KEY (commandeId) REFERENCES Commande(commandeId) ;
ALTER TABLE Promotion 
ADD CONSTRAINT FK_promotion_promotionId  FOREIGN KEY (promotionId) REFERENCES PromotionCode(promotionId) ;


ALTER TABLE Noter 
ADD CONSTRAINT FK_noter_produitId  FOREIGN KEY (produitId) REFERENCES Product(produitId) ;

ALTER TABLE Noter 
ADD CONSTRAINT FK_noter_avisId FOREIGN KEY (avisId) REFERENCES Avis(avisId) ;


ALTER TABLE Categoriser 
ADD CONSTRAINT FK_categoriser_avisId FOREIGN KEY (categorieId) REFERENCES Categorie(categorieId) ;

ALTER TABLE Categoriser 
ADD CONSTRAINT FK_categoriser_produitId  FOREIGN KEY (produitId) REFERENCES Product(produitId) ;


INSERT INTO Categorie(categorieName, CategorieDescription )  VALUES 
("Sportifs", "Cette catégorie est destinée aux amateurs de sport et d'activités physiques, avec des parfums frais et énergisants."),
("Luxueux", "Une catégorie pour ceux qui recherchent l'élégance et la sophistication, avec des parfums aux notes raffinées."),
("Fruités", "Des parfums aux senteurs sucrées et légères, idéaux pour ceux qui aiment les arômes fruités."),
("Floraux", "Une catégorie qui met en avant des senteurs florales délicates et élégantes, parfaite pour le printemps."),
("Boisés", "Pour les amateurs de senteurs naturelles et chaudes, inspirées des bois et de la terre."),
("Orientaux", "Des parfums exotiques et mystérieux avec des notes épicées et ambrées."),
("Citrus", "Des parfums frais et vivifiants, avec des notes d'agrumes parfaites pour les journées ensoleillées."),
("Gourmands", "Pour les amateurs de parfums sucrés rappelant les desserts et les gourmandises."),
("Unisexes", "Des parfums conçus pour convenir aussi bien aux hommes qu'aux femmes."),
("Été", "Une sélection de parfums légers et rafraîchissants, parfaits pour les journées chaudes."),
("Hiver", "Des parfums riches et chaleureux pour les saisons froides."),
("Vintage", "Pour ceux qui recherchent des parfums classiques et intemporels, inspirés des années passées."),
("Modernes", "Des senteurs innovantes et tendances pour le monde d'aujourd'hui."),
("Nocturnes", "Des parfums envoûtants et sensuels, idéaux pour les soirées."),
("Juvéniles", "Des parfums légers et fun, parfaits pour les plus jeunes ou pour ceux qui aiment des senteurs pétillantes.");



-- ALTER TABLE Commander 
-- ADD CONSTRAINT FK_commander_commandeId 
-- FOREIGN KEY (commandeId) REFERENCES Commande(commandeId) 
-- ON DELETE SET NULL 
-- ON UPDATE CASCADE;

-- ALTER TABLE Commander 
-- ADD CONSTRAINT FK_commander_basketId   FOREIGN KEY (basketId) REFERENCES Basket(basketId) ON DELETE SET NULL ON UPDATE CASCADE;


-- ALTER TABLE Choisire 
-- ADD CONSTRAINT FK_choisire_commandeId  FOREIGN KEY (commandeId) REFERENCES Commande(commandeId) ON DELETE SET NULL ON UPDATE CASCADE;

-- ALTER TABLE Choisire 
-- ADD CONSTRAINT FK_choisire_produitId   FOREIGN KEY (produitId) REFERENCES Product(produitId) ON DELETE SET NULL ON UPDATE CASCADE;


-- ALTER TABLE Promotion 
-- ADD CONSTRAINT FK_promotion_commandeId FOREIGN KEY (commandeId) REFERENCES Commande(commandeId) ON DELETE SET NULL ON UPDATE CASCADE;
-- ALTER TABLE Promotion 
-- ADD CONSTRAINT FK_promotion_promotionId  FOREIGN KEY (promotionId) REFERENCES Promotion(promotionId) ON DELETE SET NULL; ON UPDATE CASCADE


-- ALTER TABLE Noter 
-- ADD CONSTRAINT FK_noter_produitId  FOREIGN KEY (produitId) REFERENCES Product(produitId) ON DELETE SET NULL ON UPDATE CASCADE;

-- ALTER TABLE Noter 
-- ADD CONSTRAINT FK_noter_avisId FOREIGN KEY (avisId) REFERENCES Avis(avisId) ON DELETE SET NULL ON UPDATE CASCADE;


-- ALTER TABLE Categoriser 
-- ADD CONSTRAINT FK_categoriser_avisId FOREIGN KEY (categorieId) REFERENCES Categorie(categorieId) ON DELETE SET NULL ON UPDATE CASCADE;

-- ALTER TABLE Categoriser 
-- ADD CONSTRAINT FK_categoriser_produitId  FOREIGN KEY (produitId) REFERENCES Product(produitId) ON DELETE SET NULL ON UPDATE CASCADE;
