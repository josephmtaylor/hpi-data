<?php
session_start();

require_once "../classes/carmodel.class.php";
			
			
if (isset($_GET['type'])) {

	if ($_GET['type'] == "viewmodel") {
	
			$manufacturer_id = $_GET['manufacturer_id'];
			
			$searchStatus = 'exact';
			
			$A1 = new CarModelClass;
			$cntTotal = $A1->getTotal('manufacturer_id',$manufacturer_id, $searchStatus);
			$cmRec = $A1->getList('manufacturer_id',$manufacturer_id,  $searchStatus);
	
			if ($cntTotal > 0) {
			
			?>
			 <p>
			 <label for="car_model_id_fk">Car Model </label>
			 <select id="car_model_id_fk" name="car_model_id_fk" style="width:250px;">
					<option value="">- Select Model -</option>
				<?	
					if ($cntTotal > 0) { 
						 for ($i=0; $i < $cntTotal; $i++) { 
								$cmid = $cmRec[$i]['id'];
								$carModel = $cmRec[$i]['name'];
					?>	
						<option value="<?php echo $cmid; ?>" ><?php echo $carModel; ?></option>
					<? 	 } 
					} // end if 
					else {
				?>	
						<option value="">- N/A -</option>
				 <? }// end else ?>
			 </select>
			 </p>
			 
<?php
			} // end if cntTotal
			else
				return NULL;


	}
	
	

}

?>

