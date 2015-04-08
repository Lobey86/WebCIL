<?php

$data = "http://arrigo-dev/office/tmp/gedooo.xml";
$model = "http://arrigo-dev/office/tmp/Section.ott";
$Format = "pdf";

$url = "http://arrigo-test/phpgedooo/generator.php?";

$url .= "data=".urlencode($data);
$url .= "&model=".urlencode($model);
$url .= "&Format=".$Format;

echo "Cliquez <a href=\"$url\">ici</a>";

?>