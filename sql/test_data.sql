USE gestionale;

-- Insert rows into table 'atleta'
INSERT INTO atleta ( nome, cognome, username, password, data_nascita ) VALUES 
('alberto', 'rossi', 'alberto.rossi', 'demo', '1994-01-20'),
('luciano', 'paolo', 'luciano.paolo', 'demo', '1994-01-20'),
('valentina', 'paolo', 'valentina.paolo', 'demo', '1994-01-20');

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
INSERT INTO plicometria ( id_atleta, pettorale, addome, gamba, percentuale, data_rilevazione, note ) VALUES 
(1, 1, 1, 3, 15, '2019-05-19', 'Prova Prova');

-- Insert rows into table 'programma'
INSERT INTO programma ( id_atleta, data_inizio, data_fine, note ) VALUES 
(1, '2019-05-15', '2019-07-15', 'Prova'),
(2, '2019-04-15', '2019-07-15', 'Prova Prova');

-- Insert rows into table 'programmazione'
INSERT INTO programmazione ( id_esercizio, id_programma, giorno, serie, ripetizioni, carico, note ) VALUES 
(1,1,1,4,15,80,'Prova'),
(1,1,1,4,15,80,'Prova');

-- Insert rows into table 'peso'
INSERT INTO peso ( id_atleta, peso, note ) VALUES 
(1,90,'Prova'),
(2,88,'Prova');

-- Insert rows into table 'prestazione'
INSERT INTO prestazione ( id_atleta, id_esercizio, peso, note ) VALUES 
(1,1,900,'Prova'),
(2,1,888,'Prova');

-- Insert rows into table 'note'
INSERT INTO note ( id_atleta, note ) VALUES 
(1,'Prova'),
(2,'Prova');