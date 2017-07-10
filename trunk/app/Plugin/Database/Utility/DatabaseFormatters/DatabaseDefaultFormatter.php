<?php
    /**
     * Code source de la classe DatabaseDefaultFormatter.
     *
     * PHP 5.3
     *
     * @package Database
     * @subpackage Utility.DatabaseFormatters
     * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
     */

    /**
	 * La classe DatabaseDefaultFormatter fournit des méthodes de formattage
	 * basiques.
     *
     * @package Database
     * @subpackage Utility.DatabaseFormatters
     */
	class DatabaseDefaultFormatter
	{

		/**
		 * Si le paramètre est une chaîne de caractères, supprime les caractères
		 * blancs à l'avant et à l'arrière de la chaîne avant de la renvoyer.
		 *
		 * @param mixed $value
		 * @return mixed
		 */
		public static function formatTrim( $value ) {
			if( is_string( $value ) ) {
				$value = trim( $value );
			}

			return $value;
		}

		/**
		 * Si le paramètre est une chaîne de caractères vide ou ne contenant que
		 * des caractères blancs, alors la valeur renvoyée est null.
		 *
		 * @param mixed $value
		 * @return mixed
		 */
		public static function formatNull( $value ) {
			if( is_string( $value ) && ( strlen( trim( $value ) ) == 0 ) ) {
				$value = null;
			}

			return $value;
		}

		/**
		 * Si le paramètre ressemble à un "nombre formatté en français", la
		 * valeur renvoyée devient un nombre à entrer dans la base de données.
		 *
		 * <pre>
		 * "6 661" => 6661
		 * "-10 123,67" => -10123.67
		 * </pre>
		 *
		 * @param mixed $value
		 * @return string
		 */
		public static function formatNumeric( $value ) {
			$cleaned = str_replace( ' ', '', $value );

			if( preg_match( '/^(\-{0,1})([0-9]+)(,([0-9]+)){0,1}$/', $cleaned, $matches ) ) {
				// Float
				if( isset( $matches[4] ) ) {
					$value = "{$matches[1]}{$matches[2]}.{$matches[4]}";
				}
				// Integer
				else {
					$value = "{$matches[1]}{$matches[2]}";
				}
			}

			return $value;
		}

		/**
		 * Retourne le suffixe d'une valeur, c'est à dire la partie de la valeur
		 * après le dernier séparateur si celui-ci existe.
		 *
		 * @param mixed $value
		 * @param string $separator
		 * @return string
		 */
		public static function formatSuffix( $value, $separator = '_' ) {
			if( preg_match( "/^(.*){$separator}([^{$separator}]*)$/", $value, $matches ) ) {
				$value = $matches[2];
			}

			return $value;
		}
                
                /**
                * Retourne une chaîne dans laquelle tous les caractères non alpha-numériques
                * ont été supprimés.
                *
                * @param mixed $value
                * @param string $separator
                * @return string
                */
               public static function formatStripNotAlnum( $value ) {
                   return null === $value
                       ? null
                       : preg_replace( '/[^[:alnum:]]/u', '', $value );
               }
	}
?>
