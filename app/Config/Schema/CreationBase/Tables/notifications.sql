--
-- Création de la table commentaires
--
CREATE TABLE notifications (
	id SERIAL NOT NULL PRIMARY KEY,
	user_id INTEGER NOT NULL REFERENCES users(id),
	content INTEGER NOT NULL,
	fiche_id INTEGER NOT NULL REFERENCES fiches(id),
	vu BOOLEAN NOT NULL,
	created DATE,
	modified DATE,
        afficher BOOLEAN
);