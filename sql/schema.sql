DROP SCHEMA gestionale;
CREATE SCHEMA gestionale;
USE gestionale;

CREATE TABLE atleta(
    id_atleta   SERIAL PRIMARY KEY,
    nome        VARCHAR(60) NOT NULL,
    cognome     VARCHAR(60) NOT NULL,
    username    VARCHAR(60) NOT NULL UNIQUE,
    password    VARCHAR(60) NOT NULL,
    deleted     BOOLEAN NOT NULL DEFAULT FALSE
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
    id_esercizio    BIGINT UNSIGNED NOT NULL REFERENCES esercizio (id_esercizio),
    serie           INT(10) NOT NULL,
    ripetizioni     INT(10) NOT NULL,
    note            TEXT,
    deleted         BOOLEAN NOT NULL DEFAULT FALSE
);