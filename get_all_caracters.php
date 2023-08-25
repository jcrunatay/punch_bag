<div>
	<table id="rounded-corner">
		<thead>
			<tr>
				<th scope="col" class="rounded-company">ID</th>
				<th scope="col" class="rounded-q1">Name</th>
				<th scope="col" class="rounded-q4">Strength</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2" class="rounded-foot-left"></td>
				<td class="rounded-foot-right">&nbsp;</td>
			</tr>
		</tfoot>
		<tbody>
				<!-- PRINT ALL THE NAME OF THE CHARACTERS ... -->
				<?php 
					$characters = $dbManager->Get_All_Characters();
					for ($i=0; $i < count($characters) ; $i++) {
						echo'<tr>';
						echo '<td>' . $characters[$i]->getId()  . '</td>';
						echo '<td>' . $characters[$i]->getName()  . '</td>';
						echo '<td>' . "50"  . '</td>';
						echo'</tr>';
					}
				?>			
		</tbody>
	</table>	
</div>