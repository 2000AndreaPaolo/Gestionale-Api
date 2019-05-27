DROP SCHEMA gestionale;
CREATE SCHEMA gestionale;
USE gestionale;

CREATE TABLE atleta(
    id_atleta       SERIAL PRIMARY KEY,
    nome            VARCHAR(60) NOT NULL,
    cognome         VARCHAR(60) NOT NULL,
    username        VARCHAR(60) NOT NULL UNIQUE,
    password        VARCHAR(60) NOT NULL,
    data_nascita    DATE NOT NULL,
    deleted         BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE coach(
    id_coach    SERIAL PRIMARY KEY,
    nome        VARCHAR(60) NOT NULL,
    cognome     VARCHAR(60) NOT NULL,
    username    VARCHAR(60) NOT NULL UNIQUE,
    password    VARCHAR(60) NOT NULL,
    deleted     BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE esercizio(
    id_esercizio        SERIAL PRIMARY KEY,
    descrizione         VARCHAR(60) NOT NULL,
    id_gruppoMuscolare  BIGINT UNSIGNED NOT NULL REFERENCES gruppoMuscolare (id_gruppoMuscolare),
    deleted             BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE gruppoMuscolare(
    id_gruppoMuscolare    SERIAL PRIMARY KEY,
    descrizione           VARCHAR(60) NOT NULL,
    deleted               BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE scheda(
    id_scheda       SERIAL PRIMARY KEY,
    nome            VARCHAR(60) NOT NULL,
    durata          INT(10) NOT NULL,
    data_inizio     DATE NOT NULL,
    data_fine       DATE NOT NULL,
    id_atleta       BIGINT UNSIGNED NOT NULL REFERENCES atleta (id_atleta),
    deleted         BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE progressione(
    id_progressione SERIAL PRIMARY KEY,
    id_scheda       BIGINT UNSIGNED NOT NULL REFERENCES scheda (id_scheda),
    id_esercizio    BIGINT UNSIGNED NOT NULL REFERENCES esercizio (id_esercizio),
    giorno          INT(10) NOT NULL,
    serie           INT(10) NOT NULL,
    ripetizioni     INT(10) NOT NULL,
    note            TEXT,
    deleted         BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE plicometria(
    id_plicometria      SERIAL PRIMARY KEY,
    id_atleta           BIGINT UNSIGNED NOT NULL REFERENCES atleta (id_atleta),
    pettorale           FLOAT(10) NOT NULL,
    addome              FLOAT(10) NOT NULL,
    gamba               FLOAT(10) NOT NULL,
    percentuale         FLOAT(10) NOT NULL,
    data_rilevazione    DATE NOT NULL,
    note                TEXT,
    deleted             BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE programma(
    id_programma    SERIAL PRIMARY KEY,
    id_atleta       BIGINT UNSIGNED NOT NULL REFERENCES atleta (id_atleta),
    data_inizio     DATE NOT NULL,
    data_fine       DATE NOT NULL,
    note            TEXT,
    deleted         BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE programmazione(
    id_programmazione   SERIAL PRIMARY KEY,
    id_programma        BIGINT UNSIGNED NOT NULL REFERENCES programma (id_programma),
    id_esercizio        BIGINT UNSIGNED NOT NULL REFERENCES esercizio (id_esercizio),
    giorno              INT(10) NOT NULL,
    settimana           INT(10) NOT NULL,
    data                DATE NOT NULL,
    serie               INT(10) NOT NULL,
    ripetizioni         INT(10) NOT NULL,
    carico              INT(10) NOT NULL,
    note                TEXT,
    deleted             BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE peso(
    id_peso    SERIAL PRIMARY KEY,
    id_atleta   BIGINT UNSIGNED NOT NULL REFERENCES atleta (id_atleta),
    peso        FLOAT(10) NOT NULL,
    data        DATE DEFAULT NOW(),
    note        TEXT,
    deleted     BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE note(
    id_note    SERIAL PRIMARY KEY,
    id_atleta   BIGINT UNSIGNED NOT NULL REFERENCES atleta (id_atleta),
    data        DATE DEFAULT NOW(),
    note        TEXT,
    deleted     BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE prestazione(
    id_prestazioni      SERIAL PRIMARY KEY,
    id_atleta           BIGINT UNSIGNED NOT NULL REFERENCES atleta (id_atleta),
    id_esercizio        BIGINT UNSIGNED NOT NULL REFERENCES esercizio (id_esercizio),
    peso                FLOAT(10) NOT NULL,
    data                DATE DEFAULT NOW(),
    note                TEXT,
    deleted             BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE VIEW atleti_eliminati AS SELECT * FROM atleta WHERE deleted = true;
CREATE VIEW note_eliminate AS SELECT * FROM note WHERE deleted = true;
CREATE VIEW peso_eliminato AS SELECT * FROM peso WHERE deleted = true;
CREATE VIEW plicometrie_eliminate AS SELECT * FROM plicometria WHERE deleted = true;
CREATE VIEW prestazioni_eliminate AS SELECT * FROM prestazione WHERE deleted = true;
CREATE VIEW programmi_eliminati AS SELECT * FROM programma WHERE deleted = true;
CREATE VIEW programmazioni_eliminati AS SELECT * FROM programmazione WHERE deleted = true;
CREATE VIEW schede_eliminate AS SELECT * FROM scheda WHERE deleted = true;
CREATE VIEW progressioni_eliminati AS SELECT * FROM progressione WHERE deleted = true;