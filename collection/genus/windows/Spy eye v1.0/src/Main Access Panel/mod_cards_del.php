<?

require_once 'mod_dbase.php';

$id = $_GET['id'];
if (!@$id)
  exit;

$dbase = db_open();
if (!$dbase) exit;

  $sql = "SELECT email_t.id_email " . 
         "  FROM cards, email_t " .
		 " WHERE cards.fk_email = email_t.id_email " .
		 "   AND cards.id_card = $id " .
		 " LIMIT 0, 1";
  $res = mysqli_query($dbase,  $sql);
  list($id_email) = mysqli_fetch_row($res);
  
  $sql = "SELECT count(*) " .
         "  FROM cards " .
		 " WHERE cards.fk_email = $id_email";
  $res = mysqli_query($dbase,  $sql);
  list($cnt_email) = mysqli_fetch_row($res);
  if ($cnt_email == 1) {
    $sql = "DELETE " .
	       "  FROM email_t " .
		   " WHERE id_email = $id_email " .
		   " LIMIT 1";
	$res = mysqli_query($dbase,  $sql);	   
  }

  $sql = "DELETE "
       . "  FROM cards "
	   . " WHERE id_card = $id";
  $res = mysqli_query($dbase,  $sql);
  
  db_close($dbase);
  
  echo $id;

?>