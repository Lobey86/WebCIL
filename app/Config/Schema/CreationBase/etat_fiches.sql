
--
-- Création de la table etat_fiches
--

CREATE TABLE etat_fiches (
	id SERIAL NOT NULL PRIMARY KEY,
	fiche_id INTEGER NOT NULL REFERENCES fiches(id) ON DELETE CASCADE,
	etat_id INTEGER NOT NULL REFERENCES etats(id),
	user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
	previous_user_id INTEGER NOT NULL REFERENCES users(id),
	previous_etat_id INTEGER DEFAULT NULL,
	created DATE,
	modified DATE
);


