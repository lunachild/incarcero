<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/tr/html4/loose.dtd">

<?php
require_once 'mod_dbase.php';
require_once 'mod_time.php';
require_once 'mod_crypt.php';
require_once 'mod_file.php';

$id_card = $_GET['id'];
if (!@$id_card)
	exit;

$dbase = db_open();
if (!$dbase) exit;
		 
// �������� ������ �� �����
$sql = ' SELECT cards.num, cards.csc, cards.exp_date, cards.name, cards.surname, cards.address, cards.city, cards.state, cards.post_code, country_t.name_country, cards.phone_num, email_t.value_email '
	 . ' FROM cards, country_t, email_t'
	 . ' WHERE cards.fk_email = email_t.id_email'
	 . ' AND cards.fk_country = country_t.id_country'
	 . " AND cards.id_card = $id_card"
	 . ' LIMIT 0, 1';
$res = mysqli_query ($dbase, $sql);
if ((!(@($res))) || !mysqli_num_rows($res))	{
	writelog ("error.log", $sql);
	db_close($dbase);
	exit();
}

$res = mysqli_fetch_row($res);
list($num_card, $csc, $exp_date, $name, $surname, $address, $city, $state, $post_code, $country, $phone_num, $email) = $res;

db_close($dbase);

// ������� ������� � ������ ���� "01/13"
list($year, $month) = split('[\/.-]', $exp_date);
$res[2] = $exp_date = gmdate("m/y", mktime(0, 0, 0, $month, 1, $year));

// �� uncoding
$res[0] = $num = encode(base64_decode($num_card), $csc);

// labels & names
$reslb['num'] = 'Card number';
$reslb['csc'] = 'CSC';
$reslb['exp_date'] = 'Exp. date';
$reslb['name'] = 'Name';
$reslb['surname'] = 'Surname';
$reslb['address'] = 'Address';
$reslb['city'] = 'City';
$reslb['state'] = 'State';
$reslb['post_code'] = 'ZIP';
$reslb['country'] = 'Country';
$reslb['phone'] = 'Phone';
$reslb['email'] = 'E-Mail';

// lengths
$lnexp = 7;
$resln[0] = $numln = strlen($num) + $lnexp;
$resln[1] = $cscln = strlen($csc) + $lnexp;
$resln[2] = $exp_dateln = strlen($exp_date) + $lnexp;
$resln[3] = $nameln = strlen($name) + $lnexp;
$resln[4] = $surnameln = strlen($surname) + $lnexp;
$resln[5] = $addressln = strlen($address) + $lnexp;
$resln[6] = $cityln = strlen($city) + $lnexp;
$resln[7] = $stateln = strlen($state) + $lnexp;
$resln[8] = $post_codeln = strlen($post_code) + $lnexp;
$resln[9] = $countryln = strlen($country) + $lnexp;
$resln[10] = $phone_numln = strlen($phone_num) + $lnexp;
$resln[11] = $emailln = strlen($email) + $lnexp;

?>

<?php
		
$content .= "<form id='frm_cardsedt' onsubmit='var pdata = ajax_getInputs(\"frm_cardsedt\"); ajax_pload(\"mod_cards_edit.php\", pdata, \"ajax_smcedt\"); return false;'>";
$content .= "<table border='0' cellspacing='5' cellpadding='0'>";
$i = 0;
foreach ($reslb as $rsnm => $rslb) {
	$content .= "<tr align='left'>\n";
	$content .= "<td><input type=\"hidden\" name=\"id\" value=\"$id_card\">" . ($i + 1) . "</td>\n";
	$content .= "<td>$rslb</td>\n";
	$content .= '<td>' . '<input size="' . $resln[$i] . "\" name=\"$rsnm\" value=\"" . $res[$i] . '">' . '</td>' . "\n";
	$content .= "</tr>\n";
	$i++;
}
		
$content .= "</table>\n";
$content .= "<input type='submit' value='APPLY'> &nbsp; &nbsp; &nbsp;\n";
$content .= "<input type='button' value='CANCEL' onclick=\"parent.parent.GB_hide();\">\n";
$content .= "</form>\n";
$content .= "<br><div id='ajax_smcedt'></div>\n";

?>

<?php
require_once 'frm_skelet.php';
echo get_smskelet('CC Edit', $content);
?>