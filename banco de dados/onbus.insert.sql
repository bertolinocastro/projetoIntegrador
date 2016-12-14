USE onbus;

INSERT INTO usuario ( login, senha, email, cpf, nascimento, pne, nivel) VALUES 
( 'admin', 'admin', 'admin@gmail.com', '123123122', '9995-12-16', '0', '1'), 
( 'user', 'user', 'user@gmail.com', '132131', '9999-12-31', '0', '0');

INSERT INTO linha (num_linha, variacao_linha, desc_linha) VALUES
('1053', '00', 'BARRA 3 - ESTAÇÃO MUSSURUNGA'),
('1420', '00', 'BOCA DA MATA - PITUBA'),
('1055', '00', 'ESTAÇÃO MUSSURUNGA - RIBEIRA'),
('1055', '01', 'ESTAÇÃO MUSSURUNGA - LARGO DO TANQUE'),
('1628', '00', 'RIO SENA - LAPA'),
('1022', '00', 'PRAIA DO FLAMENGO - LAPA'),
('1342', '00', 'ESTAÇÃO PIRAJÁ - RIBEIRA'),
('1342', '01', 'RIBEIRA - RUA DIRETA'),
('0410', '00', 'SIEIRO - AEROPORTO'),
('1052', '00', 'ESTAÇÃO MUSSURUNGA - BARRA 2');


INSERT INTO onibus (placa,lotacao_max,numero_onibus) VALUES 
("I5W6W3",50,"16601"),("U6N2C3",59,"00965"),("U9U0P3",51,"27089"),
("L5U7G2",52,"33200"),("F9X9M9",58,"82584"),("Y3L0U0",57,"00328"),
("O9G1E3",56,"34414"),("R6V4L2",56,"47133"),("I9E1P2",57,"03766"),
("M1H4N9",54,"70134"),("B8Q7U2",59,"30162"),("X5M2D1",51,"41974"),
("N3Y6P2",55,"78770"),("F5C0N9",58,"71798"),("B5D2C6",52,"69493"),
("E6C1F1",57,"90283"),("U6W0W3",51,"68368"),("E4U4V4",53,"29866"),
("L6T5K0",50,"74262"),("E9E0F2",60,"71988"),("Y0K7X1",58,"85703"),
("T4T1B8",57,"20589"),("X6D4V0",51,"20628"),("Y2J2F4",54,"54681"),
("D4E4S3",50,"82324"),("E1K3V0",55,"29591"),("X4N1Q5",54,"05622"),
("C3S8A9",60,"93294"),("V1L0D8",50,"73298"),("R3E5X7",57,"36712");



INSERT INTO onibus_linha (id_onibus, id_linha) VALUES
(1, 1),(2, 2),(3, 3),(4, 4), (5, 5);



INSERT INTO ponto (latitude_ponto, longitude_ponto, descricao_ponto) VALUES
(-12.884260999999999, -38.4680234, "PONTO DO RIO SENA"), 
(-12.938218,-38.387323,"PONTO DO SENAI CIMATEC");






