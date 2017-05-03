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

-- -----------------------------------------------------------------------------
-- inclusiveRange
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_inclusive_range( p_check float, p_min float, p_max float ) RETURNS boolean AS
$$
	BEGIN
		RETURN p_check IS NULL OR ( p_check >= p_min AND p_check <= p_max  );
	END;
$$
LANGUAGE plpgsql IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_inclusive_range( p_check float, p_min float, p_max float ) IS
	'Comme cakephp_validate_range(), bornes comprises.';

-- -----------------------------------------------------------------------------

SELECT
	( cakephp_validate_inclusive_range( 20, 100, 1 ) = false )
	AND ( cakephp_validate_inclusive_range( 20, 1, 100 ) = true )
	AND ( cakephp_validate_inclusive_range( .5, 1, 100 ) = false )
	AND ( cakephp_validate_inclusive_range( .5, 0, 100 ) = true )
	AND ( cakephp_validate_inclusive_range( -5, -10, 1 ) = true )
	AND ( cakephp_validate_inclusive_range( 10, 0, 10 ) = true )
	AS passed_tests_cakephp_validate_inclusive_range;

-- -----------------------------------------------------------------------------
-- compareDates
-- -----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION cakephp_validate_compare_dates( p_check1 TIMESTAMP, p_check2 TIMESTAMP, p_comparator text ) RETURNS boolean AS
$$
	BEGIN
		RETURN ( p_check1 IS NULL OR p_check2 IS NULL )
			OR(
				p_comparator IS NOT NULL
				AND p_check2 IS NOT NULL
				AND (
					( p_comparator IN ( '>', 'is greater' ) AND p_check1 > p_check2 )
					OR ( p_comparator IN ( '>=', 'greater or equal' ) AND p_check1 >= p_check2 )
					OR ( p_comparator IN ( '==', 'equal to' ) AND p_check1 = p_check2 )
					OR ( p_comparator IN ( '<', 'is less' ) AND p_check1 < p_check2 )
					OR ( p_comparator IN ( '<=', 'less or equal' ) AND p_check1 <= p_check2 )
				)
			);
	END;
$$
LANGUAGE 'plpgsql' IMMUTABLE;

COMMENT ON FUNCTION cakephp_validate_compare_dates( p_check1 TIMESTAMP, p_check2 TIMESTAMP, p_comparator text ) IS
	'@see Validation2.Validation2RulesComparisonBehavior::compareDates()';

-- -----------------------------------------------------------------------------

SELECT
	( cakephp_validate_compare_dates( NULL, NULL, NULL ) = true )
	AND ( cakephp_validate_compare_dates( '2012-01-01', '2012-01-02', '<' ) = true )
	AND ( cakephp_validate_compare_dates( '2012-01-01', '2012-01-02', '*' ) = false )
	AND ( cakephp_validate_compare_dates( '2012-01-01', '2012-01-02', '>' ) = false )
	AND ( cakephp_validate_compare_dates( '2012-01-02', '2012-01-01', '<=' ) = false )
	AND ( cakephp_validate_compare_dates( '2012-01-01', '2012-01-01', '==' ) = true )
	AS passed_tests_cakephp_validate_compare_dates;

-- *****************************************************************************
COMMIT;
-- *****************************************************************************