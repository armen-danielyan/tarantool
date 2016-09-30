				<?php
					foreach($base_load as $mrow)
					{
						//Get Name of receiver
				?>
				<div>
					<div class="con250">&nbsp;</div>
					<div class="con250 bggray">
						<?php
							//get actual value and set 1st var
            				$a1 = 0;
            				foreach($table_base as $avalrow)
            				{
            					$apath = explode(",", $avalrow['path']);
								if($apath[0] == $version_actual_area && $apath[5] == $mrow['element'])
								{
									$a1 = $avalrow['value'];
									
								}
								
            				}
							//get plan/target value and set 2nd var
            				$a2 = 0;
            				foreach($table_base as $bvalrow)
            				{
            					$bpath = explode(",", $bvalrow['path']);
								if($bpath[0] == $version && $apath[5] == $mrow['element'])
								{
									$a2 = $bvalrow['value'];
									
								}
								
            				}
							$aresult = $a2-$a1;
						?>
						
						<?php
						$depth = '';
                        for($i=1; $i<$mrow['depth']; $i++)
                        {
                            $depth .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                        }
						if($mrow['number_children'] > 0)
            			{
            			?>
							<?php echo "<span class='left'>".$depth."</span>"; ?><a href="#" class="dvlink" onclick="get_rp('<?php echo $account_element; ?>', '<?php echo $mrow['element']; ?>', '<?php echo "rp".$mrow['element']."ae".$account_element; ?>'); return false;" title="Click to Expand/Collapse" ><span class="ui-icon ui-icon-squaresmall-plus left"></span><?php echo $mrow['name_element']; ?></a>
						<?php
	            		} else {
	            			echo "<span class='left'>".$depth."&nbsp;&nbsp;</span>".$mrow['name_element'];
							if( isset($mrow['manager']) && isset($mrow['email']) )
							{
						?>
								<a title="Send a proEO query" 
								href="mailto:<?php echo $mrow['email']; ?>?cc=%23DecisionSupport@ssigroup.com&subject=proEO Cost Query&body=<?php echo $mrow['manager']; ?>,%0D%0A%0D%0AThe following posting requires an explanation:%0D%0A%0D%0A Filter Selection: <?php echo $this->jedoxapi->get_name($year_elements, $year); ?>, <?php echo $this->jedoxapi->get_name($month_all_alias, $month); ?>, <?php echo $this->jedoxapi->get_name($version_name, $version); ?> vs Actual%0D%0ACost Element: <?php echo $this->jedoxapi->get_name($account_element_set, $account_element); ?>%0D%0AResource Pool: <?php echo $mrow['name_element']; ?>%0D%0AAmount: <?php echo CUR_SIGN.' '.number_format($aresult, 0, '.', ','); ?>%0D%0A%0D%0AThank You."><span class="ui-icon ui-icon-mail-closed right"></span></a>
						<?php		
							}
							
	            		}
						
						?>
					</div>
					<div class="con150 center bggray">
						<?php
            				echo CUR_SIGN." ".number_format($a1, 0, '.', ',');
            			?>
						
					</div>
					<div class="con150 center bggray">
						<?php
							echo CUR_SIGN." ".number_format($a2, 0, '.', ',');
            			?>
					</div>
					<div class="con150 center bggray">
						<?php
            				
							if($aresult < 0){
								echo "<span class='redcolor'>".CUR_SIGN." ".number_format($aresult, 0, '.', ',')."</span>";
							}
							else
							{
								echo CUR_SIGN." ".number_format($aresult, 0, '.', ',');
							}
            			?>
					</div>
					<div class="clearfix"></div>
            		<div id="<?php echo "rp".$mrow['element']."ae".$account_element; ?>"></div>
            		<div class="clearfix"></div>
				</div>
				<?php
					}
					//$this->jedoxapi->traceme($month_all_alias);
				?>