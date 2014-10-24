BEGIN;

ALTER TABLE wkf_visas ADD COLUMN etape_id INT REFERENCES wkf_etapes(id) DEFAULT NULL;
ALTER TABLE wkf_signatures ADD COLUMN visa_id INT REFERENCES wkf_visas(id);

COMMIT;