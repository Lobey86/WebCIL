<?php
	/**
	 * Code source de la classe FusionConvBuilderTest.
	 *
	 * PHP 5.3
	 *
	 * @package FusionConv
	 * @subpackage Test.Case.Utility
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'FusionConvBuilder', 'FusionConv.Utility' );
	require_once( dirname( __FILE__ ).DS.'..'.DS.'..'.DS.'..'.DS.'Config'.DS.'bootstrap.php' );

	/**
	 * La classe FusionConvBuilderTest ...
	 *
	 * @package FusionConv
	 * @subpackage Test.Case.Utility
	 */
	class FusionConvBuilderTest extends CakeTestCase
	{
		/**
		 * Test de la méthode FusionConvBuilder::main()
		 */
		public function testMain() {
			$data = array(
				'User' => array(
					'id' => 4,
					'username' => 'cbuffin',
					'birthday' => '1979-01-24',
				)
			);
			$types = array(
				'User.id' => 'number',
				'User.username' => 'text',
				'User.birthday' => 'date',
			);
			$correspondances = array(
				'user_id' => 'User.id',
				'user_username' => 'User.username',
				'user_birthday' => 'User.birthday',
			);

			$MainPart = new GDO_PartType();
			$result = FusionConvBuilder::main( $MainPart, $data, $types, $correspondances );

			$expected = array(
				new GDO_FieldType( 'user_id', '4', 'number' ),
				new GDO_FieldType( 'user_username', 'cbuffin', 'text' ),
				new GDO_FieldType( 'user_birthday', '1979-01-24', 'date' )
			);
			$this->assertEquals( $result->field, $expected, var_export( $result->field, true ) );
		}

		/**
		 * Test de la méthode FusionConvBuilder::iteration()
		 */
		public function testIteration() {
			$data = array(
				array(
					'User' => array(
						'id' => 4,
						'username' => 'cbuffin',
						'birthday' => '1979-01-24',
					)
				),
				array(
					'User' => array(
						'id' => 5,
						'username' => 'pmason',
						'birthday' => '1982-03-12',
					)
				)
			);
			$types = array(
				'User.id' => 'number',
				'User.username' => 'text',
				'User.birthday' => 'date',
			);
			$correspondances = array(
				'user_id' => 'User.id',
				'user_username' => 'User.username',
				'user_birthday' => 'User.birthday',
			);

			$MainPart = new GDO_PartType();
			$result = FusionConvBuilder::iteration( $MainPart, 'IterationName', $data, $types, $correspondances );

			$expected = new GDO_IterationType( 'IterationName' );
			$expected->addPart( FusionConvBuilder::main( new GDO_PartType(), $data[0], $types, $correspondances ) );
			$expected->addPart( FusionConvBuilder::main( new GDO_PartType(), $data[1], $types, $correspondances ) );
			$expected = array( $expected );

			$this->assertEquals( $result->iteration, $expected, var_export( $result->iteration, true ) );
		}
	}