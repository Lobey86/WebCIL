<?php
	/**
	 * Code source de la classe FusionConvBuilder.
	 *
	 * PHP 5.3
	 *
	 * @package FusionConv
	 * @subpackage Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	// require_once( dirname( __FILE__ ).DS.'..'.DS.'Config'.DS.'bootstrap.php' );

	/**
	 * La classe FusionConvBuilder ...
	 *
	 * @package FusionConv
	 * @subpackage Utility
	 */
	abstract class FusionConvBuilder
	{
		/**
		 *
		 * @param GDO_PartType $GDOPartType
		 * @param array $data
		 * @param array $types
		 * @param array $correspondances
		 * @return \GDO_PartType
		 */
		public static function main( GDO_PartType $GDOPartType, array $data, array $types, array $correspondances ) {
			foreach( $correspondances as $newKey => $oldKey ) {
				$oldKeySplit = explode( '.', $oldKey );
				$count = count( $oldKeySplit );

				$keyExists = (
					( $count == 1 && array_key_exists( $oldKey, $data ) )
					|| ( $count == 2 && array_key_exists( $oldKeySplit[0], $data ) && array_key_exists( $oldKeySplit[1], $data[$oldKeySplit[0]] ) )
					// INFO: Hash::check ne fonctionne pas bien si la valeur est null
					|| Hash::check( $data, $oldKey )
				);

				if( $keyExists ) {
					$value = Hash::get( $data, $oldKey );
					// $value = utf8_encode( $value );

					$type = ( isset( $types[$oldKey] ) ? $types[$oldKey] : 'text' );

					$GDOPartType->addElement( new GDO_FieldType( $newKey, $value, $type ) );
				}
			}

			return $GDOPartType;
		}

		/**
		 *
		 * @param GDO_PartType $MainPart
		 * @param type $iterationName
		 * @param array $datas
		 * @param array $types
		 * @param array $correspondances
		 * @return \GDO_PartType
		 */
		public static function iteration( GDO_PartType $MainPart, $iterationName, array $datas, array $types, array $correspondances ) {
			$Iteration = new GDO_IterationType( $iterationName );

			foreach( $datas as $data ) {
				$InnerPart = new GDO_PartType();
				$InnerPart = self::main( $InnerPart, $data, $types, $correspondances );
				$Iteration->addPart( $InnerPart );
			}
			$MainPart->addElement( $Iteration );

			return $MainPart;
		}
	}
?>