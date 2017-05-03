<?php

use phpgedooo_client\GDO_PartType;
use phpgedooo_client\GDO_IterationType;
use phpgedooo_client\GDO_FieldType;
use phpgedooo_client\GDO_ContentType;

/**
 * Code source de la classe FusionConvBuilder.
 *
 * PHP 5.3
 *
 * @package FusionConv
 * @subpackage Utility
 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
 */

/**
 * La classe FusionConvBuilder ...
 *
 * @package FusionConv
 * @subpackage Utility
 */
abstract class FusionConvBuilder {

    /**
     *
     * @param GDO_PartType $GDOPartType
     * @param array $data
     * @param array $types
     * @param array $correspondances
     * @return \GDO_PartType
     */
    public static function main(GDO_PartType $GDOPartType, array $data, array $types, array $correspondances) {
        foreach ($correspondances as $newKey => $alias) {

            $aliasSplit = explode('.', $newKey);
            $count = count($aliasSplit);

            $keyExists = (
                    ( $count == 1 && array_key_exists($alias, $data) ) || ( $count == 2 && array_key_exists($aliasSplit[0], $data) && array_key_exists($aliasSplit[1], $data[$aliasSplit[0]]) )
                    // INFO: Hash::check ne fonctionne pas bien si la valeur est null
                    || Hash::check($data, $alias)
                    );

            if ($keyExists) {
                $value = Hash::get($data, $alias);
                $type = ( isset($types[$newKey]) ? $types[$newKey] : 'text' );


                if ($type == 'file') {
                    $GDOPartType->addElement(new GDO_ContentType($alias, $alias . '.odt', 'application/vnd.oasis.opendocument.text', 'binary', $value));
                } else {
                    $value = mb_convert_encoding($value, "UTF-8", mb_detect_encoding($value));
                    $GDOPartType->addElement(new GDO_FieldType($alias, $value, $type));
                }
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
    public static function iteration(GDO_PartType $MainPart, $iterationName, array $datas, array $types, array $correspondances) {
        $Iteration = new GDO_IterationType($iterationName);
        foreach ($datas[$iterationName] as $data) {
            $InnerPart = new GDO_PartType();
            $InnerPart = self::main($InnerPart, $data, $types, $correspondances);
            foreach ($data as $key_iteration => $value) {
                if (is_array($value)) {
                    self::iteration($InnerPart, $key_iteration, array($key_iteration => $value), $types, $correspondances);
                }
            }
            $Iteration->addPart($InnerPart);
        }

        $MainPart->addElement($Iteration);

        return $MainPart;
    }

}
