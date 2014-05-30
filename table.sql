DROP TABLE 'entries';
CREATE TABLE 'entries' ( 'id' INTEGER PRIMARY KEY AUTOINCREMENT, 'login_name' VARCHAR(20) NOT NULL UNIQUE, 'kg_id' INTEGER NOT NULL, 'title' VARCHAR(256) NOT NULL, 'class_id' INTEGER NOT NULL, 'grade_id' INTEGER NOT NULL, 'handout_name' VARCHAR(256) NOT NULL, 'slide_name' VARCHAR(256) NOT NULL, 'password' VARCHAR(128) NOT NULL );

DROP TABLE 'kg';
CREATE TABLE 'kg' ( 'id' INTEGER PRIMARY KEY AUTOINCREMENT, 'value' VARCHAR(20) NOT NULL UNIQUE );

DROP TABLE 'classes';
CREATE TABLE 'classes' ( 'id' INTEGER PRIMARY KEY AUTOINCREMENT, 'value' VARCHAR(5) NOT NULL UNIQUE );

DROP TABLE 'grades';
CREATE TABLE 'grades' ( 'id' INTEGER PRIMARY KEY AUTOINCREMENT, 'value' VARCHAR(2) NOT NULL UNIQUE );

DROP TABLE 'comments';
CREATE TABLE 'comments' ( 'id' INTEGER PRIMARY KEY AUTOINCREMENT, 'entry_id' INTEGER NOT NULL, 'pf' INTEGER NOT NULL DEFAULT 0, 'name' VARCHAR(20) NOT NULL, 'value' VARCHAR(1024), 'timestamp' TIMESTAMP DEFAULT (DATETIME('now', 'localtime')) );


INSERT INTO 'kg'(value) VALUES ('AQUA');
INSERT INTO 'kg'(value) VALUES ('ARCH');
INSERT INTO 'kg'(value) VALUES ('Auto-ID');
INSERT INTO 'kg'(value) VALUES ('Bianco');
INSERT INTO 'kg'(value) VALUES ('CPSF');
INSERT INTO 'kg'(value) VALUES ('HACCAR');
INSERT INTO 'kg'(value) VALUES ('ISC');
INSERT INTO 'kg'(value) VALUES ('Life-Cloud');
INSERT INTO 'kg'(value) VALUES ('LINK');
INSERT INTO 'kg'(value) VALUES ('MEMSYS');
INSERT INTO 'kg'(value) VALUES ('NECO');
INSERT INTO 'kg'(value) VALUES ('Sociable Robots');

INSERT INTO 'classes'(value) VALUES ('WIP');
INSERT INTO 'classes'(value) VALUES ('TERM');
INSERT INTO 'classes'(value) VALUES ('卒論');

INSERT INTO 'grades'(value) VALUES ('B1');
INSERT INTO 'grades'(value) VALUES ('B2');
INSERT INTO 'grades'(value) VALUES ('B3');
INSERT INTO 'grades'(value) VALUES ('B4');

