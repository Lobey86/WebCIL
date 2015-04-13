
--
-- Création de la table commentaires
--

CREATE TABLE notifications (
	id SERIAL NOT NULL PRIMARY KEY,
	user_id INTEGER NOT NULL REFERENCES users(id),
	content INTEGER NOT NULL,
	fiche_id INTEGER NOT NULL REFERENCES fiches(id),
	created DATE,
	modified DATE
);