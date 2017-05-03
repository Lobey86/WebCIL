<?php
	/**
	 * Code source de la classe PostgresExtraValidationRulesBehavior.
	 *
	 * PHP 5.3
	 *
	 * @package Postgres
	 * @subpackage Model.Behavior
	 */

	/**
	 * La classe PostgresExtraValidationRulesBehavior...
	 *
	 * @package Postgres
	 * @subpackage Model.Behavior
	 */
	class PostgresExtraValidationRulesBehavior extends ModelBehavior
	{
		/**
		 * Compare la date en valeur par-rapport à la date de référence, suivant
		 * le comparateur.
		 *
		 * @param Model $Model
		 * @param array $check
		 * @param string $reference
		 * @param string $comparator Une valeur parmi: >, <, ==, <=, >=
		 * @return boolean
		 */
		public function compareDates( Model $Model, $check, $reference, $comparator ) {
			if( !is_array( $check ) ) {
				return false;
			}

			$check = array_values( $check );

			$check_value = strtotime( isset( $check[0] ) ? $check[0] : null );
			$reference_value = strtotime( Set::extract( $Model->data, $Model->alias.'.'.$reference ) );

			if( empty( $reference_value ) || empty( $check_value ) ) {
				return true;
			}

			if ( in_array( $comparator, array( '>', '<', '==', '<=', '>=' ) ) ) {
				if ( !( eval( "return \$check_value $comparator \$reference_value ;" ) ) ) {
					return false;
				}
			}
			else {
				return false;
			}
			return true;
		}

		/**
		 * Validate that a number is in specified range.
		 * if $lower and $upper are not set, will return true if
		 * $check is a legal finite on this platform
		 *
		 * @param Model $Model
		 * @param array $check Value to check
		 * @param mixed $lower Lower limit
		 * @param mixed $upper Upper limit
		 * @return boolean
		 */
		public function inclusiveRange( Model $Model, $check, $lower = null, $upper = null ) {
			if( !is_array( $check ) ) {
				return false;
			}

			$return = true;
			foreach( $check as $field => $value ) {
				if( isset( $lower ) && isset( $upper ) ) {
					$return = ( $value >= $lower && $value <= $upper ) && $return;
				}
			}
			return $return;
		}
	}
?>