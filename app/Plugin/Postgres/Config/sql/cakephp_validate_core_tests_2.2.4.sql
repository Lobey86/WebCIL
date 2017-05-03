/*
* CakePHP validation functions in PlPgSQL
* Unit tests from CakePHP 2.2.4
* Tested with:
* 	- PostgreSQL 8.3.3 on i686-pc-linux-gnu, compiled by GCC gcc (GCC) 4.2.4
*
* @see http://book.cakephp.org/view/125/Data-Validation#!/view/134/Core-Validation-Rules
* @see http://api.cakephp.org/class/validation
*
* @see http://archives.postgresql.org/pgsql-de-allgemein/2004-09/msg00001.php
* @see http://stackoverflow.com/questions/2978751/why-repeat-database-constraints-in-models
*
* @see http://bakery.cakephp.org/articles/mattc/2008/10/26/automagic-javascript-validation-helper
* @see https://github.com/mcurry/js_validate
*/
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

-- *****************************************************************************
BEGIN;
-- *****************************************************************************

SELECT
		cakephp_validate_not_empty('abcdefg')= true
		AND cakephp_validate_not_empty('fasdf ')= true
		AND cakephp_validate_not_empty('fooo' || chr(243) || 'blabla')= true
		AND cakephp_validate_not_empty('abçďĕʑʘπй')= true
		AND cakephp_validate_not_empty('José')= true
		AND cakephp_validate_not_empty('é')= true
		AND cakephp_validate_not_empty('π')= true
		AND cakephp_validate_not_empty('\t ')= false
		AND cakephp_validate_not_empty('')= false
	AS testNotEmpty;

	SELECT
		cakephp_validate_alpha_numeric('frferrf')= true
		AND cakephp_validate_alpha_numeric('12234')= true
		AND cakephp_validate_alpha_numeric('1w2e2r3t4y')= true
		AND cakephp_validate_alpha_numeric('0')= true
		AND cakephp_validate_alpha_numeric('abçďĕʑʘπй')= true
		AND cakephp_validate_alpha_numeric('ˇˆๆゞ')= true
		AND cakephp_validate_alpha_numeric('אกあアꀀ豈')= true
		AND cakephp_validate_alpha_numeric('ǅᾈᾨ')= true
		AND cakephp_validate_alpha_numeric('ÆΔΩЖÇ')= true

		AND cakephp_validate_alpha_numeric('12 234')= false
		AND cakephp_validate_alpha_numeric('dfd 234')= false
		AND cakephp_validate_alpha_numeric('\n')= false
		AND cakephp_validate_alpha_numeric('\t')= false
		AND cakephp_validate_alpha_numeric('\r')= false
		AND cakephp_validate_alpha_numeric(' ')= false
		AND cakephp_validate_alpha_numeric('')= false
	AS testAlphaNumeric;

	SELECT
		cakephp_validate_between('abcdefg', 1, 7)= true
		AND cakephp_validate_between('', 0, 7)= true
		AND cakephp_validate_between('אกあアꀀ豈', 1, 7)= true
		AND cakephp_validate_between('abcdefg', 1, 6)= false
		AND cakephp_validate_between('ÆΔΩЖÇ', 1, 3)= false
	AS testBetween;


	SELECT cakephp_validate_blank('')= true
		AND cakephp_validate_blank(' ')= true
		AND cakephp_validate_blank('\n')= true
		AND cakephp_validate_blank('\t')= true
		AND cakephp_validate_blank('\r')= true
		AND cakephp_validate_blank('    Blank')= false
		AND cakephp_validate_blank('Blank')= false
	AS testBlank;

