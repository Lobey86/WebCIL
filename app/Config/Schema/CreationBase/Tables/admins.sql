--
-- Création de la table admins
--

REATE TABLE admins
(
  id serial NOT NULL,
  user_id integer NOT NULL,
  created date,
  modified date,
  CONSTRAINT admins_pkey PRIMARY KEY (id),
  CONSTRAINT admins_user_id_fkey FOREIGN KEY (user_id)
      REFERENCES users (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)

INSERT INTO admins (user_id, created, modified) values(1, NOW(), NOW());