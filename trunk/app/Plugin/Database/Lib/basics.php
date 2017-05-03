<?php
	/**
	 * Fonctions utilitaires du plugin Database.
	 *
	 * PHP 5.3
	 *
	 * @package Database
	 * @subpackage Lib
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	if( false === function_exists( 'cacheKey' ) ) {
		/**
		 * Retourne le nom de la clé de l'entrée de cache formée de l'assemblage des
		 * paramètres.
		 *
		 * @param array $params
		 * @param boolean $underscore
		 * @return string
		 */
		function cacheKey( array $params, $underscore = false ) {
			$cacheKey = implode( '_', $params );

			if( $underscore ) {
				$cacheKey = Inflector::underscore( $cacheKey );
			}

			return $cacheKey;
		}
	}

	if( false === function_exists( 'preg_replace_array' ) ) {
		/**
		 * Effectue des remplacements d'expressions réulières d'un array, de
		 * manière récursive.
		 *
		 * @deprecated since 1.1.0 use recursive_key_value_preg_replace
		 *
		 * @param array $array
		 * @param array $replacements Clés regexpes, valeurs chaînes de remplacement
		 * @return array
		 */
		function preg_replace_array( array $array, array $replacements ) {
			$newArray = array();
			foreach( $array as $key => $value ) {
				foreach( $replacements as $pattern => $replacement ) {
					$key = preg_replace( $pattern, $replacement, $key );
				}

				if( is_array( $value ) ) {
					$value = preg_replace_array( $value, $replacements );
				}
				else {
					foreach( $replacements as $pattern => $replacement ) {
						$value = preg_replace( $pattern, $replacement, $value );
					}
				}
				$newArray[$key] = $value;
			}
			return $newArray;
		}
	}

	if( false === function_exists( 'alias_querydata' ) ) {
		/**
		 * Remplace des mots par d'autres dans un querydata ou une partie de
		 * celui-ci.
		 *
		 * Exemple:
		 * 	$subject = array( 'Foo.id' => array( 'Bar' => 1 ), 'Foobar' => array( 'Foo.bar = Bar.foo' ) );
		 * 	$replacement = array( 'Foo' => 'Baz' );
		 * 	Résultat: array( 'Baz.id' => array( 'Bar' => 1 ), 'Foobar' => array( 'Baz.bar = Bar.foo' ) );
		 *
		 * @deprecated since 1.1.0 use words_replace
		 * @see preg_replace_array
		 *
		 * @param array $subject
		 * @param array $replacement
		 * @return array
		 */
		function alias_querydata( array $subject, array $replacement ) {
			$regexes = array( );
			foreach( $replacement as $key => $value ) {
				$key = "/(?<!\.)(?<!\w)({$key})(?!\w)/";
				$regexes[$key] = $value;
			}
			return preg_replace_array( $subject, $regexes );
		}
	}

	if( false === function_exists( 'groupKeysByValues' ) ) {
		/**
		 * Retourne un array contenant les valeurs en clé et les clés dans des arrays
		 * de valeurs.
		 *
		 * @param array $input
		 * @return array
		 */
		function groupKeysByValues( array $input = array() ) {
			$output = array();

			if( !empty( $input ) ) {
				foreach( $input as $key => $value ) {
					if( !isset( $output[$value] ) ) {
						$output[$value] = array();
					}

					$output[$value][] = $key;
				}
			}

			return $output;
		}
	}

	if( false === function_exists( 'array_normalize' ) ) {
		/**
		 * Retourne un array normalisé, de la même manière que la méthode
		 * Hash::normalize() de CakePHP, mais sur un seul niveau de clés et
		 * en traitant les clés numériques ou non numériques au cas par cas.
		 *
		 * @param array $array
		 * @return array
		 */
		function array_normalize( array $array ) {
			$result = array();

			foreach( $array as $key => $value ) {
				if( is_int( $key ) && is_string( $value ) ) {
					$result[$value] = null;
				}
				else {
					$result[$key] = $value;
				}
			}

			return $result;
		}
	}

	if( false === function_exists( 'recursive_key_value_preg_replace' ) ) {
		/**
		 * Effectue les remplacements contenus dans le paramètre $replacements (sous
		 * la forme clé => valeur, ce qui équivaut à pattern => remplacement ) des
		 * clés et des valeurs de array, de manière récursive, grâce à la fonction
		 * preg_replace.
		 *
		 * @param array $array
		 * @param array $replacements
		 * @return array
		 */
		function recursive_key_value_preg_replace( array $array, array $replacements ) {
			$result = array();
			foreach( $array as $key => $value ) {
				foreach( $replacements as $pattern => $replacement ) {
					$key = preg_replace( $pattern, $replacement, $key );
				}

				if( is_array( $value ) ) {
					$value = recursive_key_value_preg_replace( $value, $replacements );
				}
				else {
					foreach( $replacements as $pattern => $replacement ) {
						$value = preg_replace( $pattern, $replacement, $value );
					}
				}
				$result[$key] = $value;
			}
			return $result;
		}
	}

	if( false === function_exists( 'words_replace' ) ) {
		/**
		 * Remplace des mots par d'autres dans une chaine de caractères ou un array,
		 * de façon récursive, tant au niveau des clés que des valeurs.
		 *
		 * Exemple:
		 * 	$subject = array( 'Foo.id' => array( 'Bar' => 1 ), 'Foobar' => array( 'Foo.bar = Bar.foo' ) );
		 * 	$replacement = array( 'Foo' => 'Baz' );
		 * 	Résultat: array( 'Baz.id' => array( 'Bar' => 1 ), 'Foobar' => array( 'Baz.bar = Bar.foo' ) );
		 *
		 * @see recursive_key_value_preg_replace
		 *
		 * @param string|array $subject
		 * @param array $replacement
		 * @return array
		 */
		function words_replace( $subject, array $replacements ) {
			$regexes = array();

			foreach( $replacements as $key => $value ) {
				$key = "/(?<!\.)(?<!\w)(".preg_quote( $key ).")(?!\w)/";
				$regexes[$key] = $value;
			}

			if( false === is_array( $subject ) ) {
				return preg_replace( array_keys( $regexes ), array_values( $regexes ), $subject );
			}
			else {
				return recursive_key_value_preg_replace( $subject, $regexes );
			}
		}
	}

	if( false === function_exists( 'core_version' ) ) {
		/**
		 * Retourne la version de CakePHP utilisée.
		 *
		 * @return string
		 */
		function core_version() {
			$versionData = array_filter( explode( "\n", file_get_contents( CAKE.'VERSION.txt' ) ) );
			$version = explode( '.', $versionData[count( $versionData ) - 1] );
			return implode( '.', $version );
		}
	}

	if( false === function_exists( 'merge_conditions' ) ) {
		/**
		 * Merges a mixed set of string/array conditions
		 *
		 * @see Cake.Model.Datasource.DboSource::_mergeConditions()
		 *
		 * @param mixed $query
		 * @param mixed $assoc
		 * @return array
		 */
		function merge_conditions( $query, $assoc ) {
			if( empty( $assoc ) ) {
				return $query;
			}

			if (is_array($query)) {
				return array_merge((array)$assoc, $query);
			}

			if (!empty($query)) {
				$query = array($query);
				if (is_array($assoc)) {
					$query = array_merge($query, $assoc);
				} else {
					$query[] = $assoc;
				}
				return $query;
			}

			return $assoc;
		}
	}
?>
