<?php
	/**
	 * Code source de la classe FusionConvConverterCloudooo.
	 *
	 * PHP 5.3
	 *
	 * @package FusionConv
	 * @subpackage Utility.Converter
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Debugger', 'Utility' );
	App::uses( 'FusionConvAbstractConverter', 'FusionConv.Utility/Converter' );

	/**
	 * La classe FusionConvConverterCloudooo permet de convertir un document d'un
	 * format vers un autre (par défaut, odt vers pdf) en utilisant un serveur
	 * Cloudooo.
	 *
	 * @package FusionConv
	 * @subpackage Utility.Converter
	 */
	abstract class FusionConvConverterCloudooo extends FusionConvAbstractConverter
	{
		/**
		 * L'adresse du serveur Cloudooo.
		 *
		 * @var string
		 */
		protected static $_server = null;

		/**
		 * Le port du serveur Cloudooo.
		 *
		 * @var integer
		 */
		protected static $_port = null;

		/**
		 * La version des classes XMLRPC: 1 ou 2
		 *
		 * @var integer
		 */
		protected static $_xmlRpcClass = null;

        /**
         * Les chemins vers les variables dans la configuration CakePHP:
         *  - FusionConv.FusionConvConverterCloudooo.xml_rpc_class (integer, défaut 1, valeur 1 ou 2)
		 *	- FusionConv.FusionConvConverterCloudooo.server (string, défaut 127.0.01)
		 *	- FusionConv.FusionConvConverterCloudooo.port (integer, défaut 8011)
         *
         * @todo defaultInputFormat, defaultOutputFormat ?
         *
         * @var array
         */
        public static $configured = array(
            'xml_rpc_class' => array(
                'path' => 'FusionConv.FusionConvConverterCloudooo.xml_rpc_class',
                'type' => 'integer',
                'default' => 2,
                'in_list' => array( 1, 2 ),
            ),
            'server' => array(
                'path' => 'FusionConv.FusionConvConverterCloudooo.server',
                'type' => 'string',
                'default' => '127.0.01',
            ),
            'port' => array(
                'path' => 'FusionConv.FusionConvConverterCloudooo.port',
                'type' => 'integer',
                'default' => 8011,
            ),
        );

		/**
		 * Initialisation: si le serveur ou le port n'ont pas été spécifiés, on
		 * essaie de lire leurs valeurs dans la configuration ou on prend les
         * valeurs par défaut.
         *
         * @see self::$configure
		 */
		protected static function _init() {
            self::$_server = self::_configured( self::$configured, 'server', self::$_server );
            self::$_port = self::_configured( self::$configured, 'port', self::$_port );
            self::$_xmlRpcClass = self::_configured( self::$configured, 'xml_rpc_class', self::$_xmlRpcClass );
		}

        /**
         * Version XmlRpc de self::convert().
         *
		 * @param string $content Le contenu du fichier à convertir
		 * @param string $inputFormat Le format d'entrée du fichier.
		 * @param string $outputFormat Le format de sortie du fichier.
		 * @return string
         */
        protected static function _xmlRpc1Convert( $content, $inputFormat, $outputFormat ) {
			require_once 'XML/RPC.php';

			$params = array(
				new XML_RPC_Value( base64_encode( $content ), 'string' ),
				new XML_RPC_Value( $inputFormat, 'string' ),
				new XML_RPC_Value( $outputFormat, 'string' ),
				new XML_RPC_Value( false, 'boolean' ),
				new XML_RPC_Value( true, 'boolean' )
			);

			$Message = new XML_RPC_Message( 'convertFile', $params );
			$Client = new XML_RPC_Client( '/', self::$_server, self::$_port );
			$Response = $Client->send( $Message );

			$msgid = "Erreur du serveur Cloudooo \"%s:%d\": %s";
			if( empty( $Response ) ) {
				Debugger::log( sprintf( $msgid, self::$_server, self::$_port, $Client->errstr ), LOG_ERROR );
				return false;
			}

			if( empty( $Response->xv ) ) {
				Debugger::log( sprintf( $msgid, self::$_server, self::$_port, $Response->fs ), LOG_ERROR );
				return false;
			}

			return base64_decode( @$Response->xv->me['string'] );
        }

        /**
         * Version XmlRpc2 de self::convert().
         *
		 * @param string $content Le contenu du fichier à convertir
		 * @param string $inputFormat Le format d'entrée du fichier.
		 * @param string $outputFormat Le format de sortie du fichier.
		 * @return string
         */
        protected static function _xmlRpc2Convert( $content, $inputFormat, $outputFormat ) {
            require_once 'XML/RPC2/Client.php';
			require_once 'XML/RPC2/Value.php';

			$params = array(
                XML_RPC2_Value::createFromNative( $content, 'base64'),
                XML_RPC2_Value::createFromNative( $inputFormat, 'string'),
                XML_RPC2_Value::createFromNative( $outputFormat, 'string'),
                XML_RPC2_Value::createFromNative( false, 'string'),
                XML_RPC2_Value::createFromNative( true, 'string')
			);

            $uri = sprintf( "http://%s:%d/", self::$_server, self::$_port );
            $Response = null;
            $errorMsgid = "Erreur du serveur Cloudooo \"%s\" (code %d): %s";

            try {
                $Client = XML_RPC2_Client::create( $uri, array( 'debug' => false ) );
                $Response = $Client->convertFile( $params );
            } catch( XML_RPC2_CurlException $XML_RPC2_CurlException ) {
                $lines = preg_split( "/\n/", $XML_RPC2_CurlException->getMessage() );
                if( isset( $lines[1] ) ) {
                    $message = trim( preg_replace( '/(<\/{0,1}b>|<tr><td[^>]+>|<\/td><\/tr>)/', '', $lines[1] ) );
                }
                else {
                    $message = var_export( $XML_RPC2_CurlException->getMessage(), true );
                }
				Debugger::log( sprintf( $errorMsgid, $uri, $XML_RPC2_CurlException->getCode(), $message ), LOG_ERROR );
				return false;
            } catch( Exception $Exception ) {
				Debugger::log( sprintf( $errorMsgid, $uri, $Exception->getCode(), $Exception->getMessage() ), LOG_ERROR );
				return false;
            }

			return base64_decode( @$Response->xv->me['string'] );
        }

		/**
		 * Initialisation et conversion du contenu d'un fichier d'un format vers
		 * un autre.
         *
		 * @param string $content Le contenu du fichier à convertir
		 * @param string $inputFormat Le format d'entrée du fichier.
		 * @param string $outputFormat Le format de sortie du fichier.
		 * @return string
		 */
		public static function convert( $content, $inputFormat = 'odt', $outputFormat = 'pdf' ) {
			self::_init();

            if( self::$_xmlRpcClass == 2 ) {
                return self::_xmlRpc2Convert( $content, $inputFormat, $outputFormat );
            }

			return self::_xmlRpc1Convert( $content, $inputFormat, $outputFormat );
		}
	}
?>