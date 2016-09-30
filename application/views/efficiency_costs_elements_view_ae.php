				<?php
            		foreach($base_load as $mrow)
            		{
            			//Get Name of element
            	?>
            	<div>
            		<div class="con250 bggray">
            			<?php
            			$depth = '';
                        for($i=1; $i<$mrow['depth']; $i++)
                        {
                            $depth .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                        } 
            			if($mrow['number_children'] > 0)
            			{
            		?>
						<?php echo "<span class='left'>".$depth."</span>"; ?><a href="#" class="dvlink" onclick="get_ae('<?php echo $mrow['element']; ?>', '<?php echo "ae".$mrow['element']; ?>'); return false;" title="Click to Expand/Collapse" ><span class="ui-icon ui-icon-squaresmall-plus left"></span><?php echo $mrow['name_element']; ?></a>
					<?php
            			} else {
            				echo "<span class='left'>".$depth."&nbsp;&nbsp;</span>".$mrow['name_element']; 
            			}
						?>
            		</div>
            		<div class="con250 bggray">
            			<?php
            				//a base load node will always start with RP. how to chain this?
            				//get actual value and set 1st var 
            				$a1 = 0;
            				foreach($table_base as $avalrow)
            				{
            					$apath = explode(",", $avalrow['path']);
								if($apath[0] == $version_actual_area && $apath[4] == $mrow['element'])
								{
									$a1 = $avalrow['value'];
									foreach($receiver_RP as $rprow)
									{
										if($apath[5] == $rprow['element'])
										{
											$rpname = $rprow['name_element'];
											if($rprow['number_children'] > 0)
											{
												//has child. make link.
						?>
							<a href="#" class="dvlink" onclick="get_rp('<?php echo $mrow['element']; ?>', '<?php echo $rprow['element'] ?>', '<?php echo "rp".$mrow['element']."ae".$mrow['element']; ?>'); return false;" title="Click to Expand/Collapse"><span class="ui-icon ui-icon-squaresmall-plus left"></span><?php echo $rpname; ?></a>
						<?php
											} else {
												echo $rpname;
											}
										}
									}
								}
								
            				}
            				//echo $receiver_RP[0]['name_element']; 
            			?>
            		</div>
            		<div class="con150 center bggray">
            			<?php
							echo CUR_SIGN." ".number_format($a1, 0, '.', ',');
            			?>
            		</div>
            		<div class="con150 center bggray">
            			<?php
            				//get plan/target value and set 2nd var
            				$a2 = 0;
            				foreach($table_base as $bvalrow)
            				{
            					$bpath = explode(",", $bvalrow['path']);
								if($bpath[0] == $version && $bpath[4] == $mrow['element'])
								{
									$a2 = $bvalrow['value'];
									
								}
								
            				}
							echo CUR_SIGN." ".number_format($a2, 0, '.', ',');
            			?>
            		</div>
            		<div class="con150 center bggray">
            			<?php
            				$aresult = $a2-$a1;
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
            		<div id="<?php echo "rp".$mrow['element']."ae".$mrow['element']; ?>"></div>
            		<div class="clearfix"></div>
            		<div id="<?php echo "ae".$mrow['element']; ?>"></div>
            		<div class="clearfix"></div>
            	</div>
            	<?php
            		}
				//$this->jedoxapi->traceme($base_load);
				//$this->jedoxapi->traceme($table_base);
            	?>