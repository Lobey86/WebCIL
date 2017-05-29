/*
* CakePHP validation functions in PlPgSQL
* Tested with:
* 	- PostgreSQL 8.3.3 on i686-pc-linux-gnu, compiled by GCC gcc (GCC) 4.2.4
*
* @see http://book.cakephp.org/view/125/Data-Validation#!/view/134/Core-Validation-Rules
* @see http://api.cakephp.org/class/validation
*
* @see http://archives.postgresql.org/pgsql-de-allgemein/2004-09/msg00001.php
* @see http://stackoverflow.com/questions/2978751/why-repeat-database-constraints-in-models
*
* @see http://bakery.cakephp.org/articles/mattc/2008/10/26/automagic-javascript-validation-helper
* @see https://github.com/mcurry/js_validate
*/
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

-- *****************************************************************************
BEGIN;
-- *****************************************************************************

/*
* Create PlPgSQL language if it doesn't exist.
* @source http://andreas.scherbaum.la/blog/archives/346-create-language-if-not-exist.html
*/

CREATE OR REPLACE FUNCTION create_plpgsql_language () RETURNS TEXT AS
$$
		CREATE LANGUAGE 'plpgsql';
		SELECT 'language plpgsql created'::TEXT;
$$
LANGUAGE 'sql';

SELECT CASE WHEN ( SELECT true::BOOLEAN FROM pg_language WHERE lanname='plpgsql')
	THEN ( SELECT 'language already installed'::TEXT )
	ELSE ( SELECT create_plpgsql_language() )
END;

DROP FUNCTION create_plpgsql_language ();

-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION array_search( p_needle text, p_haystack text[] ) RETURNS integer AS
$$
	DECLARE
		v_i integer;
		v_min integer;
		v_max integer;
	BEGIN
		v_min := array_lower( p_haystack, 1 );
		v_max := array_upper( p_haystack, 1 );

		v_i := v_min;
		LOOP
			IF p_needle = p_haystack[v_i] THEN
				RETURN v_i;
			END IF;

			v_i := v_i + 1;
			EXIT WHEN v_i > v_max;
		END LOOP;

		RETURN null;
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION array_search( p_needle text, p_haystack text[] ) IS
	E'PHP-like way to find the key value of the needle in the haystack.\nReturns null when not found.';

