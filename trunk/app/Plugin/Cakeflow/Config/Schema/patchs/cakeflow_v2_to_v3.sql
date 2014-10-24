ALTER TABLE wkf_visas ALTER COLUMN commentaire TYPE TEXT;
ALTER TABLE wkf_etapes ADD COLUMN soustype integer DEFAULT NULL;
ALTER TABLE wkf_compositions ADD COLUMN soustype integer DEFAULT NULL;
ALTER TABLE wkf_compositions ADD COLUMN type_composition VARCHAR(20) DEFAULT 'USER';