SELECT
		--American Express
		cakephp_validate_cc('370482756063980', ARRAY['amex'])= true
		AND cakephp_validate_cc('349106433773483', ARRAY['amex'])= true
		AND cakephp_validate_cc('344671486204764', ARRAY['amex'])= true
		AND cakephp_validate_cc('344042544509943', ARRAY['amex'])= true
		AND cakephp_validate_cc('377147515754475', ARRAY['amex'])= true
		AND cakephp_validate_cc('375239372816422', ARRAY['amex'])= true
		AND cakephp_validate_cc('376294341957707', ARRAY['amex'])= true
		AND cakephp_validate_cc('341779292230411', ARRAY['amex'])= true
		AND cakephp_validate_cc('341646919853372', ARRAY['amex'])= true
		AND cakephp_validate_cc('348498616319346', ARRAY['amex'])= true
		--BankCard
		AND cakephp_validate_cc('5610745867413420', ARRAY['bankcard'])= true
		AND cakephp_validate_cc('5610376649499352', ARRAY['bankcard'])= true
		AND cakephp_validate_cc('5610091936000694', ARRAY['bankcard'])= true
		AND cakephp_validate_cc('5602248780118788', ARRAY['bankcard'])= true
		AND cakephp_validate_cc('5610631567676765', ARRAY['bankcard'])= true
		AND cakephp_validate_cc('5602238211270795', ARRAY['bankcard'])= true
		AND cakephp_validate_cc('5610173951215470', ARRAY['bankcard'])= true
		AND cakephp_validate_cc('5610139705753702', ARRAY['bankcard'])= true
		AND cakephp_validate_cc('5602226032150551', ARRAY['bankcard'])= true
		AND cakephp_validate_cc('5602223993735777', ARRAY['bankcard'])= true
		--Diners Club 14
		AND cakephp_validate_cc('30155483651028', ARRAY['diners'])= true
		AND cakephp_validate_cc('36371312803821', ARRAY['diners'])= true
		AND cakephp_validate_cc('38801277489875', ARRAY['diners'])= true
		AND cakephp_validate_cc('30348560464296', ARRAY['diners'])= true
		AND cakephp_validate_cc('30349040317708', ARRAY['diners'])= true
		AND cakephp_validate_cc('36567413559978', ARRAY['diners'])= true
		AND cakephp_validate_cc('36051554732702', ARRAY['diners'])= true
		AND cakephp_validate_cc('30391842198191', ARRAY['diners'])= true
		AND cakephp_validate_cc('30172682197745', ARRAY['diners'])= true
		AND cakephp_validate_cc('30162056566641', ARRAY['diners'])= true
		AND cakephp_validate_cc('30085066927745', ARRAY['diners'])= true
		AND cakephp_validate_cc('36519025221976', ARRAY['diners'])= true
		AND cakephp_validate_cc('30372679371044', ARRAY['diners'])= true
		AND cakephp_validate_cc('38913939150124', ARRAY['diners'])= true
		AND cakephp_validate_cc('36852899094637', ARRAY['diners'])= true
		AND cakephp_validate_cc('30138041971120', ARRAY['diners'])= true
		AND cakephp_validate_cc('36184047836838', ARRAY['diners'])= true
		AND cakephp_validate_cc('30057460264462', ARRAY['diners'])= true
		AND cakephp_validate_cc('38980165212050', ARRAY['diners'])= true
		AND cakephp_validate_cc('30356516881240', ARRAY['diners'])= true
		AND cakephp_validate_cc('38744810033182', ARRAY['diners'])= true
		AND cakephp_validate_cc('30173638706621', ARRAY['diners'])= true
		AND cakephp_validate_cc('30158334709185', ARRAY['diners'])= true
		AND cakephp_validate_cc('30195413721186', ARRAY['diners'])= true
		AND cakephp_validate_cc('38863347694793', ARRAY['diners'])= true
		AND cakephp_validate_cc('30275627009113', ARRAY['diners'])= true
		AND cakephp_validate_cc('30242860404971', ARRAY['diners'])= true
		AND cakephp_validate_cc('30081877595151', ARRAY['diners'])= true
		AND cakephp_validate_cc('38053196067461', ARRAY['diners'])= true
		AND cakephp_validate_cc('36520379984870', ARRAY['diners'])= true
		--2004 MasterCard/Diners Club Alliance International 14
		AND cakephp_validate_cc('36747701998969', ARRAY['diners'])= true
		AND cakephp_validate_cc('36427861123159', ARRAY['diners'])= true
		AND cakephp_validate_cc('36150537602386', ARRAY['diners'])= true
		AND cakephp_validate_cc('36582388820610', ARRAY['diners'])= true
		AND cakephp_validate_cc('36729045250216', ARRAY['diners'])= true
		--2004 MasterCard/Diners Club Alliance US & Canada 16
		AND cakephp_validate_cc('5597511346169950', ARRAY['diners'])= true
		AND cakephp_validate_cc('5526443162217562', ARRAY['diners'])= true
		AND cakephp_validate_cc('5577265786122391', ARRAY['diners'])= true
		AND cakephp_validate_cc('5534061404676989', ARRAY['diners'])= true
		AND cakephp_validate_cc('5545313588374502', ARRAY['diners'])= true
		--Discover
		AND cakephp_validate_cc('6011802876467237', ARRAY['disc'])= true
		AND cakephp_validate_cc('6506432777720955', ARRAY['disc'])= true
		AND cakephp_validate_cc('6011126265283942', ARRAY['disc'])= true
		AND cakephp_validate_cc('6502187151579252', ARRAY['disc'])= true
		AND cakephp_validate_cc('6506600836002298', ARRAY['disc'])= true
		AND cakephp_validate_cc('6504376463615189', ARRAY['disc'])= true
		AND cakephp_validate_cc('6011440907005377', ARRAY['disc'])= true
		AND cakephp_validate_cc('6509735979634270', ARRAY['disc'])= true
		AND cakephp_validate_cc('6011422366775856', ARRAY['disc'])= true
		AND cakephp_validate_cc('6500976374623323', ARRAY['disc'])= true
		--enRoute
		AND cakephp_validate_cc('201496944158937', ARRAY['enroute'])= true
		AND cakephp_validate_cc('214945833739665', ARRAY['enroute'])= true
		AND cakephp_validate_cc('214982692491187', ARRAY['enroute'])= true
		AND cakephp_validate_cc('214901395949424', ARRAY['enroute'])= true
		AND cakephp_validate_cc('201480676269187', ARRAY['enroute'])= true
		AND cakephp_validate_cc('214911922887807', ARRAY['enroute'])= true
		AND cakephp_validate_cc('201485025457250', ARRAY['enroute'])= true
		AND cakephp_validate_cc('201402662758866', ARRAY['enroute'])= true
		AND cakephp_validate_cc('214981579370225', ARRAY['enroute'])= true
		AND cakephp_validate_cc('201447595859877', ARRAY['enroute'])= true
		--JCB 15 digit
		AND cakephp_validate_cc('210034762247893', ARRAY['jcb'])= true
		AND cakephp_validate_cc('180078671678892', ARRAY['jcb'])= true
		AND cakephp_validate_cc('180010559353736', ARRAY['jcb'])= true
		AND cakephp_validate_cc('210095474464258', ARRAY['jcb'])= true
		AND cakephp_validate_cc('210006675562188', ARRAY['jcb'])= true
		AND cakephp_validate_cc('210063299662662', ARRAY['jcb'])= true
		AND cakephp_validate_cc('180032506857825', ARRAY['jcb'])= true
		AND cakephp_validate_cc('210057919192738', ARRAY['jcb'])= true
		AND cakephp_validate_cc('180031358949367', ARRAY['jcb'])= true
		AND cakephp_validate_cc('180033802147846', ARRAY['jcb'])= true
		--JCB 16 digit
		AND cakephp_validate_cc('3096806857839939', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3158699503187091', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3112549607186579', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3112332922425604', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3112001541159239', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3112162495317841', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3337562627732768', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3337107161330775', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3528053736003621', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3528915255020360', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3096786059660921', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3528264799292320', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3096469164130136', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3112127443822853', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3096849995802328', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3528090735127407', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3112101006819234', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3337444428040784', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3088043154151061', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3088295969414866', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3158748843158575', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3158709206148538', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3158365159575324', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3158671691305165', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3528523028771093', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3096057126267870', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3158514047166834', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3528274546125962', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3528890967705733', ARRAY['jcb'])= true
		AND cakephp_validate_cc('3337198811307545', ARRAY['jcb'])= true
		--Maestro (debit card)
		AND cakephp_validate_cc('5020147409985219', ARRAY['maestro'])= true
		AND cakephp_validate_cc('5020931809905616', ARRAY['maestro'])= true
		AND cakephp_validate_cc('5020412965470224', ARRAY['maestro'])= true
		AND cakephp_validate_cc('5020129740944022', ARRAY['maestro'])= true
		AND cakephp_validate_cc('5020024696747943', ARRAY['maestro'])= true
		AND cakephp_validate_cc('5020581514636509', ARRAY['maestro'])= true
		AND cakephp_validate_cc('5020695008411987', ARRAY['maestro'])= true
		AND cakephp_validate_cc('5020565359718977', ARRAY['maestro'])= true
		AND cakephp_validate_cc('6339931536544062', ARRAY['maestro'])= true
		AND cakephp_validate_cc('6465028615704406', ARRAY['maestro'])= true
		--Mastercard
		AND cakephp_validate_cc('5580424361774366', ARRAY['mc'])= true
		AND cakephp_validate_cc('5589563059318282', ARRAY['mc'])= true
		AND cakephp_validate_cc('5387558333690047', ARRAY['mc'])= true
		AND cakephp_validate_cc('5163919215247175', ARRAY['mc'])= true
		AND cakephp_validate_cc('5386742685055055', ARRAY['mc'])= true
		AND cakephp_validate_cc('5102303335960674', ARRAY['mc'])= true
		AND cakephp_validate_cc('5526543403964565', ARRAY['mc'])= true
		AND cakephp_validate_cc('5538725892618432', ARRAY['mc'])= true
		AND cakephp_validate_cc('5119543573129778', ARRAY['mc'])= true
		AND cakephp_validate_cc('5391174753915767', ARRAY['mc'])= true
		AND cakephp_validate_cc('5510994113980714', ARRAY['mc'])= true
		AND cakephp_validate_cc('5183720260418091', ARRAY['mc'])= true
		AND cakephp_validate_cc('5488082196086704', ARRAY['mc'])= true
		AND cakephp_validate_cc('5484645164161834', ARRAY['mc'])= true
		AND cakephp_validate_cc('5171254350337031', ARRAY['mc'])= true
		AND cakephp_validate_cc('5526987528136452', ARRAY['mc'])= true
		AND cakephp_validate_cc('5504148941409358', ARRAY['mc'])= true
		AND cakephp_validate_cc('5240793507243615', ARRAY['mc'])= true
		AND cakephp_validate_cc('5162114693017107', ARRAY['mc'])= true
		AND cakephp_validate_cc('5163104807404753', ARRAY['mc'])= true
		AND cakephp_validate_cc('5590136167248365', ARRAY['mc'])= true
		AND cakephp_validate_cc('5565816281038948', ARRAY['mc'])= true
		AND cakephp_validate_cc('5467639122779531', ARRAY['mc'])= true
		AND cakephp_validate_cc('5297350261550024', ARRAY['mc'])= true
		AND cakephp_validate_cc('5162739131368058', ARRAY['mc'])= true
		--Solo 16
		AND cakephp_validate_cc('6767432107064987', ARRAY['solo'])= true
		AND cakephp_validate_cc('6334667758225411', ARRAY['solo'])= true
		AND cakephp_validate_cc('6767037421954068', ARRAY['solo'])= true
		AND cakephp_validate_cc('6767823306394854', ARRAY['solo'])= true
		AND cakephp_validate_cc('6334768185398134', ARRAY['solo'])= true
		AND cakephp_validate_cc('6767286729498589', ARRAY['solo'])= true
		AND cakephp_validate_cc('6334972104431261', ARRAY['solo'])= true
		AND cakephp_validate_cc('6334843427400616', ARRAY['solo'])= true
		AND cakephp_validate_cc('6767493947881311', ARRAY['solo'])= true
		AND cakephp_validate_cc('6767194235798817', ARRAY['solo'])= true
		--Solo 18
		AND cakephp_validate_cc('676714834398858593', ARRAY['solo'])= true
		AND cakephp_validate_cc('676751666435130857', ARRAY['solo'])= true
		AND cakephp_validate_cc('676781908573924236', ARRAY['solo'])= true
		AND cakephp_validate_cc('633488724644003240', ARRAY['solo'])= true
		AND cakephp_validate_cc('676732252338067316', ARRAY['solo'])= true
		AND cakephp_validate_cc('676747520084495821', ARRAY['solo'])= true
		AND cakephp_validate_cc('633465488901381957', ARRAY['solo'])= true
		AND cakephp_validate_cc('633487484858610484', ARRAY['solo'])= true
		AND cakephp_validate_cc('633453764680740694', ARRAY['solo'])= true
		AND cakephp_validate_cc('676768613295414451', ARRAY['solo'])= true
		--Solo 19
		AND cakephp_validate_cc('6767838565218340113', ARRAY['solo'])= true
		AND cakephp_validate_cc('6767760119829705181', ARRAY['solo'])= true
		AND cakephp_validate_cc('6767265917091593668', ARRAY['solo'])= true
		AND cakephp_validate_cc('6767938856947440111', ARRAY['solo'])= true
		AND cakephp_validate_cc('6767501945697390076', ARRAY['solo'])= true
		AND cakephp_validate_cc('6334902868716257379', ARRAY['solo'])= true
		AND cakephp_validate_cc('6334922127686425532', ARRAY['solo'])= true
		AND cakephp_validate_cc('6334933119080706440', ARRAY['solo'])= true
		AND cakephp_validate_cc('6334647959628261714', ARRAY['solo'])= true
		AND cakephp_validate_cc('6334527312384101382', ARRAY['solo'])= true
		--Switch 16
		AND cakephp_validate_cc('5641829171515733', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641824852820809', ARRAY['switch'])= true
		AND cakephp_validate_cc('6759129648956909', ARRAY['switch'])= true
		AND cakephp_validate_cc('6759626072268156', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641822698388957', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641827123105470', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641823755819553', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641821939587682', ARRAY['switch'])= true
		AND cakephp_validate_cc('4936097148079186', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641829739125009', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641822860725507', ARRAY['switch'])= true
		AND cakephp_validate_cc('4936717688865831', ARRAY['switch'])= true
		AND cakephp_validate_cc('6759487613615441', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641821346840617', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641825793417126', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641821302759595', ARRAY['switch'])= true
		AND cakephp_validate_cc('6759784969918837', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641824910667036', ARRAY['switch'])= true
		AND cakephp_validate_cc('6759139909636173', ARRAY['switch'])= true
		AND cakephp_validate_cc('6333425070638022', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641823910382067', ARRAY['switch'])= true
		AND cakephp_validate_cc('4936295218139423', ARRAY['switch'])= true
		AND cakephp_validate_cc('6333031811316199', ARRAY['switch'])= true
		AND cakephp_validate_cc('4936912044763198', ARRAY['switch'])= true
		AND cakephp_validate_cc('4936387053303824', ARRAY['switch'])= true
		AND cakephp_validate_cc('6759535838760523', ARRAY['switch'])= true
		AND cakephp_validate_cc('6333427174594051', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641829037102700', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641826495463046', ARRAY['switch'])= true
		AND cakephp_validate_cc('6333480852979946', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641827761302876', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641825083505317', ARRAY['switch'])= true
		AND cakephp_validate_cc('6759298096003991', ARRAY['switch'])= true
		AND cakephp_validate_cc('4936119165483420', ARRAY['switch'])= true
		AND cakephp_validate_cc('4936190990500993', ARRAY['switch'])= true
		AND cakephp_validate_cc('4903356467384927', ARRAY['switch'])= true
		AND cakephp_validate_cc('6333372765092554', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641821330950570', ARRAY['switch'])= true
		AND cakephp_validate_cc('6759841558826118', ARRAY['switch'])= true
		AND cakephp_validate_cc('4936164540922452', ARRAY['switch'])= true
		--Switch 18
		AND cakephp_validate_cc('493622764224625174', ARRAY['switch'])= true
		AND cakephp_validate_cc('564182823396913535', ARRAY['switch'])= true
		AND cakephp_validate_cc('675917308304801234', ARRAY['switch'])= true
		AND cakephp_validate_cc('675919890024220298', ARRAY['switch'])= true
		AND cakephp_validate_cc('633308376862556751', ARRAY['switch'])= true
		AND cakephp_validate_cc('564182377633208779', ARRAY['switch'])= true
		AND cakephp_validate_cc('564182870014926787', ARRAY['switch'])= true
		AND cakephp_validate_cc('675979788553829819', ARRAY['switch'])= true
		AND cakephp_validate_cc('493668394358130935', ARRAY['switch'])= true
		AND cakephp_validate_cc('493637431790930965', ARRAY['switch'])= true
		AND cakephp_validate_cc('633321438601941513', ARRAY['switch'])= true
		AND cakephp_validate_cc('675913800898840986', ARRAY['switch'])= true
		AND cakephp_validate_cc('564182592016841547', ARRAY['switch'])= true
		AND cakephp_validate_cc('564182428380440899', ARRAY['switch'])= true
		AND cakephp_validate_cc('493696376827623463', ARRAY['switch'])= true
		AND cakephp_validate_cc('675977939286485757', ARRAY['switch'])= true
		AND cakephp_validate_cc('490302699502091579', ARRAY['switch'])= true
		AND cakephp_validate_cc('564182085013662230', ARRAY['switch'])= true
		AND cakephp_validate_cc('493693054263310167', ARRAY['switch'])= true
		AND cakephp_validate_cc('633321755966697525', ARRAY['switch'])= true
		AND cakephp_validate_cc('675996851719732811', ARRAY['switch'])= true
		AND cakephp_validate_cc('493699211208281028', ARRAY['switch'])= true
		AND cakephp_validate_cc('493697817378356614', ARRAY['switch'])= true
		AND cakephp_validate_cc('675968224161768150', ARRAY['switch'])= true
		AND cakephp_validate_cc('493669416873337627', ARRAY['switch'])= true
		AND cakephp_validate_cc('564182439172549714', ARRAY['switch'])= true
		AND cakephp_validate_cc('675926914467673598', ARRAY['switch'])= true
		AND cakephp_validate_cc('564182565231977809', ARRAY['switch'])= true
		AND cakephp_validate_cc('675966282607849002', ARRAY['switch'])= true
		AND cakephp_validate_cc('493691609704348548', ARRAY['switch'])= true
		AND cakephp_validate_cc('675933118546065120', ARRAY['switch'])= true
		AND cakephp_validate_cc('493631116677238592', ARRAY['switch'])= true
		AND cakephp_validate_cc('675921142812825938', ARRAY['switch'])= true
		AND cakephp_validate_cc('633338311815675113', ARRAY['switch'])= true
		AND cakephp_validate_cc('633323539867338621', ARRAY['switch'])= true
		AND cakephp_validate_cc('675964912740845663', ARRAY['switch'])= true
		AND cakephp_validate_cc('633334008833727504', ARRAY['switch'])= true
		AND cakephp_validate_cc('493631941273687169', ARRAY['switch'])= true
		AND cakephp_validate_cc('564182971729706785', ARRAY['switch'])= true
		AND cakephp_validate_cc('633303461188963496', ARRAY['switch'])= true
		--Switch 19
		AND cakephp_validate_cc('6759603460617628716', ARRAY['switch'])= true
		AND cakephp_validate_cc('4936705825268647681', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641829846600479183', ARRAY['switch'])= true
		AND cakephp_validate_cc('6759389846573792530', ARRAY['switch'])= true
		AND cakephp_validate_cc('4936189558712637603', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641822217393868189', ARRAY['switch'])= true
		AND cakephp_validate_cc('4903075563780057152', ARRAY['switch'])= true
		AND cakephp_validate_cc('4936510653566569547', ARRAY['switch'])= true
		AND cakephp_validate_cc('4936503083627303364', ARRAY['switch'])= true
		AND cakephp_validate_cc('4936777334398116272', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641823876900554860', ARRAY['switch'])= true
		AND cakephp_validate_cc('6759619236903407276', ARRAY['switch'])= true
		AND cakephp_validate_cc('6759011470269978117', ARRAY['switch'])= true
		AND cakephp_validate_cc('6333175833997062502', ARRAY['switch'])= true
		AND cakephp_validate_cc('6759498728789080439', ARRAY['switch'])= true
		AND cakephp_validate_cc('4903020404168157841', ARRAY['switch'])= true
		AND cakephp_validate_cc('6759354334874804313', ARRAY['switch'])= true
		AND cakephp_validate_cc('6759900856420875115', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641827269346868860', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641828995047453870', ARRAY['switch'])= true
		AND cakephp_validate_cc('6333321884754806543', ARRAY['switch'])= true
		AND cakephp_validate_cc('6333108246283715901', ARRAY['switch'])= true
		AND cakephp_validate_cc('6759572372800700102', ARRAY['switch'])= true
		AND cakephp_validate_cc('4903095096797974933', ARRAY['switch'])= true
		AND cakephp_validate_cc('6333354315797920215', ARRAY['switch'])= true
		AND cakephp_validate_cc('6759163746089433755', ARRAY['switch'])= true
		AND cakephp_validate_cc('6759871666634807647', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641827883728575248', ARRAY['switch'])= true
		AND cakephp_validate_cc('4936527975051407847', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641823318396882141', ARRAY['switch'])= true
		AND cakephp_validate_cc('6759123772311123708', ARRAY['switch'])= true
		AND cakephp_validate_cc('4903054736148271088', ARRAY['switch'])= true
		AND cakephp_validate_cc('4936477526808883952', ARRAY['switch'])= true
		AND cakephp_validate_cc('4936433964890967966', ARRAY['switch'])= true
		AND cakephp_validate_cc('6333245128906049344', ARRAY['switch'])= true
		AND cakephp_validate_cc('4936321036970553134', ARRAY['switch'])= true
		AND cakephp_validate_cc('4936111816358702773', ARRAY['switch'])= true
		AND cakephp_validate_cc('4936196077254804290', ARRAY['switch'])= true
		AND cakephp_validate_cc('6759558831206830183', ARRAY['switch'])= true
		AND cakephp_validate_cc('5641827998830403137', ARRAY['switch'])= true
		--VISA 13 digit
		AND cakephp_validate_cc('4024007174754', ARRAY['visa'])= true
		AND cakephp_validate_cc('4104816460717', ARRAY['visa'])= true
		AND cakephp_validate_cc('4716229700437', ARRAY['visa'])= true
		AND cakephp_validate_cc('4539305400213', ARRAY['visa'])= true
		AND cakephp_validate_cc('4728260558665', ARRAY['visa'])= true
		AND cakephp_validate_cc('4929100131792', ARRAY['visa'])= true
		AND cakephp_validate_cc('4024007117308', ARRAY['visa'])= true
		AND cakephp_validate_cc('4539915491024', ARRAY['visa'])= true
		AND cakephp_validate_cc('4539790901139', ARRAY['visa'])= true
		AND cakephp_validate_cc('4485284914909', ARRAY['visa'])= true
		AND cakephp_validate_cc('4782793022350', ARRAY['visa'])= true
		AND cakephp_validate_cc('4556899290685', ARRAY['visa'])= true
		AND cakephp_validate_cc('4024007134774', ARRAY['visa'])= true
		AND cakephp_validate_cc('4333412341316', ARRAY['visa'])= true
		AND cakephp_validate_cc('4539534204543', ARRAY['visa'])= true
		AND cakephp_validate_cc('4485640373626', ARRAY['visa'])= true
		AND cakephp_validate_cc('4929911445746', ARRAY['visa'])= true
		AND cakephp_validate_cc('4539292550806', ARRAY['visa'])= true
		AND cakephp_validate_cc('4716523014030', ARRAY['visa'])= true
		AND cakephp_validate_cc('4024007125152', ARRAY['visa'])= true
		AND cakephp_validate_cc('4539758883311', ARRAY['visa'])= true
		AND cakephp_validate_cc('4024007103258', ARRAY['visa'])= true
		AND cakephp_validate_cc('4916933155767', ARRAY['visa'])= true
		AND cakephp_validate_cc('4024007159672', ARRAY['visa'])= true
		AND cakephp_validate_cc('4716935544871', ARRAY['visa'])= true
		AND cakephp_validate_cc('4929415177779', ARRAY['visa'])= true
		AND cakephp_validate_cc('4929748547896', ARRAY['visa'])= true
		AND cakephp_validate_cc('4929153468612', ARRAY['visa'])= true
		AND cakephp_validate_cc('4539397132104', ARRAY['visa'])= true
		AND cakephp_validate_cc('4485293435540', ARRAY['visa'])= true
		AND cakephp_validate_cc('4485799412720', ARRAY['visa'])= true
		AND cakephp_validate_cc('4916744757686', ARRAY['visa'])= true
		AND cakephp_validate_cc('4556475655426', ARRAY['visa'])= true
		AND cakephp_validate_cc('4539400441625', ARRAY['visa'])= true
		AND cakephp_validate_cc('4485437129173', ARRAY['visa'])= true
		AND cakephp_validate_cc('4716253605320', ARRAY['visa'])= true
		AND cakephp_validate_cc('4539366156589', ARRAY['visa'])= true
		AND cakephp_validate_cc('4916498061392', ARRAY['visa'])= true
		AND cakephp_validate_cc('4716127163779', ARRAY['visa'])= true
		AND cakephp_validate_cc('4024007183078', ARRAY['visa'])= true
		AND cakephp_validate_cc('4041553279654', ARRAY['visa'])= true
		AND cakephp_validate_cc('4532380121960', ARRAY['visa'])= true
		AND cakephp_validate_cc('4485906062491', ARRAY['visa'])= true
		AND cakephp_validate_cc('4539365115149', ARRAY['visa'])= true
		AND cakephp_validate_cc('4485146516702', ARRAY['visa'])= true
		--VISA 16 digit
		AND cakephp_validate_cc('4916375389940009', ARRAY['visa'])= true
		AND cakephp_validate_cc('4929167481032610', ARRAY['visa'])= true
		AND cakephp_validate_cc('4485029969061519', ARRAY['visa'])= true
		AND cakephp_validate_cc('4485573845281759', ARRAY['visa'])= true
		AND cakephp_validate_cc('4485669810383529', ARRAY['visa'])= true
		AND cakephp_validate_cc('4929615806560327', ARRAY['visa'])= true
		AND cakephp_validate_cc('4556807505609535', ARRAY['visa'])= true
		AND cakephp_validate_cc('4532611336232890', ARRAY['visa'])= true
		AND cakephp_validate_cc('4532201952422387', ARRAY['visa'])= true
		AND cakephp_validate_cc('4485073797976290', ARRAY['visa'])= true
		AND cakephp_validate_cc('4024007157580969', ARRAY['visa'])= true
		AND cakephp_validate_cc('4053740470212274', ARRAY['visa'])= true
		AND cakephp_validate_cc('4716265831525676', ARRAY['visa'])= true
		AND cakephp_validate_cc('4024007100222966', ARRAY['visa'])= true
		AND cakephp_validate_cc('4539556148303244', ARRAY['visa'])= true
		AND cakephp_validate_cc('4532449879689709', ARRAY['visa'])= true
		AND cakephp_validate_cc('4916805467840986', ARRAY['visa'])= true
		AND cakephp_validate_cc('4532155644440233', ARRAY['visa'])= true
		AND cakephp_validate_cc('4467977802223781', ARRAY['visa'])= true
		AND cakephp_validate_cc('4539224637000686', ARRAY['visa'])= true
		AND cakephp_validate_cc('4556629187064965', ARRAY['visa'])= true
		AND cakephp_validate_cc('4532970205932943', ARRAY['visa'])= true
		AND cakephp_validate_cc('4821470132041850', ARRAY['visa'])= true
		AND cakephp_validate_cc('4916214267894485', ARRAY['visa'])= true
		AND cakephp_validate_cc('4024007169073284', ARRAY['visa'])= true
		AND cakephp_validate_cc('4716783351296122', ARRAY['visa'])= true
		AND cakephp_validate_cc('4556480171913795', ARRAY['visa'])= true
		AND cakephp_validate_cc('4929678411034997', ARRAY['visa'])= true
		AND cakephp_validate_cc('4682061913519392', ARRAY['visa'])= true
		AND cakephp_validate_cc('4916495481746474', ARRAY['visa'])= true
		AND cakephp_validate_cc('4929007108460499', ARRAY['visa'])= true
		AND cakephp_validate_cc('4539951357838586', ARRAY['visa'])= true
		AND cakephp_validate_cc('4716482691051558', ARRAY['visa'])= true
		AND cakephp_validate_cc('4916385069917516', ARRAY['visa'])= true
		AND cakephp_validate_cc('4929020289494641', ARRAY['visa'])= true
		AND cakephp_validate_cc('4532176245263774', ARRAY['visa'])= true
		AND cakephp_validate_cc('4556242273553949', ARRAY['visa'])= true
		AND cakephp_validate_cc('4481007485188614', ARRAY['visa'])= true
		AND cakephp_validate_cc('4716533372139623', ARRAY['visa'])= true
		AND cakephp_validate_cc('4929152038152632', ARRAY['visa'])= true
		AND cakephp_validate_cc('4539404037310550', ARRAY['visa'])= true
		AND cakephp_validate_cc('4532800925229140', ARRAY['visa'])= true
		AND cakephp_validate_cc('4916845885268360', ARRAY['visa'])= true
		AND cakephp_validate_cc('4394514669078434', ARRAY['visa'])= true
		AND cakephp_validate_cc('4485611378115042', ARRAY['visa'])= true
		--Visa Electron
		AND cakephp_validate_cc('4175003346287100', ARRAY['electron'])= true
		AND cakephp_validate_cc('4913042516577228', ARRAY['electron'])= true
		AND cakephp_validate_cc('4917592325659381', ARRAY['electron'])= true
		AND cakephp_validate_cc('4917084924450511', ARRAY['electron'])= true
		AND cakephp_validate_cc('4917994610643999', ARRAY['electron'])= true
		AND cakephp_validate_cc('4175005933743585', ARRAY['electron'])= true
		AND cakephp_validate_cc('4175008373425044', ARRAY['electron'])= true
		AND cakephp_validate_cc('4913119763664154', ARRAY['electron'])= true
		AND cakephp_validate_cc('4913189017481812', ARRAY['electron'])= true
		AND cakephp_validate_cc('4913085104968622', ARRAY['electron'])= true
		AND cakephp_validate_cc('4175008803122021', ARRAY['electron'])= true
		AND cakephp_validate_cc('4913294453962489', ARRAY['electron'])= true
		AND cakephp_validate_cc('4175009797419290', ARRAY['electron'])= true
		AND cakephp_validate_cc('4175005028142917', ARRAY['electron'])= true
		AND cakephp_validate_cc('4913940802385364', ARRAY['electron'])= true
		--Voyager
		AND cakephp_validate_cc('869940697287073', ARRAY['voyager'])= true
		AND cakephp_validate_cc('869934523596112', ARRAY['voyager'])= true
		AND cakephp_validate_cc('869958670174621', ARRAY['voyager'])= true
		AND cakephp_validate_cc('869921250068209', ARRAY['voyager'])= true
		AND cakephp_validate_cc('869972521242198', ARRAY['voyager'])= true
	AS testCc;

	SELECT
		--American Express
		cakephp_validate_luhn('370482756063980', true)= true
		--BankCard
		AND cakephp_validate_luhn('5610745867413420', true)= true
		--Diners Club 14
		AND cakephp_validate_luhn('30155483651028', true)= true
		--2004 MasterCard/Diners Club Alliance International 14
		AND cakephp_validate_luhn('36747701998969', true)= true
		--2004 MasterCard/Diners Club Alliance US & Canada 16
		AND cakephp_validate_luhn('5597511346169950', true)= true
		--Discover
		AND cakephp_validate_luhn('6011802876467237', true)= true
		--enRoute
		AND cakephp_validate_luhn('201496944158937', true)= true
		--JCB 15 digit
		AND cakephp_validate_luhn('210034762247893', true)= true
		--JCB 16 digit
		AND cakephp_validate_luhn('3096806857839939', true)= true
		--Maestro (debit card)
		AND cakephp_validate_luhn('5020147409985219', true)= true
		--Mastercard
		AND cakephp_validate_luhn('5580424361774366', true)= true
		--Solo 16
		AND cakephp_validate_luhn('6767432107064987', true)= true
		--Solo 18
		AND cakephp_validate_luhn('676714834398858593', true)= true
		--Solo 19
		AND cakephp_validate_luhn('6767838565218340113', true)= true
		--Switch 16
		AND cakephp_validate_luhn('5641829171515733', true)= true
		--Switch 18
		AND cakephp_validate_luhn('493622764224625174', true)= true
		--Switch 19
		AND cakephp_validate_luhn('6759603460617628716', true)= true
		--VISA 13 digit
		AND cakephp_validate_luhn('4024007174754', true)= true
		--VISA 16 digit
		AND cakephp_validate_luhn('4916375389940009', true)= true
		--Visa Electron
		AND cakephp_validate_luhn('4175003346287100', true)= true
		--Voyager
		AND cakephp_validate_luhn('869940697287073', true)= true

		AND cakephp_validate_luhn('0000000000000000', true)= false

		AND cakephp_validate_luhn('869940697287173', true)= false
	AS testLuhn;

	SELECT
		-- FIXME
-- 		cakephp_validate_cc('12332105933743585', null, null, '123321\\d{11}')= true
		/*AND */cakephp_validate_cc('1233210593374358', null, null, '/123321\\d{11}/')= false
		AND cakephp_validate_cc('12312305933743585', null, null, '/123321\\d{11}/')= false
	AS testCustomRegexForCc;

	SELECT
		-- FIXME
-- 		cakephp_validate_cc('12332110426226941', null, true, '/123321\\d{11}/')= true
		/*AND */cakephp_validate_cc('12332105933743585', null, true, '/123321\\d{11}/')= false
		AND cakephp_validate_cc('12332105933743587', null, true, '/123321\\d{11}/')= false
		AND cakephp_validate_cc('12312305933743585', null, true, '/123321\\d{11}/')= false
	AS testCustomRegexForCcWithLuhnCheck;

	SELECT
		-- too short
		cakephp_validate_cc('123456789012')= false
		--American Express
		AND cakephp_validate_cc('370482756063980')= true
		--Diners Club 14
		AND cakephp_validate_cc('30155483651028')= true
		--2004 MasterCard/Diners Club Alliance International 14
		AND cakephp_validate_cc('36747701998969')= true
		--2004 MasterCard/Diners Club Alliance US & Canada 16
		AND cakephp_validate_cc('5597511346169950')= true
		--Discover
		AND cakephp_validate_cc('6011802876467237')= true
		--Mastercard
		AND cakephp_validate_cc('5580424361774366')= true
		--VISA 13 digit
		AND cakephp_validate_cc('4024007174754')= true
		--VISA 16 digit
		AND cakephp_validate_cc('4916375389940009')= true
		--Visa Electron
		AND cakephp_validate_cc('4175003346287100')= true
	AS testFastCc;

	SELECT
		--American Express
		cakephp_validate_cc('370482756063980', 'all')= true
		--BankCard
		AND cakephp_validate_cc('5610745867413420', 'all')= true
		--Diners Club 14
		AND cakephp_validate_cc('30155483651028', 'all')= true
		--2004 MasterCard/Diners Club Alliance International 14
		AND cakephp_validate_cc('36747701998969', 'all')= true
		--2004 MasterCard/Diners Club Alliance US & Canada 16
		AND cakephp_validate_cc('5597511346169950', 'all')= true
		--Discover
		AND cakephp_validate_cc('6011802876467237', 'all')= true
		--enRoute
		AND cakephp_validate_cc('201496944158937', 'all')= true
		--JCB 15 digit
		AND cakephp_validate_cc('210034762247893', 'all')= true
		--JCB 16 digit
		AND cakephp_validate_cc('3096806857839939', 'all')= true
		--Maestro (debit card)
		AND cakephp_validate_cc('5020147409985219', 'all')= true
		--Mastercard
		AND cakephp_validate_cc('5580424361774366', 'all')= true
		--Solo 16
		AND cakephp_validate_cc('6767432107064987', 'all')= true
		--Solo 18
		AND cakephp_validate_cc('676714834398858593', 'all')= true
		--Solo 19
		AND cakephp_validate_cc('6767838565218340113', 'all')= true
		--Switch 16
		AND cakephp_validate_cc('5641829171515733', 'all')= true
		--Switch 18
		AND cakephp_validate_cc('493622764224625174', 'all')= true
		--Switch 19
		AND cakephp_validate_cc('6759603460617628716', 'all')= true
		--VISA 13 digit
		AND cakephp_validate_cc('4024007174754', 'all')= true
		--VISA 16 digit
		AND cakephp_validate_cc('4916375389940009', 'all')= true
		--Visa Electron
		AND cakephp_validate_cc('4175003346287100', 'all')= true
		--Voyager
		AND cakephp_validate_cc('869940697287073', 'all')= true
	AS testAllCc;

	SELECT
		--American Express
		cakephp_validate_cc('370482756063980', 'all', true)= true
		--BankCard
		AND cakephp_validate_cc('5610745867413420', 'all', true)= true
		--Diners Club 14
		AND cakephp_validate_cc('30155483651028', 'all', true)= true
		--2004 MasterCard/Diners Club Alliance International 14
		AND cakephp_validate_cc('36747701998969', 'all', true)= true
		--2004 MasterCard/Diners Club Alliance US & Canada 16
		AND cakephp_validate_cc('5597511346169950', 'all', true)= true
		--Discover
		AND cakephp_validate_cc('6011802876467237', 'all', true)= true
		--enRoute
		AND cakephp_validate_cc('201496944158937', 'all', true)= true
		--JCB 15 digit
		AND cakephp_validate_cc('210034762247893', 'all', true)= true
		--JCB 16 digit
		AND cakephp_validate_cc('3096806857839939', 'all', true)= true
		--Maestro (debit card)
		AND cakephp_validate_cc('5020147409985219', 'all', true)= true
		--Mastercard
		AND cakephp_validate_cc('5580424361774366', 'all', true)= true
		--Solo 16
		AND cakephp_validate_cc('6767432107064987', 'all', true)= true
		--Solo 18
		AND cakephp_validate_cc('676714834398858593', 'all', true)= true
		--Solo 19
		AND cakephp_validate_cc('6767838565218340113', 'all', true)= true
		--Switch 16
		AND cakephp_validate_cc('5641829171515733', 'all', true)= true
		--Switch 18
		AND cakephp_validate_cc('493622764224625174', 'all', true)= true
		--Switch 19
		AND cakephp_validate_cc('6759603460617628716', 'all', true)= true
		--VISA 13 digit
		AND cakephp_validate_cc('4024007174754', 'all', true)= true
		--VISA 16 digit
		AND cakephp_validate_cc('4916375389940009', 'all', true)= true
		--Visa Electron
		AND cakephp_validate_cc('4175003346287100', 'all', true)= true
		--Voyager
		AND cakephp_validate_cc('869940697287073', 'all', true)= true
	AS testAllCcDeep;

	SELECT
		cakephp_validate_comparison(7, null, 6)= false
		AND cakephp_validate_comparison(7, 'is greater', 6)= true
		AND cakephp_validate_comparison(7, '>', 6)= true
		AND cakephp_validate_comparison(6, 'is less', 7)= true
		AND cakephp_validate_comparison(6, '<', 7)= true
		AND cakephp_validate_comparison(7, 'greater or equal', 7)= true
		AND cakephp_validate_comparison(7, '>=', 7)= true
		AND cakephp_validate_comparison(7, 'greater or equal', 6)= true
		AND cakephp_validate_comparison(7, '>=', 6)= true
		AND cakephp_validate_comparison(6, 'less or equal', 7)= true
		AND cakephp_validate_comparison(6, '<=', 7)= true
		AND cakephp_validate_comparison(7, 'equal to', 7)= true
		AND cakephp_validate_comparison(7, '==', 7)= true
		AND cakephp_validate_comparison(7, 'not equal', 6)= true
		AND cakephp_validate_comparison(7, '!=', 6)= true
		AND cakephp_validate_comparison(6, 'is greater', 7)= false
		AND cakephp_validate_comparison(6, '>', 7)= false
		AND cakephp_validate_comparison(7, 'is less', 6)= false
		AND cakephp_validate_comparison(7, '<', 6)= false
		AND cakephp_validate_comparison(6, 'greater or equal', 7)= false
		AND cakephp_validate_comparison(6, '>=', 7)= false
		AND cakephp_validate_comparison(6, 'greater or equal', 7)= false
		AND cakephp_validate_comparison(6, '>=', 7)= false
		AND cakephp_validate_comparison(7, 'less or equal', 6)= false
		AND cakephp_validate_comparison(7, '<=', 6)= false
		AND cakephp_validate_comparison(7, 'equal to', 6)= false
		AND cakephp_validate_comparison(7, '==', 6)= false
		AND cakephp_validate_comparison(7, 'not equal', 7)= false
		AND cakephp_validate_comparison(7, '!=', 7)= false
	AS testComparison;
/*
	-- TODO
	public function testCustom() {
		cakephp_validate_custom('12345', '/(?<!\\S)\\d++(?!\\S)/')= true
		AND cakephp_validate_custom('Text', '/(?<!\\S)\\d++(?!\\S)/')= false
		AND cakephp_validate_custom('123.45', '/(?<!\\S)\\d++(?!\\S)/')= false
		AND cakephp_validate_custom('missing regex')= false
	}
*/

/*
	-- FIXME
	SELECT
		cakephp_validate_decimal('+1234.54321', null)= true
		AND cakephp_validate_decimal('-1234.54321', null)= true
		AND cakephp_validate_decimal('1234.54321', null)= true
		AND cakephp_validate_decimal('+0123.45e6', null)= true
		AND cakephp_validate_decimal('-0123.45e6', null)= true
		AND cakephp_validate_decimal('0123.45e6', null)= true
		AND cakephp_validate_decimal(1234.56, null)= true
		AND cakephp_validate_decimal(1234.00, null)= true
		AND cakephp_validate_decimal(1234., null)= true
		AND cakephp_validate_decimal('1234.00', null)= true
		AND cakephp_validate_decimal(.0, null)= true
		AND cakephp_validate_decimal(.00, null)= true
		AND cakephp_validate_decimal('.00', null)= true
		AND cakephp_validate_decimal(.01, null)= true
		AND cakephp_validate_decimal('.01', null)= true
		AND cakephp_validate_decimal('1234', null)= true
		AND cakephp_validate_decimal('-1234', null)= true
		AND cakephp_validate_decimal('+1234', null)= true
		AND cakephp_validate_decimal((float)1234, null)= true
		AND cakephp_validate_decimal((double)1234, null)= true
		AND cakephp_validate_decimal((int)1234, null)= true

		AND cakephp_validate_decimal('', null)= false
		AND cakephp_validate_decimal('string', null)= false
		AND cakephp_validate_decimal('1234.', null)= false
	AS testDecimalWithPlacesNull;
*/

/*
	public function testDecimalWithPlacesTrue() {
		AND cakephp_validate_decimal('+1234.54321', true)= true
		AND cakephp_validate_decimal('-1234.54321', true)= true
		AND cakephp_validate_decimal('1234.54321', true)= true
		AND cakephp_validate_decimal('+0123.45e6', true)= true
		AND cakephp_validate_decimal('-0123.45e6', true)= true
		AND cakephp_validate_decimal('0123.45e6', true)= true
		AND cakephp_validate_decimal(1234.56, true)= true
		AND cakephp_validate_decimal(1234.00, true)= true
		AND cakephp_validate_decimal(1234., true)= true
		AND cakephp_validate_decimal('1234.00', true)= true
		AND cakephp_validate_decimal(.0, true)= true
		AND cakephp_validate_decimal(.00, true)= true
		AND cakephp_validate_decimal('.00', true)= true
		AND cakephp_validate_decimal(.01, true)= true
		AND cakephp_validate_decimal('.01', true)= true
		AND cakephp_validate_decimal((float)1234, true)= true
		AND cakephp_validate_decimal((double)1234, true)= true

		AND cakephp_validate_decimal('', true)= false
		AND cakephp_validate_decimal('string', true)= false
		AND cakephp_validate_decimal('1234.', true)= false
		AND cakephp_validate_decimal((int)1234, true)= false
		AND cakephp_validate_decimal('1234', true)= false
		AND cakephp_validate_decimal('-1234', true)= false
		AND cakephp_validate_decimal('+1234', true)= false
	}






	public function testDecimalWithPlacesNumeric() {
		AND cakephp_validate_decimal('.27', '2')= true
		AND cakephp_validate_decimal(0.27, 2)= true
		AND cakephp_validate_decimal(-0.27, 2)= true
		AND cakephp_validate_decimal(0.27, 2)= true
		AND cakephp_validate_decimal('0.277', '3')= true
		AND cakephp_validate_decimal(0.277, 3)= true
		AND cakephp_validate_decimal(-0.277, 3)= true
		AND cakephp_validate_decimal(0.277, 3)= true
		AND cakephp_validate_decimal('1234.5678', '4')= true
		AND cakephp_validate_decimal(1234.5678, 4)= true
		AND cakephp_validate_decimal(-1234.5678, 4)= true
		AND cakephp_validate_decimal(1234.5678, 4)= true
		AND cakephp_validate_decimal('.00', 2)= true
		AND cakephp_validate_decimal(.01, 2)= true
		AND cakephp_validate_decimal('.01', 2)= true

		AND cakephp_validate_decimal('', 1)= false
		AND cakephp_validate_decimal('string', 1)= false
		AND cakephp_validate_decimal(1234., 1)= false
		AND cakephp_validate_decimal('1234.', 1)= false
		AND cakephp_validate_decimal(.0, 1)= false
		AND cakephp_validate_decimal(.00, 2)= false
		AND cakephp_validate_decimal((float)1234, 1)= false
		AND cakephp_validate_decimal((double)1234, 1)= false
		AND cakephp_validate_decimal((int)1234, 1)= false
		AND cakephp_validate_decimal('1234.5678', '3')= false
		AND cakephp_validate_decimal(1234.5678, 3)= false
		AND cakephp_validate_decimal(-1234.5678, 3)= false
		AND cakephp_validate_decimal(1234.5678, 3)= false
	}






	public function testDecimalWithInvalidPlaces() {
		AND cakephp_validate_decimal('.27', 'string')= false
		AND cakephp_validate_decimal(1234.5678, (array)true)= false
		AND cakephp_validate_decimal(-1234.5678, (object)true)= false
	}






	public function testDecimalCustomRegex() {
		AND cakephp_validate_decimal('1.54321', null, '/^[-+]?[0-9]+(\\.[0-9]+)?$/s')= true
		AND cakephp_validate_decimal('.54321', null, '/^[-+]?[0-9]+(\\.[0-9]+)?$/s')= false
	}
*/





	SELECT
		cakephp_validate_email('abc.efg@domain.com')= true
		AND cakephp_validate_email('efg@domain.com')= true
		AND cakephp_validate_email('abc-efg@domain.com')= true
		AND cakephp_validate_email('abc_efg@domain.com')= true
		AND cakephp_validate_email('raw@test.ra.ru')= true
		AND cakephp_validate_email('abc-efg@domain-hyphened.com')= true
		AND cakephp_validate_email('p.o''malley@domain.com')= true
		AND cakephp_validate_email('abc+efg@domain.com')= true
		AND cakephp_validate_email('abc&efg@domain.com')= true
		AND cakephp_validate_email('abc.efg@12345.com')= true
		AND cakephp_validate_email('abc.efg@12345.co.jp')= true
		AND cakephp_validate_email('abc@g.cn')= true
		AND cakephp_validate_email('abc@x.com')= true
		AND cakephp_validate_email('henrik@sbcglobal.net')= true
		AND cakephp_validate_email('sani@sbcglobal.net')= true
		-- all ICANN TLDs
		AND cakephp_validate_email('abc@example.aero')= true
		AND cakephp_validate_email('abc@example.asia')= true
		AND cakephp_validate_email('abc@example.biz')= true
		AND cakephp_validate_email('abc@example.cat')= true
		AND cakephp_validate_email('abc@example.com')= true
		AND cakephp_validate_email('abc@example.coop')= true
		AND cakephp_validate_email('abc@example.edu')= true
		AND cakephp_validate_email('abc@example.gov')= true
		AND cakephp_validate_email('abc@example.info')= true
		AND cakephp_validate_email('abc@example.int')= true
		AND cakephp_validate_email('abc@example.jobs')= true
		AND cakephp_validate_email('abc@example.mil')= true
		AND cakephp_validate_email('abc@example.mobi')= true
		AND cakephp_validate_email('abc@example.museum')= true
		AND cakephp_validate_email('abc@example.name')= true
		AND cakephp_validate_email('abc@example.net')= true
		AND cakephp_validate_email('abc@example.org')= true
		AND cakephp_validate_email('abc@example.pro')= true
		AND cakephp_validate_email('abc@example.tel')= true
		AND cakephp_validate_email('abc@example.travel')= true
		AND cakephp_validate_email('someone@st.t-com.hr')= true
		-- gTLD's
		AND cakephp_validate_email('example@host.local')= true
		AND cakephp_validate_email('example@x.org')= true
		AND cakephp_validate_email('example@host.xxx')= true
		-- strange, but technically valid email addresses
		AND cakephp_validate_email('S=postmaster/OU=rz/P=uni-frankfurt/A=d400/C=de@gateway.d400.de')= true
		AND cakephp_validate_email('customer/department=shipping@example.com')= true
		AND cakephp_validate_email('$A12345@example.com')= true
		AND cakephp_validate_email('!def!xyz%abc@example.com')= true
		AND cakephp_validate_email('_somename@example.com')= true
		-- invalid addresses
		AND cakephp_validate_email('abc@example')= false
		AND cakephp_validate_email('abc@example.c')= false
		AND cakephp_validate_email('abc@example.com.')= false
		AND cakephp_validate_email('abc.@example.com')= false
		AND cakephp_validate_email('abc@example..com')= false
		AND cakephp_validate_email('abc@example.com.a')= false
		AND cakephp_validate_email('abc;@example.com')= false
		AND cakephp_validate_email('abc@example.com;')= false
		AND cakephp_validate_email('abc@efg@example.com')= false
		AND cakephp_validate_email('abc@@example.com')= false
		AND cakephp_validate_email('abc efg@example.com')= false
		AND cakephp_validate_email('abc,efg@example.com')= false
		AND cakephp_validate_email('abc@sub,example.com')= false
		AND cakephp_validate_email('abc@sub''example.com')= false
		AND cakephp_validate_email('abc@sub/example.com')= false
		AND cakephp_validate_email('abc@yahoo!.com')= false
		AND cakephp_validate_email('Nyrée.surname@example.com')= false
		AND cakephp_validate_email('abc@example_underscored.com')= false
		AND cakephp_validate_email('raw@test.ra.ru....com')= false
	AS testEmail;

	SELECT
		cakephp_validate_email('abc.efg@cakephp.org', null, '^[a-z0-9._%-]+@[a-z0-9.-]+\\.[a-z]{2,4}$')= true
		AND cakephp_validate_email('abc.efg@com.caphpkeinvalid', null, '^[a-z0-9._%-]+@[a-z0-9.-]+\\.[a-z]{2,4}$')= false
	AS testEmailCustomRegex;





/*
	-- TODO
	SELECT
		cakephp_validate_equal_to('1', '1')= true
		AND cakephp_validate_equal_to(1, '1')= false
		AND cakephp_validate_equal_to('', null)= false
		AND cakephp_validate_equal_to('', false)= false
		AND cakephp_validate_equal_to(0, false)= false
		AND cakephp_validate_equal_to(null, false)= false
	AS testEqualTo;
*/

	SELECT
		cakephp_validate_ip('0.0.0.0', 'ipv4')= true
		AND cakephp_validate_ip('192.168.1.156')= true
		AND cakephp_validate_ip('255.255.255.255')= true
		AND cakephp_validate_ip('127.0.0')= false
		AND cakephp_validate_ip('127.0.0.a')= false
		AND cakephp_validate_ip('127.0.0.256')= false
		AND cakephp_validate_ip('2001:0db8:85a3:0000:0000:8a2e:0370:7334', 'ipv4')= false
	AS testIpV4;

	SELECT
		cakephp_validate_ip('2001:0db8:85a3:0000:0000:8a2e:0370:7334', 'IPv6')= true
		AND cakephp_validate_ip('2001:db8:85a3:0:0:8a2e:370:7334', 'IPv6')= true
		AND cakephp_validate_ip('2001:db8:85a3::8a2e:370:7334', 'IPv6')= true
		AND cakephp_validate_ip('2001:0db8:0000:0000:0000:0000:1428:57ab', 'IPv6')= true
		AND cakephp_validate_ip('2001:0db8:0000:0000:0000::1428:57ab', 'IPv6')= true
		AND cakephp_validate_ip('2001:0db8:0:0:0:0:1428:57ab', 'IPv6')= true
		AND cakephp_validate_ip('2001:0db8:0:0::1428:57ab', 'IPv6')= true
		AND cakephp_validate_ip('2001:0db8::1428:57ab', 'IPv6')= true
		AND cakephp_validate_ip('2001:db8::1428:57ab', 'IPv6')= true
		AND cakephp_validate_ip('0000:0000:0000:0000:0000:0000:0000:0001', 'IPv6')= true
		AND cakephp_validate_ip('::1', 'IPv6')= true
		AND cakephp_validate_ip('::ffff:12.34.56.78', 'IPv6')= true
		AND cakephp_validate_ip('::ffff:0c22:384e', 'IPv6')= true
		AND cakephp_validate_ip('2001:0db8:1234:0000:0000:0000:0000:0000', 'IPv6')= true
		AND cakephp_validate_ip('2001:0db8:1234:ffff:ffff:ffff:ffff:ffff', 'IPv6')= true
		AND cakephp_validate_ip('2001:db8:a::123', 'IPv6')= true
		AND cakephp_validate_ip('fe80::', 'IPv6')= true
		AND cakephp_validate_ip('::ffff:192.0.2.128', 'IPv6')= true
		AND cakephp_validate_ip('::ffff:c000:280', 'IPv6')= true
		AND cakephp_validate_ip('123', 'IPv6')= false
		AND cakephp_validate_ip('ldkfj', 'IPv6')= false
		AND cakephp_validate_ip('2001::FFD3::57ab', 'IPv6')= false
		AND cakephp_validate_ip('2001:db8:85a3::8a2e:37023:7334', 'IPv6')= false
		AND cakephp_validate_ip('2001:db8:85a3::8a2e:370k:7334', 'IPv6')= false
		AND cakephp_validate_ip('1:2:3:4:5:6:7:8:9', 'IPv6')= false
		AND cakephp_validate_ip('1::2::3', 'IPv6')= false
		AND cakephp_validate_ip('1:::3:4:5', 'IPv6')= false
		AND cakephp_validate_ip('1:2:3::4:5:6:7:8:9', 'IPv6')= false
		AND cakephp_validate_ip('::ffff:2.3.4', 'IPv6')= false
		AND cakephp_validate_ip('::ffff:257.1.2.3', 'IPv6')= false
		-- FIXME
-- 		AND cakephp_validate_ip('255.255.255.255', 'ipv6')= false
	AS testIpv6;


	SELECT
		cakephp_validate_max_length('ab', 3)= true
		AND cakephp_validate_max_length('abc', 3)= true
		AND cakephp_validate_max_length('ÆΔΩЖÇ', 10)= true
		AND cakephp_validate_max_length('abcd', 3)= false
		AND cakephp_validate_max_length('ÆΔΩЖÇ', 3)= false
	AS testMaxLength;

	SELECT
		cakephp_validate_min_length('ab', 3)= false
		AND cakephp_validate_min_length('ÆΔΩЖÇ', 10)= false
		AND cakephp_validate_min_length('abc', 3)= true
		AND cakephp_validate_min_length('abcd', 3)= true
		AND cakephp_validate_min_length('ÆΔΩЖÇ', 2)= true
	AS testMinLength;

/*
	-- TODO
	SELECT
		cakephp_validate_url('http://www.cakephp.org')= true
		AND cakephp_validate_url('http://cakephp.org')= true
		AND cakephp_validate_url('http://www.cakephp.org/somewhere#anchor')= true
		AND cakephp_validate_url('http://192.168.0.1')= true
		AND cakephp_validate_url('https://www.cakephp.org')= true
		AND cakephp_validate_url('https://cakephp.org')= true
		AND cakephp_validate_url('https://www.cakephp.org/somewhere#anchor')= true
		AND cakephp_validate_url('https://192.168.0.1')= true
		AND cakephp_validate_url('ftps://www.cakephp.org/pub/cake')= true
		AND cakephp_validate_url('ftps://cakephp.org/pub/cake')= true
		AND cakephp_validate_url('ftps://192.168.0.1/pub/cake')= true
		AND cakephp_validate_url('ftp://www.cakephp.org/pub/cake')= true
		AND cakephp_validate_url('ftp://cakephp.org/pub/cake')= true
		AND cakephp_validate_url('ftp://192.168.0.1/pub/cake')= true
		AND cakephp_validate_url('sftp://192.168.0.1/pub/cake')= true
		AND cakephp_validate_url('https://my.domain.com/gizmo/app?class=MySip;proc=start')= true
		AND cakephp_validate_url('www.domain.tld')= true
		AND cakephp_validate_url('http://123456789112345678921234567893123456789412345678951234567896123.com')= true
		AND cakephp_validate_url('http://www.domain.com/blogs/index.php?blog=6&tempskin=_rss2')= true
		AND cakephp_validate_url('http://www.domain.com/blogs/parenth()eses.php')= true
		AND cakephp_validate_url('http://www.domain.com/index.php?get=params&amp;get2=params')= true
		AND cakephp_validate_url('http://www.domain.com/ndex.php?get=params&amp;get2=params#anchor')= true
		AND cakephp_validate_url('http://www.domain.com/real%20url%20encodeing')= true
		AND cakephp_validate_url('http://en.wikipedia.org/wiki/Architectural_pattern_(computer_science)')= true
		AND cakephp_validate_url('http://www.cakephp.org', true)= true
		AND cakephp_validate_url('http://example.com/~userdir/')= true
		AND cakephp_validate_url('http://underscore_subdomain.example.org')= true
		AND cakephp_validate_url('http://_jabber._tcp.gmail.com')= true
		AND cakephp_validate_url('http://www.domain.longttldnotallowed')= true
		AND cakephp_validate_url('ftps://256.168.0.1/pub/cake')= false
		AND cakephp_validate_url('ftp://256.168.0.1/pub/cake')= false
		AND cakephp_validate_url('http://w_w.domain.co_m')= false
		AND cakephp_validate_url('http://www.domain.12com')= false
		AND cakephp_validate_url('http://www.-invaliddomain.tld')= false
		AND cakephp_validate_url('http://www.domain.-invalidtld')= false
		AND cakephp_validate_url('http://this-domain-is-too-loooooong-by-icann-rules-maximum-length-is-63.com')= false
		AND cakephp_validate_url('http://www.underscore_domain.org')= false
		AND cakephp_validate_url('http://_jabber._tcp.g_mail.com')= false
		AND cakephp_validate_url('http://en.(wikipedia).org/')= false
		AND cakephp_validate_url('http://www.domain.com/fakeenco%ode')= false
		AND cakephp_validate_url('www.cakephp.org', true)= false

		AND cakephp_validate_url('http://example.com/~userdir/subdir/index.html')= true
		AND cakephp_validate_url('http://www.zwischenraume.de')= true
		AND cakephp_validate_url('http://www.zwischenraume.cz')= true
		AND cakephp_validate_url('http://www.last.fm/music/浜崎あゆみ')= true
		AND cakephp_validate_url('http://www.electrohome.ro/images/239537750-284232-215_300[1].jpg')= true

		AND cakephp_validate_url('http://cakephp.org:80')= true
		AND cakephp_validate_url('http://cakephp.org:443')= true
		AND cakephp_validate_url('http://cakephp.org:2000')= true
		AND cakephp_validate_url('http://cakephp.org:27000')= true
		AND cakephp_validate_url('http://cakephp.org:65000')= true

		AND cakephp_validate_url('[2001:0db8::1428:57ab]')= true
		AND cakephp_validate_url('[::1]')= true
		AND cakephp_validate_url('[2001:0db8::1428:57ab]:80')= true
		AND cakephp_validate_url('[::1]:80')= true
		AND cakephp_validate_url('http://[2001:0db8::1428:57ab]')= true
		AND cakephp_validate_url('http://[::1]')= true
		AND cakephp_validate_url('http://[2001:0db8::1428:57ab]:80')= true
		AND cakephp_validate_url('http://[::1]:80')= true

		AND cakephp_validate_url('[1::2::3]')= false
	AS testUrl;
*/

	SELECT
		cakephp_validate_uuid('550e8400-e29b-11d4-a716-446655440000')= true
		AND cakephp_validate_uuid('BRAP-e29b-11d4-a716-446655440000')= false
		AND cakephp_validate_uuid('550E8400-e29b-11D4-A716-446655440000')= true
		AND cakephp_validate_uuid('550e8400-e29b11d4-a716-446655440000')= false
		AND cakephp_validate_uuid('550e8400-e29b-11d4-a716-4466440000')= false
		AND cakephp_validate_uuid('550e8400-e29b-11d4-a71-446655440000')= false
		AND cakephp_validate_uuid('550e8400-e29b-11d-a716-446655440000')= false
		AND cakephp_validate_uuid('550e8400-e29-11d4-a716-446655440000')= false
	AS testUuid;

	SELECT
		cakephp_validate_in_list('one', ARRAY['one', 'two'])= true
		AND cakephp_validate_in_list('two', ARRAY['one', 'two'])= true
		AND cakephp_validate_in_list('three', ARRAY['one', 'two'])= false
		AND cakephp_validate_in_list('1one', ARRAY['0', '1', '2', '3'])= false
		AND cakephp_validate_in_list('one', ARRAY['0', '1', '2', '3'])= false
-- 		AND cakephp_validate_in_list(2, ARRAY[1, 2, 3])= false
-- 		AND cakephp_validate_in_list('2', ARRAY[1, 2, 3], false)= true
	AS testInList;

	SELECT
		cakephp_validate_range(20, 100, 1)= false
		AND cakephp_validate_range(20, 1, 100)= true
		AND cakephp_validate_range(.5, 1, 100)= false
		AND cakephp_validate_range(.5, 0, 100)= true
-- 		AND cakephp_validate_range(5)= true
		AND cakephp_validate_range(-5, -10, 1)= true
-- 		AND cakephp_validate_range('word')= false
	AS testRange;




/*
	public function testExtension() {
		AND cakephp_validate_extension('extension.jpeg')= true
		AND cakephp_validate_extension('extension.JPEG')= true
		AND cakephp_validate_extension('extension.gif')= true
		AND cakephp_validate_extension('extension.GIF')= true
		AND cakephp_validate_extension('extension.png')= true
		AND cakephp_validate_extension('extension.jpg')= true
		AND cakephp_validate_extension('extension.JPG')= true
		AND cakephp_validate_extension('noextension')= false
		AND cakephp_validate_extension('extension.pdf', array('PDF'))= true
		AND cakephp_validate_extension('extension.jpg', array('GIF'))= false
		AND cakephp_validate_extension(array('extension.JPG', 'extension.gif', 'extension.png'))= true
		AND cakephp_validate_extension(array('file' => array('name' => 'file.jpg')))= true
		$this->assertTrue(Validation::extension(array('file1' => array('name' => 'file.jpg'),
												'file2' => array('name' => 'file.jpg'),
												'file3' => array('name' => 'file.jpg'))));
		$this->assertFalse(Validation::extension(array('file1' => array('name' => 'file.jpg'),
												'file2' => array('name' => 'file.jpg'),
												'file3' => array('name' => 'file.jpg')), array('gif')));

		AND cakephp_validate_extension(array('noextension', 'extension.JPG', 'extension.gif', 'extension.png'))= false
		AND cakephp_validate_extension(array('extension.pdf', 'extension.JPG', 'extension.gif', 'extension.png'))= false
	}






	public function testMoney() {
		AND cakephp_validate_money('$100')= true
		AND cakephp_validate_money('$100.11')= true
		AND cakephp_validate_money('$100.112')= true
		AND cakephp_validate_money('$100.1')= false
		AND cakephp_validate_money('$100.1111')= false
		AND cakephp_validate_money('text')= false

		AND cakephp_validate_money('100', 'right')= true
		AND cakephp_validate_money('100.11$', 'right')= true
		AND cakephp_validate_money('100.112$', 'right')= true
		AND cakephp_validate_money('100.1$', 'right')= false
		AND cakephp_validate_money('100.1111$', 'right')= false

		AND cakephp_validate_money('€100')= true
		AND cakephp_validate_money('€100.11')= true
		AND cakephp_validate_money('€100.112')= true
		AND cakephp_validate_money('€100.1')= false
		AND cakephp_validate_money('€100.1111')= false

		AND cakephp_validate_money('100', 'right')= true
		AND cakephp_validate_money('100.11€', 'right')= true
		AND cakephp_validate_money('100.112€', 'right')= true
		AND cakephp_validate_money('100.1€', 'right')= false
		AND cakephp_validate_money('100.1111€', 'right')= false
	}






	public function testMultiple() {
		AND cakephp_validate_multiple(array(0, 1, 2, 3))= true
		AND cakephp_validate_multiple(array(50, 32, 22, 0))= true
		AND cakephp_validate_multiple(array('str', 'var', 'enum', 0))= true
		AND cakephp_validate_multiple('')= false
		AND cakephp_validate_multiple(null)= false
		AND cakephp_validate_multiple(array())= false
		AND cakephp_validate_multiple(array(0))= false
		AND cakephp_validate_multiple(array('0'))= false

		AND cakephp_validate_multiple(array(0, 3, 4, 5), array('in' => range(0, 10)))= true
		AND cakephp_validate_multiple(array(0, 15, 20, 5), array('in' => range(0, 10)))= false
		AND cakephp_validate_multiple(array(0, 5, 10, 11), array('in' => range(0, 10)))= false
		AND cakephp_validate_multiple(array('boo', 'foo', 'bar'), array('in' => array('foo', 'bar', 'baz')))= false
		AND cakephp_validate_multiple(array('foo', '1bar'), array('in' => range(0, 10)))= false

		AND cakephp_validate_multiple(array(0, 5, 10, 11), array('max' => 3))= true
		AND cakephp_validate_multiple(array(0, 5, 10, 11, 55), array('max' => 3))= false
		AND cakephp_validate_multiple(array('foo', 'bar', 'baz'), array('max' => 3))= true
		AND cakephp_validate_multiple(array('foo', 'bar', 'baz', 'squirrel'), array('max' => 3))= false

		AND cakephp_validate_multiple(array(0, 5, 10, 11), array('min' => 3))= true
		AND cakephp_validate_multiple(array(0, 5, 10, 11, 55), array('min' => 3))= true
		AND cakephp_validate_multiple(array('foo', 'bar', 'baz'), array('min' => 5))= false
		AND cakephp_validate_multiple(array('foo', 'bar', 'baz', 'squirrel'), array('min' => 10))= false

		AND cakephp_validate_multiple(array(0, 5, 9), array('in' => range(0, 10), 'max' => 5))= true
		AND cakephp_validate_multiple(array('0', '5', '9'), array('in' => range(0, 10), 'max' => 5))= false
		AND cakephp_validate_multiple(array('0', '5', '9'), array('in' => range(0, 10), 'max' => 5), false)= true
		AND cakephp_validate_multiple(array(0, 5, 9, 8, 6, 2, 1), array('in' => range(0, 10), 'max' => 5))= false
		AND cakephp_validate_multiple(array(0, 5, 9, 8, 11), array('in' => range(0, 10), 'max' => 5))= false

		AND cakephp_validate_multiple(array(0, 5, 9), array('in' => range(0, 10), 'max' => 5, 'min' => 3))= false
		AND cakephp_validate_multiple(array(0, 5, 9, 8, 6, 2, 1), array('in' => range(0, 10), 'max' => 5, 'min' => 2))= false
		AND cakephp_validate_multiple(array(0, 5, 9, 8, 11), array('in' => range(0, 10), 'max' => 5, 'min' => 2))= false
	}






	public function testNumeric() {
		AND cakephp_validate_numeric('teststring')= false
		AND cakephp_validate_numeric('1.1test')= false
		AND cakephp_validate_numeric('2test')= false

		AND cakephp_validate_numeric('2')= true
		AND cakephp_validate_numeric(2)= true
		AND cakephp_validate_numeric(2.2)= true
		AND cakephp_validate_numeric('2.2')= true
	}






	public function testNaturalNumber() {
		AND cakephp_validate_naturalNumber('teststring')= false
		AND cakephp_validate_naturalNumber('5.4')= false
		AND cakephp_validate_naturalNumber(99.004)= false
		AND cakephp_validate_naturalNumber('0,05')= false
		AND cakephp_validate_naturalNumber('-2')= false
		AND cakephp_validate_naturalNumber(-2)= false
		AND cakephp_validate_naturalNumber('0')= false
		AND cakephp_validate_naturalNumber('050')= false

		AND cakephp_validate_naturalNumber('2')= true
		AND cakephp_validate_naturalNumber(49)= true
		AND cakephp_validate_naturalNumber('0', true)= true
		AND cakephp_validate_naturalNumber(0, true)= true
	}
*/

	SELECT
		cakephp_validate_phone('teststring')= false
		AND cakephp_validate_phone('1-(33)-(333)-(4444)')= false
		AND cakephp_validate_phone('1-(33)-3333-4444')= false
		AND cakephp_validate_phone('1-(33)-33-4444')= false
		AND cakephp_validate_phone('1-(33)-3-44444')= false
		AND cakephp_validate_phone('1-(33)-3-444')= false
		AND cakephp_validate_phone('1-(33)-3-44')= false

		AND cakephp_validate_phone('(055) 999-9999')= false
		AND cakephp_validate_phone('(155) 999-9999')= false
		AND cakephp_validate_phone('(595) 999-9999')= false
		AND cakephp_validate_phone('(555) 099-9999')= false
		AND cakephp_validate_phone('(555) 199-9999')= false

		AND cakephp_validate_phone('1 (222) 333 4444')= true
		AND cakephp_validate_phone('+1 (222) 333 4444')= true
		AND cakephp_validate_phone('(222) 333 4444')= true

		AND cakephp_validate_phone('1-(333)-333-4444')= true
		AND cakephp_validate_phone('1.(333)-333-4444')= true
		AND cakephp_validate_phone('1.(333).333-4444')= true
		AND cakephp_validate_phone('1.(333).333.4444')= true
		AND cakephp_validate_phone('1-333-333-4444')= true
	AS testPhone;

/*
	public function testPostal() {
		AND cakephp_validate_postal('111', null, 'de')= false
		AND cakephp_validate_postal('1111', null, 'de')= false
		AND cakephp_validate_postal('13089', null, 'de')= true

		AND cakephp_validate_postal('111', null, 'be')= false
		AND cakephp_validate_postal('0123', null, 'be')= false
		AND cakephp_validate_postal('1204', null, 'be')= true

		AND cakephp_validate_postal('111', null, 'it')= false
		AND cakephp_validate_postal('1111', null, 'it')= false
		AND cakephp_validate_postal('13089', null, 'it')= true

		AND cakephp_validate_postal('111', null, 'uk')= false
		AND cakephp_validate_postal('1111', null, 'uk')= false
		AND cakephp_validate_postal('AZA 0AB', null, 'uk')= false
		AND cakephp_validate_postal('X0A 0ABC', null, 'uk')= false
		AND cakephp_validate_postal('X0A 0AB', null, 'uk')= true
		AND cakephp_validate_postal('AZ0A 0AA', null, 'uk')= true
		AND cakephp_validate_postal('A89 2DD', null, 'uk')= true

		AND cakephp_validate_postal('111', null, 'ca')= false
		AND cakephp_validate_postal('1111', null, 'ca')= false
		AND cakephp_validate_postal('D2A 0A0', null, 'ca')= false
		AND cakephp_validate_postal('BAA 0ABC', null, 'ca')= false
		AND cakephp_validate_postal('B2A AABC', null, 'ca')= false
		AND cakephp_validate_postal('B2A 2AB', null, 'ca')= false
		AND cakephp_validate_postal('X0A 0A2', null, 'ca')= true
		AND cakephp_validate_postal('G4V 4C3', null, 'ca')= true

		AND cakephp_validate_postal('111', null, 'us')= false
		AND cakephp_validate_postal('1111', null, 'us')= false
		AND cakephp_validate_postal('130896', null, 'us')= false
		AND cakephp_validate_postal('13089-33333', null, 'us')= false
		AND cakephp_validate_postal('13089-333', null, 'us')= false
		AND cakephp_validate_postal('13A89-4333', null, 'us')= false
		AND cakephp_validate_postal('13089-3333', null, 'us')= true

		AND cakephp_validate_postal('111')= false
		AND cakephp_validate_postal('1111')= false
		AND cakephp_validate_postal('130896')= false
		AND cakephp_validate_postal('13089-33333')= false
		AND cakephp_validate_postal('13089-333')= false
		AND cakephp_validate_postal('13A89-4333')= false
		AND cakephp_validate_postal('13089-3333')= true
	}






	public function testPhonePostalSsnPass() {
		AND cakephp_validate_postal('text', null, 'testNl')= true
		AND cakephp_validate_phone('text', null, 'testDe')= true
		AND cakephp_validate_ssn('text', null, 'testNl')= true
	}







	public function testPassThroughMethodFailure() {
		Validation::phone('text', null, 'testNl');
	}







	public function testPassThroughClassFailure() {
		Validation::postal('text', null, 'AUTOFAIL');
	}






	public function testPassThroughMethod() {
		AND cakephp_validate_postal('text', null, 'testNl')= true
	}
*/

	SELECT
		cakephp_validate_ssn('111-333', null, 'dk')= false
		AND cakephp_validate_ssn('111111-333', null, 'dk')= false
		AND cakephp_validate_ssn('111111-3334', null, 'dk')= true

		AND cakephp_validate_ssn('1118333', null, 'nl')= false
		AND cakephp_validate_ssn('1234567890', null, 'nl')= false
		AND cakephp_validate_ssn('12345A789', null, 'nl')= false
		AND cakephp_validate_ssn('123456789', null, 'nl')= true

		AND cakephp_validate_ssn('11-33-4333', null, 'us')= false
		AND cakephp_validate_ssn('113-3-4333', null, 'us')= false
		AND cakephp_validate_ssn('111-33-333', null, 'us')= false
		AND cakephp_validate_ssn('111-33-4333', null, 'us')= true
	AS testSsn;

/*
	public function testUserDefined() {
		$validator = new CustomValidator;
		AND cakephp_validate_userDefined('33', $validator, 'customValidate')= false
		AND cakephp_validate_userDefined('3333', $validator, 'customValidate')= false
		AND cakephp_validate_userDefined('333', $validator, 'customValidate')= true
	}






	public function testDatetime() {
		AND cakephp_validate_datetime('27-12-2006 01:00', 'dmy')= true
		AND cakephp_validate_datetime('27-12-2006 01:00', array('dmy'))= true
		AND cakephp_validate_datetime('27-12-2006 1:00', 'dmy')= false

		AND cakephp_validate_datetime('27.12.2006 1:00pm', 'dmy')= true
		AND cakephp_validate_datetime('27.12.2006 13:00pm', 'dmy')= false

		AND cakephp_validate_datetime('27/12/2006 1:00pm', 'dmy')= true
		AND cakephp_validate_datetime('27/12/2006 9:00', 'dmy')= false

		AND cakephp_validate_datetime('27 12 2006 1:00pm', 'dmy')= true
		AND cakephp_validate_datetime('27 12 2006 24:00', 'dmy')= false

		AND cakephp_validate_datetime('00-00-0000 1:00pm', 'dmy')= false
		AND cakephp_validate_datetime('00.00.0000 1:00pm', 'dmy')= false
		AND cakephp_validate_datetime('00/00/0000 1:00pm', 'dmy')= false
		AND cakephp_validate_datetime('00 00 0000 1:00pm', 'dmy')= false
		AND cakephp_validate_datetime('31-11-2006 1:00pm', 'dmy')= false
		AND cakephp_validate_datetime('31.11.2006 1:00pm', 'dmy')= false
		AND cakephp_validate_datetime('31/11/2006 1:00pm', 'dmy')= false
		AND cakephp_validate_datetime('31 11 2006 1:00pm', 'dmy')= false
	}






	public function testMimeType() {
		$image = CORE_PATH . 'Cake' . DS . 'Test' . DS . 'test_app' . DS . 'webroot' . DS . 'img' . DS . 'cake.power.gif';
		$File = new File($image, false);
		$this->skipIf(!$File->mime(), 'Cannot determine mimeType');
		AND cakephp_validate_mimeType($image, array('image/gif'))= true
		AND cakephp_validate_mimeType(array('tmp_name' => $image), array('image/gif'))= true

		AND cakephp_validate_mimeType($image, array('image/png'))= false
		AND cakephp_validate_mimeType(array('tmp_name' => $image), array('image/png'))= false
	}







	public function testMimeTypeFalse() {
		$image = CORE_PATH . 'Cake' . DS . 'Test' . DS . 'test_app' . DS . 'webroot' . DS . 'img' . DS . 'cake.power.gif';
		$File = new File($image, false);
		$this->skipIf($File->mime(), 'mimeType can be determined, no Exception will be thrown');
		Validation::mimeType($image, array('image/gif'));
	}






	public function testUploadError() {
		AND cakephp_validate_uploadError(0)= true
		AND cakephp_validate_uploadError(array('error' => 0))= true

		AND cakephp_validate_uploadError(2)= false
		AND cakephp_validate_uploadError(array('error' => 2))= false
	}

*/

-- *****************************************************************************
COMMIT;
-- *****************************************************************************
