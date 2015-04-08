<html>

<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 <title>GED'OOo</title>

</head>

<body bgcolor="#FFFFFF">

<h1 align="center">D&eacute;monstration de GED'OOo en mode GET</h1>

Cliquez sur le bouton ci-dessous pour obtenir le document

<?
$URLmodel="http://arrigo-dev/office/tmp/Section.ott";
$URLdata="http://arrigo-dev/office/tmp/gedooo.xml";
$URLnotify="http://arrigo-dev/phpgedooo/notify.php?code=";
?>
<FORM Action="http://arrigo-dev/phpgedooo/generator.php" METHOD="GET">

<table>
<tr>
<td>
ODT <input type="radio" name="Format" value="odt" CHECKED>
</td>
<td>
PDF <input type="radio" name="Format" value="pdf">
</td>
<td>
<input type="submit" name="Valider" value="T&eacute;l&eacute;charger">
</td>
</tr>
</table>

<input type="hidden" name="model" value="<? echo $URLmodel;?>">
<input type="hidden" name="data" value="<? echo $URLdata;?>">
<input type="hidden" name="notify" value="<? echo $URLnotify;?>">

</FORM>


</body>
</html>
