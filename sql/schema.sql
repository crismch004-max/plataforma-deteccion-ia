-- Esquema de la plataforma (PostgreSQL). Ejecutar una vez en el SQL Editor de Neon.
CREATE TABLE IF NOT EXISTS participantes (
    id               SERIAL PRIMARY KEY,
    codigo           VARCHAR(12) NOT NULL UNIQUE,
    edad             SMALLINT    NOT NULL CHECK (edad BETWEEN 18 AND 99),
    nivel_educativo  VARCHAR(30) NOT NULL,
    familiaridad_ia  SMALLINT    NOT NULL CHECK (familiaridad_ia BETWEEN 1 AND 5),
    consentimiento   BOOLEAN     NOT NULL DEFAULT TRUE,
    creado_en        TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS items (
    id          SERIAL PRIMARY KEY,
    codigo      VARCHAR(12) NOT NULL UNIQUE,
    tipo        VARCHAR(10) NOT NULL CHECK (tipo IN ('imagen', 'audio')),
    categoria   VARCHAR(30) NOT NULL,
    etiqueta    VARCHAR(10) NOT NULL CHECK (etiqueta IN ('real', 'ia')),
    herramienta VARCHAR(40),
    archivo     VARCHAR(120) NOT NULL
);

CREATE TABLE IF NOT EXISTS respuestas (
    id              SERIAL PRIMARY KEY,
    participante_id INTEGER     NOT NULL REFERENCES participantes(id) ON DELETE CASCADE,
    item_id         INTEGER     NOT NULL REFERENCES items(id),
    respuesta       VARCHAR(10) NOT NULL CHECK (respuesta IN ('real', 'ia')),
    acierto         BOOLEAN     NOT NULL,
    confianza       SMALLINT    NOT NULL CHECK (confianza BETWEEN 1 AND 5),
    tiempo_ms       INTEGER,
    creado_en       TIMESTAMPTZ NOT NULL DEFAULT now(),
    UNIQUE (participante_id, item_id)
);
