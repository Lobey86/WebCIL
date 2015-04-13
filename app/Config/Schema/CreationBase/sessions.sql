
--
-- Création de la table commentaires
--

CREATE TABLE sessions (
	id SERIAL NOT NULL PRIMARY KEY,
	user_id INTEGER NOT NULL REFERENCES users(id),
	ip VARCHAR(20) NOT NULL,
	created DATE,
	modified DATE
);