<?php
	/**
	 * Fichier source de la classe CakeTestSelectOptions.
	 *
	 * PHP 5.3
	 * @package CakeTest
	 * @subpackage View.Helper
	 */

	/**
	 * La classe CakeTestSelectOptions permet d'obtenir des options select de
	 * dates pour clarifier l'écriture de tests unitaires.
	 *
	 * @package CakeTest
	 * @subpackage View.Helper
	 */
	abstract class CakeTestSelectOptions
	{
		public static function years( $fromYear, $toYear, $selectedYear ) {
			$years = range( $fromYear, $toYear, ( $fromYear > $toYear ) ? -1 : 1 );

			foreach( $years as $i => $year ) {
				$selected = ( ( $year == $selectedYear ) ? ' selected="selected"' : '' );
				$years[$i] = "<option value=\"{$year}\"{$selected}>{$year}</option>";
			}

			return implode( "\n", $years );
		}

		public static function months( $selectedMonth ) {
			$months = range( 1, 12 );

			foreach( $months as $i => $month ) {
				$selected = ( ( $month == $selectedMonth ) ? ' selected="selected"' : '' );
				$monthNumber = sprintf( '%02d', $month );
				$monthLabel = strftime( '%B', strtotime( "2014-{$month}-01" ) );
				$months[$i] = "<option value=\"{$monthNumber}\"{$selected}>{$monthLabel}</option>";
			}

			return implode( "\n", $months );
		}

		public static function days( $selectedDay ) {
			$days = range( 1, 31 );

			foreach( $days as $i => $day ) {
				$selected = ( ( $day == $selectedDay ) ? ' selected="selected"' : '' );
				$dayNumber = sprintf( '%02d', $day );
				$dayLabel = $day;
				$days[$i] = "<option value=\"{$dayNumber}\"{$selected}>{$dayLabel}</option>";
			}

			return implode( "\n", $days );
		}

		/**
		 * Retourne les options select d'une période (date de début, date de fin
		 * bornes comprises) à partir de 2 chaînes de caractères.
		 *
		 * @see strtotime
		 *
		 * @param string $timestampFrom
		 * @param string $timestampTo
		 * @return array Clés From et To, sous-clés years, months et days
		 */
		public static function ymdRange( $timestampFrom, $timestampTo ) {
			$timestampFrom = strtotime( '-1 week' );
			$timestampTo = strtotime( 'now' );

			$thisYearFrom = date( 'Y', $timestampFrom );
			$thisYearTo = date( 'Y', $timestampTo );
			$yearsFrom = CakeTestSelectOptions::years( $thisYearFrom, $thisYearFrom - 120, $thisYearFrom );
			$yearsTo = CakeTestSelectOptions::years( $thisYearTo + 5, $thisYearTo - 120, $thisYearTo );

			$thisMonthFrom = date( 'm', $timestampFrom );
			$thisMonthTo = date( 'm', $timestampTo );
			$monthsFrom = CakeTestSelectOptions::months( $thisMonthFrom );
			$monthsTo = CakeTestSelectOptions::months( $thisMonthTo );

			$thisDayFrom = date( 'd', $timestampFrom );
			$thisDayTo = date( 'd', $timestampTo );
			$daysFrom = CakeTestSelectOptions::days( $thisDayFrom );
			$daysTo = CakeTestSelectOptions::days( $thisDayTo );

			return array(
				'From' => array(
					'years' =>  $yearsFrom,
					'months' =>  $monthsFrom,
					'days' =>  $daysFrom,
				),
				'To' => array(
					'years' =>  $yearsTo,
					'months' =>  $monthsTo,
					'days' =>  $daysTo,
				),
			);
		}
	}
?>
