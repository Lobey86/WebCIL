
--
-- Création de la table commentaires
--

CREATE TABLE commentaires (
	id SERIAL NOT NULL PRIMARY KEY,
	etat_fiches_id INTEGER NOT NULL REFERENCES etat_fiches(id) ON DELETE CASCADE,
	content TEXT NOT NULL,
	user_id INTEGER NOT NULL REFERENCES users(id),
	destinataire_id INTEGER NOT NULL REFERENCES users(id),
	created DATE,
	modified DATE
);