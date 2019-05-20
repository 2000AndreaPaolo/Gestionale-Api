USE gestionale;

-- Insert rows into table 'atleta'
INSERT INTO atleta ( nome, cognome, username, password ) VALUES 
('alberto', 'rossi', 'alberto.rossi', 'demo'),
('luciano', 'paolo', 'luciano.paolo', 'demo'),
('valentina', 'paolo', 'valentina.paolo', 'demo'),
('gabriele', 'cirri', 'gabriele.cirri', 'demo');

-- Insert rows into table 'coach'
INSERT INTO coach ( nome, cognome, username, password ) VALUES 
('andrea', 'paolo', 'andrea.paolo', 'demo');

-- Insert rows into table 'esercizio'
INSERT INTO esercizio ( descrizione, id_gruppoMuscolare ) VALUES 
('Panca piana', 1),
('Squat', 2),
('Stacco', 3),
('Lat machine', 3);

-- Insert rows into table 'gruppoMuscolare'
INSERT INTO gruppoMuscolare ( descrizione ) VALUES 
('Petto'),
('Quadricipiti'),
('Dorso');

-- Insert rows into table 'scheda'
INSERT INTO scheda ( nome, durata, data_inizio, data_fine, id_atleta ) VALUES 
('scheda1', 6, '2019-05-15', '2019-07-15', 1),
('scheda2', 6, '2019-05-15', '2019-07-15', 1);

-- Insert rows into table 'progressione'
INSERT INTO progressione ( id_esercizio, id_scheda, giorno, serie, ripetizioni, note ) VALUES 
(1, 1, 1, 3, 10, 'Prova Prova'),
(1, 2, 2, 3, 10, 'Prova prova');

-- Insert rows into table 'progressione'
INSERT INTO plicometria ( id_atleta, pettorale, addome, gamba, note ) VALUES 
(1, 1, 1, 3, 'Prova Prova');