-- -----------------------------------------------------------------------------
-- alphaNumeric ( string|array $check )
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_alpha_numeric( p_check text ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check IS NULL OR ( p_check ~ E'^[^[:punct:]|[:blank:]|[:space:]|[:cntrl:]]+$'  );
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_alpha_numeric( p_check text ) IS
	'@see http://api.cakephp.org/2.2/class-Validation.html#_alphaNumeric';

-- -----------------------------------------------------------------------------
-- between ( string $check , integer $min , integer $max )
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_between( p_check text, p_min integer, p_max integer ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check IS NULL OR ( LENGTH( p_check ) >= p_min AND LENGTH( p_check ) <= p_max  );
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_between( text, integer, integer ) IS
	'@see http://api.cakephp.org/2.2/class-Validation.html#_between';

-- -----------------------------------------------------------------------------
-- blank ( string|array $check )
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_blank( p_check text ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check IS NULL OR ( p_check ~ E'^[[:blank:]\n\r]*$'  );
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_blank( text ) IS
	'@see http://api.cakephp.org/2.2/class-Validation.html#_blank';

-- -----------------------------------------------------------------------------
-- boolean( $check )
-- ( autovalidate )
-- -----------------------------------------------------------------------------

-- -----------------------------------------------------------------------------
-- luhn ( string|array $check , boolean $deep = false )
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_luhn( p_check text, p_deep boolean ) RETURNS boolean AS
$$
	DECLARE
		v_sum integer;
		v_length integer;
		v_position integer;
		v_number integer;
	BEGIN
		IF ( p_deep IS NULL OR p_deep = false ) THEN
			RETURN true;
		END IF;

		v_sum = 0;
		v_length := length( p_check );

		IF ( p_check ~ '^0+$' ) OR ( v_length = 0 ) THEN
			RETURN false;
		END IF;

		v_position := ( 1 - ( v_length % 2 ) ) + 1;
		LOOP
			v_sum := v_sum + substring( p_check FROM v_position FOR 1 )::integer;

			v_position := v_position + 2;
			EXIT WHEN v_position > v_length;
		END LOOP;

		v_position := ( v_length % 2 ) + 1;
		LOOP
			v_number := substring( p_check FROM v_position FOR 1 )::integer * 2;
			IF v_number < 10 THEN
				v_sum := v_sum + v_number;
			ELSE
				v_sum := v_sum + v_number - 9;
			END IF;

			v_position := v_position + 2;
			EXIT WHEN v_position > v_length;
		END LOOP;

		RETURN ( v_sum % 10 ) = 0;
	END;
$$
LANGUAGE 'plpgsql' VOLATILE RETURNS NULL ON NULL INPUT SECURITY INVOKER;

COMMENT ON FUNCTION cakephp_validate_luhn( p_check text, p_deep boolean ) IS
	E'@see http://api.cakephp.org/2.2/class-Validation.html#_luhn';

-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_luhn( p_check text ) RETURNS bool AS
$$
	BEGIN
		RETURN cakephp_validate_luhn( $1, false );
	END
$$
LANGUAGE 'plpgsql';

-- -----------------------------------------------------------------------------
-- cc ( string|array $check , string|array $type = 'fast' , boolean $deep = false , string $regex = null )
-- -----------------------------------------------------------------------------
CREATE OR REPLACE FUNCTION cakephp_validate_cc( p_check text, p_type text[], p_deep boolean, p_regex text ) RETURNS boolean AS
$$
	DECLARE
		v_i integer;
		v_max integer;
		v_check text;
		v_return boolean;
		v_names text[];
		v_regexes text[];
		v_key integer;
	BEGIN
		v_check = replace( replace( p_check, '-', '' ), ' ', '' );

		IF LENGTH( v_check ) < 13 THEN
			RETURN false;
		END IF;

		IF p_regex IS NOT NULL THEN
			RETURN ( LOWER( v_check ) ~ p_regex )
				AND (
					( p_deep IS NULL OR p_deep = false )
					OR cakephp_validate_luhn( v_check )
				);
		END IF;

		v_names := ARRAY[
			'amex',
			'bankcard',
			'diners',
			'disc',
			'electron',
			'enroute',
			'jcb',
			'maestro',
			'mc',
			'solo',
			'switch',
			'visa',
			'voyager'
		];

		v_regexes := ARRAY[
			'^3(4|7)[0-9]{13}$',
			'^56(10[0-9]{2}|022[1-5])[0-9]{10}$',
			'(?:3(0[0-5]|[68][0-9])[0-9]{11})|(?:5[1-5][0-9]{14})',
			'(?:6011|650[0-9])[0-9]{12}',
			'(?:417500|4917[0-9]{2}|4913[0-9]{2})[0-9]{10}',
			'2(?:014|149)[0-9]{11}',
			'(3[0-9]{4}|2100|1800)[0-9]{11}',
			'(?:5020|6[0-9]{3})[0-9]{12}',
			'5[1-5][0-9]{14}',
			'(6334[5-9][0-9]|6767[0-9]{2})[0-9]{10}([0-9]{2,3})?',
			'(?:49(03(0[2-9]|3[5-9])|11(0[1-2]|7[4-9]|8[1-2])|36[0-9]{2})[0-9]{10}([0-9]{2,3})?)|(?:564182[0-9]{10}([0-9]{2,3})?)|(6(3(33[0-4][0-9])|759[0-9]{2})[0-9]{10}([0-9]{2,3})?)',
			'4[0-9]{12}([0-9]{3})?',
			'8699[0-9]{11}'
		];

		IF ARRAY['all'] <@ p_type THEN
			v_return := false;

			v_i := array_lower( v_names, 1 );
			v_max := array_upper( v_names, 1 );

			LOOP
				IF p_check ~ v_regexes[v_i] THEN
					RETURN cakephp_validate_luhn( p_check );
				END IF;

				v_i := v_i + 1;
				EXIT WHEN v_i > v_max;
			END LOOP;

			RETURN false;
		ELSE
			v_names := array_append( v_names, 'fast' );
			v_regexes := array_append( v_regexes, '^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6011[0-9]{12}|3(?:0[0-5]|[68][0-9])[0-9]{11}|3[47][0-9]{13})$' );

			-- INFO: 1 seule entrée seulement
			v_key := array_search( p_type[1], v_names );
			IF v_key IS NOT NULL THEN
				RETURN p_check ~ v_regexes[v_key]
					AND cakephp_validate_luhn( p_check );
			ELSE
				RETURN false;
			END IF;
		END IF;

		RETURN v_return;
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_cc( text, text[], boolean, text ) IS
	'@see http://api.cakephp.org/2.2/class-Validation.html#_cc';

-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_cc( p_check text, p_type text, p_deep boolean, p_regex text ) RETURNS bool AS
$$
	BEGIN
		RETURN cakephp_validate_cc( $1, ARRAY[$2], $3, $4 );
	END
$$
LANGUAGE 'plpgsql';

-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_cc( p_check text, p_type text[], p_deep boolean ) RETURNS bool AS
$$
	BEGIN
		RETURN cakephp_validate_cc( $1, $2, $3, NULL );
	END
$$
LANGUAGE 'plpgsql';

-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_cc( p_check text, p_type text, p_deep boolean ) RETURNS bool AS
$$
	BEGIN
		RETURN cakephp_validate_cc( $1, ARRAY[$2], $3, NULL );
	END
$$
LANGUAGE 'plpgsql';

-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_cc( p_check text, p_type text[], p_deep boolean ) RETURNS bool AS
$$
	BEGIN
		RETURN cakephp_validate_cc( $1, $2, $3, NULL );
	END
$$
LANGUAGE 'plpgsql';

-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_cc( p_check text, p_type text, p_deep boolean ) RETURNS bool AS
$$
	BEGIN
		RETURN cakephp_validate_cc( $1, ARRAY[$2], $3, NULL );
	END
$$
LANGUAGE 'plpgsql';

-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_cc( p_check text, p_type text[] ) RETURNS boolean AS
$$
	BEGIN
		RETURN cakephp_validate_cc( $1, $2, false, NULL );
	END
$$
LANGUAGE 'plpgsql' IMMUTABLE;

-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_cc( p_check text, p_type text ) RETURNS boolean AS
$$
	BEGIN
		RETURN cakephp_validate_cc( $1, ARRAY[$2], false, NULL );
	END
$$
LANGUAGE 'plpgsql' IMMUTABLE;

-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_cc( p_check text, p_type text ) RETURNS boolean AS
$$
	BEGIN
		RETURN cakephp_validate_cc( $1, ARRAY[p_type], false, NULL );
	END
$$
LANGUAGE 'plpgsql' IMMUTABLE;

-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_cc( p_check text ) RETURNS boolean AS
$$
	BEGIN
		RETURN cakephp_validate_cc( $1, ARRAY['fast'], false, NULL );
	END
$$
LANGUAGE 'plpgsql' IMMUTABLE;

-- -----------------------------------------------------------------------------
-- comparison ( string|array $check1 , string $operator = null , integer $check2 = null )
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_comparison( p_check1 float,p_operator text,p_check2 float ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check1 IS NULL
			OR(
				p_operator IS NOT NULL
				AND p_check2 IS NOT NULL
				AND (
					( p_operator IN ( '>', 'is greater' ) AND p_check1 > p_check2 )
					OR ( p_operator IN ( '>=', 'greater or equal' ) AND p_check1 >= p_check2 )
					OR ( p_operator IN ( '==', 'equal to' ) AND p_check1 = p_check2 )
					OR ( p_operator IN ( '!=', 'not equal' ) AND p_check1 <> p_check2 )
					OR ( p_operator IN ( '<', 'is less' ) AND p_check1 < p_check2 )
					OR ( p_operator IN ( '<=', 'less or equal' ) AND p_check1 <= p_check2 )
				)
			);
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_comparison( p_check1 float,p_operator text,p_check2 float ) IS
	'@see http://api.cakephp.org/2.2/class-Validation.html#_comparison';

-- -----------------------------------------------------------------------------
-- custom ( string|array $check , string $regex = null )
-- TODO
-- -----------------------------------------------------------------------------

-- -----------------------------------------------------------------------------
-- date ( string $check , string|array $format = 'ymd' , string $regex = null )
-- (autovalidate)
-- -----------------------------------------------------------------------------

-- -----------------------------------------------------------------------------
-- datetime ( array $check , string|array $dateFormat = 'ymd' , string $regex = null )
-- (autovalidate)
-- -----------------------------------------------------------------------------

-- -----------------------------------------------------------------------------
-- decimal ( integer $check , integer $places = null , string $regex = null )
-- FIXME: qu'est-ce qui est envoyé à PostgreSQL ?
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_decimal( p_check float, p_places integer, p_regex text ) RETURNS boolean AS
$$
	DECLARE
		v_regex TEXT;
	BEGIN
		IF p_check IS NULL THEN
			RETURN true;
		END IF;

		IF p_regex IS NOT NULL THEN
			RETURN p_check::text ~ p_regex;
		END IF;

		IF p_places IS NULL THEN
			return p_check::text ~ E'^[-+]?[0-9]*\\.{1}[0-9]+(?:[eE][-+]?[0-9]+)?$';
		ELSE
			v_regex := E'^[-+]?[0-9]*\\.{1}[0-9]{' || p_places::text || '}$';
			return p_check::text ~ v_regex;
		END IF;
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_decimal( p_check float, p_places integer, p_regex text ) IS
	'@see http://api.cakephp.org/2.2/class-Validation.html#_decimal';

-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_decimal( p_check float, p_places integer ) RETURNS boolean AS
$$
	BEGIN
		RETURN cakephp_validate_decimal( p_check, p_places, NULL );
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_decimal( p_check float ) RETURNS boolean AS
$$
	BEGIN
		RETURN cakephp_validate_decimal( p_check, NULL, NULL );
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

-- -----------------------------------------------------------------------------
-- email ( string $check , boolean $deep = false , string $regex = null )
-- INFO: deep isn't used in database, only in CakePHP
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_email( p_check text, p_deep boolean, p_regex text ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check IS NULL
			OR ( p_regex IS NOT NULL AND p_check ~ p_regex )
			OR (
				p_regex IS NULL
				AND p_check ~* E'^[a-z0-9!#$%&''*+\\/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&''*+\\/=?^_`{|}~-]+)*@(?:[-_a-z0-9][-_a-z0-9]*\\.)*(?:[a-z0-9][-a-z0-9]{0,62})\\.(?:(?:[a-z]{2}\\.)?[a-z]{2,})$'
			);
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_email( p_check text, p_deep boolean, p_regex text ) IS
	'@see http://api.cakephp.org/2.2/class-Validation.html#_email';

CREATE OR REPLACE FUNCTION cakephp_validate_email( p_check text, p_deep boolean ) RETURNS boolean AS
$$
	BEGIN
		RETURN cakephp_validate_email( p_check, p_deep, null );
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_email( p_check text ) RETURNS boolean AS
$$
	BEGIN
		RETURN cakephp_validate_email( p_check, false, null );
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

-- -----------------------------------------------------------------------------
-- equalTo ( mixed $check , mixed $comparedTo )
-- TODO
-- -----------------------------------------------------------------------------

-- -----------------------------------------------------------------------------
-- extension ( string|array $check , array $extensions = array('gif', 'jpeg', 'png', 'jpg') )
-- TODO
-- -----------------------------------------------------------------------------

-- -----------------------------------------------------------------------------
-- inList ( string $check , array $list , boolean $strict = true )
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_in_list( p_check text, p_list text[] ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check IS NULL OR ( ARRAY[CAST(p_check AS TEXT)] <@ CAST(p_list AS TEXT[]) );
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_in_list( text, text[] ) IS
	'@see http://api.cakephp.org/2.2/class-Validation.html#_inList';

-- -----------------------------------------------------------------------------
-- inList pour des entiers

CREATE OR REPLACE FUNCTION cakephp_validate_in_list( integer, integer[] ) RETURNS boolean AS
$$
	SELECT $1 IS NULL OR ( ARRAY[CAST($1 AS TEXT)] <@ CAST($2 AS TEXT[]) );
$$
LANGUAGE 'sql' IMMUTABLE;

-- -----------------------------------------------------------------------------
-- ip ( string $check , string $type = 'both' )  -> _ipv4, _ipv6
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate__ipv4( p_check text ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check IS NULL OR p_check ~ E'^(?:(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])\\.){3}(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])$';
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

CREATE OR REPLACE FUNCTION cakephp_validate__ipv6( p_check text ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check IS NULL OR p_check ~ E'^((([0-9A-Fa-f]{1,4}:){7}(([0-9A-Fa-f]{1,4})|:))|(([0-9A-Fa-f]{1,4}:){6}(:|((25[0-5]|2[0-4][0-9]|[01]?[0-9]{1,2})(\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9]{1,2})){3})|(:[0-9A-Fa-f]{1,4})))|(([0-9A-Fa-f]{1,4}:){5}((:((25[0-5]|2[0-4][0-9]|[01]?[0-9]{1,2})(\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9]{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){4}(:[0-9A-Fa-f]{1,4}){0,1}((:((25[0-5]|2[0-4][0-9]|[01]?[0-9]{1,2})(\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9]{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){3}(:[0-9A-Fa-f]{1,4}){0,2}((:((25[0-5]|2[0-4][0-9]|[01]?[0-9]{1,2})(\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9]{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){2}(:[0-9A-Fa-f]{1,4}){0,3}((:((25[0-5]|2[0-4][0-9]|[01]?[0-9]{1,2})(\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9]{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:)(:[0-9A-Fa-f]{1,4}){0,4}((:((25[0-5]|2[0-4][0-9]|[01]?[0-9]{1,2})(\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9]{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(:(:[0-9A-Fa-f]{1,4}){0,5}((:((25[0-5]|2[0-4][0-9]|[01]?[0-9]{1,2})(\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9]{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(((25[0-5]|2[0-4][0-9]|[01]?[0-9]{1,2})(\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9]{1,2})){3})))(%.+)?$';
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

CREATE OR REPLACE FUNCTION cakephp_validate_ip( p_check text, p_type text ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check IS NULL
			OR (
				(
					LOWER( p_type ) = 'both'
					AND (
						cakephp_validate__ipv4( p_check )
						OR cakephp_validate__ipv6( p_check )
					)
				)
				OR (
					LOWER( p_type ) = 'ipv4'
					AND cakephp_validate__ipv4( p_check )
				)
				OR (
					LOWER( p_type ) = 'ipv6'
					AND cakephp_validate__ipv6( p_check )
				)
			);
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_ip( p_check text, p_type text ) IS
	'@see http://api.cakephp.org/2.2/class-Validation.html#_ip';

CREATE OR REPLACE FUNCTION cakephp_validate_ip( p_check text ) RETURNS boolean AS
$$
	BEGIN
		RETURN cakephp_validate_ip( p_check, 'both' );
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

-- -----------------------------------------------------------------------------
-- maxLength ( string $check , integer $max )
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_max_length( p_check text,p_max integer ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check IS NULL OR ( LENGTH( p_check ) <= p_max );
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_max_length( p_check text,p_max integer ) IS
	'@see http://api.cakephp.org/2.2/class-Validation.html#_maxLength';

-- -----------------------------------------------------------------------------
-- mimeType ( string|array $check , array $mimeTypes = array() )
-- TODO
-- -----------------------------------------------------------------------------

-- -----------------------------------------------------------------------------
-- minLength ( string $check , integer $min )
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_min_length( p_check text,p_min integer ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check IS NULL OR ( LENGTH( p_check ) >= p_min );
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_min_length( text, integer ) IS
	'@see http://api.cakephp.org/2.2/class-Validation.html#_minLength';

-- -----------------------------------------------------------------------------
-- money ( string $check , string $symbolPosition = 'left' )
-- TODO ?
-- -----------------------------------------------------------------------------

-- -----------------------------------------------------------------------------
-- multiple ( array $check , array $options = array() , boolean $strict = true )
-- TODO ?
-- -----------------------------------------------------------------------------

-- -----------------------------------------------------------------------------
-- naturalNumber ( string $check , boolean $allowZero = false )
-- TODO
-- -----------------------------------------------------------------------------

-- -----------------------------------------------------------------------------
-- notEmpty ( string|array $check )
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_not_empty( p_check text ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check IS NULL OR NOT ( p_check ~ E'^[[:blank:]\n\r]*$'  );
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_not_empty( p_check text ) IS
	'@see http://api.cakephp.org/2.2/class-Validation.html#_notEmpty';

-- -----------------------------------------------------------------------------
-- numeric ( string $check )
-- (autovalidate)
-- -----------------------------------------------------------------------------

-- -----------------------------------------------------------------------------
-- phone ( string|array $check , string $regex = null , string $country = 'all' )
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_phone( p_phone text, p_regex text, p_country text ) RETURNS boolean AS
$$
	BEGIN
		RETURN ( p_phone IS NULL )
			OR(
				(
					( p_country IS NULL OR p_country IN ( 'all', 'can', 'us' ) )
					AND p_phone ~ E'^(?:\\+?1)?[-. ]?\\(?[2-9][0-8][0-9]\\)?[-. ]?[2-9][0-9]{2}[-. ]?[0-9]{4}$'
				)
				OR
				(
					( p_country = 'fr' )
					AND p_phone ~ E'^0[1-9][0-9]{8}$'
				)
				OR
				(
					( p_regex IS NOT NULL )
					AND p_phone ~ p_regex
				)
			);
	END;
$$ LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_phone( p_phone text, p_regex text, p_country text ) IS
	E'@see http://api.cakephp.org/2.2/class-Validation.html#_phone\nCustom country France (fr) added.';

-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_phone( p_phone text, p_regex text ) RETURNS boolean AS
$$
	BEGIN
		RETURN cakephp_validate_phone( p_phone, p_regex, 'all' );
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_phone( p_phone text ) RETURNS boolean AS
$$
	BEGIN
		RETURN cakephp_validate_phone( p_phone, null, 'all' );
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

-- -----------------------------------------------------------------------------
-- postal ( string|array $check , string $regex = null , string $country = 'us' )
-- TODO
-- -----------------------------------------------------------------------------

-- -----------------------------------------------------------------------------
-- range ( string $check , integer $lower = null , integer $upper = null )
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_range( p_check float, p_lower float, p_upper float ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check IS NULL
			OR p_lower IS NULL
			OR p_upper IS NULL
			OR(
				p_check > p_lower
				AND p_check < p_upper
			);
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_range( p_check float, p_lower float, p_upper float ) IS
	'@see http://api.cakephp.org/class/validation#method-Validationrange';

-- -----------------------------------------------------------------------------
-- ssn ( string|array $check , string $regex = null , string $country = null )
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_ssn( p_ssn text, p_regex text, p_country text ) RETURNS boolean AS
$$
	BEGIN
		RETURN ( p_ssn IS NULL )
			OR(
				(
					( p_country = 'dk' )
					AND p_ssn ~* E'^[0-9]{6}-[0-9]{4}$'
				)
				OR
				(
					( p_country = 'nl' )
					AND p_ssn ~* E'^[0-9]{9}$'
				)
				OR
				(
					( p_country = 'us' )
					AND p_ssn ~* E'^[0-9]{3}-[0-9]{2}-[0-9]{4}$'
				)
				OR
				(
					( p_country = 'fr' )
					AND UPPER( p_ssn ) ~ E'^(1|2|7|8)[0-9]{2}(0[1-9]|10|11|12|[2-9][0-9])((0[1-9]|[1-8][0-9]|9[0-5]|2A|2B)(00[1-9]|0[1-9][0-9]|[1-8][0-9][0-9]|9[0-8][0-9]|990)|(9[7-8][0-9])(0[1-9]|0[1-9]|[1-8][0-9]|90)|99(00[1-9]|0[1-9][0-9]|[1-8][0-9][0-9]|9[0-8][0-9]|990))(00[1-9]|0[1-9][0-9]|[1-9][0-9][0-9]|)(0[1-9]|[1-8][0-9]|9[0-7])$'
				)
				OR
				(
					( p_regex IS NOT NULL )
					AND p_ssn ~ p_regex
				)
			);
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_ssn( p_ssn text, p_regex text, p_country text ) IS
	E'@see http://api.cakephp.org/2.2/class-Validation.html#_ssn\nCustom country France (fr) added.\n@see http://fr.wikipedia.org/wiki/Num%C3%A9ro_de_s%C3%A9curit%C3%A9_sociale_en_France#Signification_des_chiffres_du_NIR';

-- -----------------------------------------------------------------------------
-- time ( string $check )
-- ( autovalidate )
-- -----------------------------------------------------------------------------

-- -----------------------------------------------------------------------------
-- uploadError ( string|array $check )
-- ( ? )
-- -----------------------------------------------------------------------------

-- -----------------------------------------------------------------------------
-- url ( string $check , boolean $strict = false )
-- TODO
-- -----------------------------------------------------------------------------

-- -----------------------------------------------------------------------------
-- userDefined ( string|array $check , object $object , string $method , array $args = null )
-- TODO
-- -----------------------------------------------------------------------------

-- -----------------------------------------------------------------------------
-- uuid ( string $check )
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_uuid( p_check text ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check IS NULL OR ( LOWER( p_check ) ~ E'^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$' );
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_uuid( p_check text ) IS
	'@see http://api.cakephp.org/2.2/class-Validation.html#_uuid';

-- *****************************************************************************
COMMIT;
-- *****************************************************************************