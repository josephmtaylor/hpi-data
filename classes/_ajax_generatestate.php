<?php
include "../session.php";
require_once "usstate.class.php";
require_once "country.class.php";

$A = new CountryClass;
$S = new UsStateClass;

	$country = $_REQUEST['cid'];
//	$country = $A->getCountryNameById($country_id);
	
	
	$cntStates = $S->getTotalUsStateByCountry($country);
	$stateRec = $S->getUsStateListByCountry($country);

?>

							<div class="select">
							<select name="state" id="state" style="width:200px;">
								<?php 
										if ($cntStates > 0) { ?>
										<option value="">- Select State -</option>
									<?php 	
										 for ($i=0; $i < $cntStates; $i++) { 
										 	$stateid = $stateRec[$i]['id'];
											$statename = $stateRec[$i]['state'];
									?>	
										<option value="<?php echo $stateid; ?>" <?php if ($state == $statename) { ?> selected="selected" <?php  } ?>><?php echo $statename; ?></option>
									<?php 	 } 
									    }
										else {
									?>	
										<option value="">- N/A -</option>
									<?php } ?>
							  </select>
							  </div>
