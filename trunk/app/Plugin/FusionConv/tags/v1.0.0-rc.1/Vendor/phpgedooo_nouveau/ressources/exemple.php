<?
$URLmodel="http://myserserver/myappl/modeles/Invitation.ott";
$URLdata="http://myserserver/myappl/donnees/Jean.xml";
$URLnotify="http://myserserver/myappl/notify.php?session=1234&code=";
?>

<FORM Action="http://gedooo-server/phpgedooo/generator.php" METHOD="GET">

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