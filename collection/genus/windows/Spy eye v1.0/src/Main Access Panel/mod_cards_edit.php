<?

require_once 'mod_dbase.php';
require_once 'mod_crypt.php';

$id = $_POST['id'];
if (!@$id)
  exit();

$dbase = db_open();
if (!$dbase) exit;

  $num_card      = $_POST['num'];
  $card_sec_code = $_POST['csc'];
  $exp_date      = $_POST['exp_date'];
  $name          = $_POST['name'];
  $surname       = $_POST['surname'];
  $address       = $_POST['address'];
  $city          = $_POST['city'];
  $state         = $_POST['state'];
  $country       = $_POST['country'];
  $post_code     = $_POST['post_code'];
  $tel           = $_POST['phone'];
  $email         = $_POST['email'];

  //
    list($month, $year) = split('[\/.-]', $exp_date);
	$exp_date = gmdate("Y.m.d H:i:s", mktime(0,0,0,$month,1,$year));
				
	$res = mysqli_query($dbase, 
			    "SELECT id_email"
			  . " FROM email_t"
			  . " WHERE value_email like '" . $email . "'"
			  . " LIMIT 0, 1"
		    );					
	if ((@($res)) && mysqli_num_rows($res))
	{
	  $mres=mysqli_fetch_array($res);
	  $id_email = $mres[0];
    }		   
	else
	{
      $res = mysqli_query($dbase, 
	  "INSERT INTO email_t "
	. "VALUES (null,'".$email."')"
	  );
	  $id_email = mysqli_insert_id($dbase);
	}

	$res = mysqli_query($dbase, 
			    "SELECT id_country"
			  . " FROM country_t"
			  . " WHERE name_country like '" . $country . "'"
			  . " LIMIT 0, 1"
	);
	if ((@($res)) && mysqli_num_rows($res))
	{
	  $mres = mysqli_fetch_array($res);
	  $id_country = $mres[0];
	}
	else
	{
	  $sql = "INSERT INTO country_t "
	       . "VALUES (null,'". $country ."')";
      $res = mysqli_query($dbase, $sql);
			 
	  $id_country = mysqli_insert_id($dbase);			 
	}
        
    if ($card_sec_code == '')
	   $card_sec_code = 0;
		  
    if ($id_email == '')
	  $id_email = 0;

    if ($id_country == '')
	  $id_country = 0;
		
	$num_card = base64_encode(encode($num_card, $card_sec_code));

  
  //
  
  $sql = "UPDATE cards "
       . "  SET num = '$num_card', csc = '$card_sec_code', exp_date = '$exp_date', name = '$name', surname = '$surname', address = '$address', city = '$city', state = '$state', post_code = '$post_code', phone_num = '$tel', fk_email = $id_email, fk_country = $id_country"
	   . " WHERE id_card = $id"
	   . " LIMIT 1";
  $res = mysqli_query($dbase,  $sql);
  
  if (mysqli_affected_rows($dbase) == 1) {
    echo "UPDATE IS <font class='ok'>OK</font>";
  }
  else {
    echo "<font class='error'>ERROR</font> ON UPDATE<br><small>$sql</small>";
  }
  
  db_close($dbase);

?>