-- -----------------------------------------------------
-- Schema onbus
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS onbus DEFAULT CHARACTER SET utf8 ;
USE onbus ;

-- -----------------------------------------------------
-- Tabela onibus
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS onbus.onibus (
  id_onibus INT NOT NULL AUTO_INCREMENT,
  placa VARCHAR(10) NOT NULL,
  lotacao_max INT(3) NOT NULL,
  numero_onibus VARCHAR(6) NOT NULL,
  PRIMARY KEY (id_onibus))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Tabela usuario
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS onbus.usuario (
  id_usuario INT NOT NULL AUTO_INCREMENT,
  login VARCHAR(45) NOT NULL,
  senha VARCHAR(45) NOT NULL,
  email VARCHAR(255) NOT NULL,
  cpf INT(11) NOT NULL,
  nascimento DATE NOT NULL DEFAULT '9999-12-31',
  pne BOOLEAN NULL,
  nivel int(1) DEFAULT '0',
  PRIMARY KEY (id_usuario),
  UNIQUE INDEX login_UNIQUE (login ASC),
  UNIQUE INDEX cpf_UNIQUE (cpf ASC),
  UNIQUE INDEX email_UNIQUE (email ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Tabela ponto
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS onbus.ponto (
  id_ponto INT NOT NULL AUTO_INCREMENT,
  latitude_ponto DOUBLE NOT NULL,
  longitude_ponto DOUBLE NOT NULL,
  descricao_ponto TEXT NOT NULL, /* Definir pontos de referência
  do ponto de ônibus, tal como nome da rua e sentido de tráfego */
  PRIMARY KEY (id_ponto))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Tabela linha
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS onbus.linha (
  id_linha INT NOT NULL AUTO_INCREMENT,
  num_linha VARCHAR(6) NOT NULL,
  variacao_linha VARCHAR(2) NOT NULL DEFAULT '00',
  desc_linha TEXT NOT NULL,
  PRIMARY KEY (id_linha))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Tabela itinerario
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS onbus.itinerario (
  id_itinerario INT NOT NULL AUTO_INCREMENT,
  id_linha INT NOT NULL,
  id_ponto INT NOT NULL,
  PRIMARY KEY (id_itinerario),
  INDEX fk_itinerario_linha1_idx (id_linha ASC),
  INDEX fk_itinerario_ponto1_idx (id_ponto ASC),
  CONSTRAINT fk_itinerario_linha1
    FOREIGN KEY (id_linha)
    REFERENCES onbus.linha (id_linha)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_itinerario_ponto1
    FOREIGN KEY (id_ponto)
    REFERENCES onbus.ponto (id_ponto)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Tabela peticao
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS onbus.peticao (
  id_peticao INT NOT NULL AUTO_INCREMENT,
  hora_pedido TIMESTAMP NOT NULL,
  id_ponto INT NOT NULL,
  id_usuario INT NOT NULL,
  id_linha INT NOT NULL,
  PRIMARY KEY (id_peticao),
  INDEX fk_peticao_ponto1_idx (id_ponto ASC),
  INDEX fk_peticao_usuario1_idx (id_usuario ASC),
  INDEX fk_peticao_linha1_idx (id_linha ASC),
  CONSTRAINT fk_peticao_ponto1
    FOREIGN KEY (id_ponto)
    REFERENCES onbus.ponto (id_ponto)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_peticao_usuario1
    FOREIGN KEY (id_usuario)
    REFERENCES onbus.usuario (id_usuario)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_peticao_linha1
    FOREIGN KEY (id_linha)
    REFERENCES onbus.linha (id_linha)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Tabela onibus_linha
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS onbus.onibus_linha (
  id_onibus_linha INT NOT NULL AUTO_INCREMENT,
  id_onibus INT NOT NULL,
  id_linha INT NOT NULL,
  PRIMARY KEY (id_onibus_linha),
  INDEX fk_onibus_linha_onibus1_idx (id_onibus ASC),
  INDEX fk_onibus_linha_linha1_idx (id_linha ASC),
  CONSTRAINT fk_onibus_linha_onibus1
    FOREIGN KEY (id_onibus)
    REFERENCES onbus.onibus (id_onibus)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_onibus_linha_linha1
    FOREIGN KEY (id_linha)
    REFERENCES onbus.linha (id_linha)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Tabela estado_onibus
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS onbus.estado_onibus (
  id_estado_onibus INT NOT NULL AUTO_INCREMENT,
  id_onibus_linha INT NOT NULL,
  velocidade_act FLOAT(5,2) NOT NULL,
  latitude_act DOUBLE NOT NULL,
  longitude_act DOUBLE NOT NULL,
  lotacao_act INT(3) NOT NULL,
  hora_inf_recebida TIMESTAMP NOT NULL,
  PRIMARY KEY (id_estado_onibus),
  INDEX fk_estado_onibus_onibus_linha1_idx (id_onibus_linha ASC),
  CONSTRAINT fk_estado_onibus_onibus_linha1
    FOREIGN KEY (id_onibus_linha)
    REFERENCES onbus.onibus_linha (id_onibus_linha)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

