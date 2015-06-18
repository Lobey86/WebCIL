<?php
/* Configuration pour la conversion de format des documents */
/* Configuration FusionConv */
Configure::write('FusionConv.Gedooo.wsdl', 'http://192.168.2.35:8880/ODFgedooo-1.0.1/OfficeService?wsdl');
Configure::write('FusionConv.method', 'GedoooCloudooo');
Configure::write('FusionConv.cloudooo_host', '192.168.2.35');
Configure::write('FusionConv.cloudooo_port', '8011');
Configure::write('FusionConv.FusionConvConverterCloudooo.xml_rpc_class', 1);
Configure::write('FusionConv.FusionConvConverterCloudooo.server', '192.168.2.35');
Configure::write('FusionConv.FusionConvConverterCloudooo.port', '8011');

/* Configuration pour la manipulation des pdf */
Configure::write('PDFTK_EXEC', '/usr/bin/pdftk');
Configure::write('PDFINFO_EXEC', '/usr/bin/pdfinfo');