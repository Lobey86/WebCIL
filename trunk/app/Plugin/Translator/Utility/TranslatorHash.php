<?php
	/**
	 * Code source de la classe TranslatorHash.
	 *
	 * @package Translator
	 * @subpackage Utility
	 */

	/**
	 * The Storage class provides a subset of functionalities from Cake's Hash utility
	 * class (check, insert, get).
	 * Provided simple paths are provided as array of keys, (meaningless) dots can be
	 * used in paths.
	 */
	class TranslatorHash
	{
		/**
		 * Checks if a path exists in a given multi-dimensional array.
		 *
		 * @param array $data The data to check
		 * @param array $path A path made of array key names
		 * @return bool
		 */
		public static function exists(array &$data, array $path) {
			if (empty($path)) {
				return false;
			}
			$current = &$data;
			foreach ($path as $key) {
				if (isset($current[$key])) {
					$current = &$current[$key];
				} else {
					return false;
				}
			}
			return true;
		}
		
		/**
		 * Inserts a value at a given path in a given multi-dimensional array
		 * and returns it.
		 *
		 * @param array $data The data to insert to value into
		 * @param array $path A path made of array key names
		 * @param mixed $value A value to insert
		 * @return array
		 */
		public static function insert(array $data, array $path, $value) {
			if (empty($path)) {
				return false;
			}
			$current = &$data;
			foreach ($path as $key) {
				if (false === isset($current[$key])) {
					$current[$key] = array();
				}
				$current = &$current[$key];
			}
			$current = $value;
			return $data;
		}
		
		/**
		 * Returns the value at a given path in a multi-dimensional array.
		 *
		 * @param array $data The data to get the value from
		 * @param array $path A path made of array key names
		 * @param mixed $default The default value to return if the path keys do not exist
		 * @return mixed
		 */
		public static function get(array &$data, array $path, $default = null) {
			if (empty($path)) {
				return $default;
			}
			$current = &$data;
			foreach ($path as $key) {
				if (false === isset($current[$key])) {
					return $default;
				}
				$current = &$current[$key];
			}
			return $current;
		}
	}