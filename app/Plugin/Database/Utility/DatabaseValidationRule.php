<?php
	/**
	 * Code source de la classe DatabaseValidationRule.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe DatabaseValidationRule ...
	 *
	 * @package Database
	 * @subpackage Utility
	 */
	abstract class DatabaseValidationRule
	{
		/**
		 * Normalisation d'une règle de validation, retourne un array avec les
		 * clés rule, message, required, allowEmpty, on.
		 *
		 * @param string|array $rule
		 * @return array
		 */
		public static function normalize( $rule ) {
			if( false === is_array( $rule ) ) {
				$rule = array( 'rule' => array( $rule ) );
			}
			else if( false === isset( $rule['rule'] ) && true === isset( $rule[0] ) ) {
				$rule = array( 'rule' => $rule );
			}
			else if( false === is_array( $rule['rule'] ) ) {
				$rule['rule'] = (array)$rule['rule'];
			}

			$defaults = array(
				'rule' => null,
				'message' => null,
				'required' => null,
				'allowEmpty' => null,
				'on' => null
			);

			$rule = array_merge( $defaults, $rule );

			return $rule;
		}

		/**
		 * Retourne le message par défaut lié à une règle de validation.
		 *
		 * @param string|array $rule
		 * @param string $domain
		 * @return string
		 */
		public static function message( $rule, $domain = null ) {
			$rule = static::normalize( $rule );
			$ruleName = Hash::get( $rule, 'rule.0' );

			if( null === $ruleName ) {
				return null;
			}

			$message = "Validate::{$ruleName}";

			$params = array();
			if( count( $rule['rule'] ) > 1 ) {
				$params = array_slice( $rule['rule'], 1 );

				if( is_array( $params[0] ) ) { //FIXME: debug($params);
					$params = $params[0];
				}
			}

			if( 'inlist' === strtolower( $ruleName ) ) { //FIXME: debug($params);
				$params = '"'.implode( '", "', $params ).'"';
			}

			if( null === $domain && true === isset( $rule['domain'] ) ) {
				$domain = $rule['domain'];
			}
			if( null === $domain ) {
				$domain = 'database';
			}

			return call_user_func_array( 'sprintf', array_merge( array( __d( $domain, $message ) ), (array)$params ) );
		}

		/**
		 *
		 * @param array $validate
		 * @param string $domain
		 * @return array
		 */
		public static function translate( array $validate, $domain ) {
			foreach( $validate as $field => $rules ) {
				foreach( $rules as $key => $rule ) {
					$rule = static::normalize( $rule );
					if( !isset( $rule['message'] ) || empty( $rule['message'] ) ) {
						$rule['message'] = static::message( $rule, $domain );
						$validate[$field][$key] = $rule;
					}
				}
			}
			return $validate;
		}

		// ---------------------------------------------------------------------

		public function boolean() {
		}

		public function integer() {
		}
		// INFO: avoid in PHP >= 7
//		public function string() {}
	}
?>