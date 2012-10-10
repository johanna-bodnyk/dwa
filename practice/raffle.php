<!DOCTYPE html>
<html>
<head>
    <title>Raffle v1</title>

    <?php
    # Who are our contestants? 
        $contestants["Ethel"] = "";
        $contestants["Leroy"] = "";
        $contestants["Sam"]   = "";
        $contestants["Sandy"] = "";
            
    # Pick and print a winning number 
        $how_many_contestants = count($contestants);
        $winning_number       = rand(1,$how_many_contestants);
        
	#Create an array of unique numbers as long as the number of contestants
		$ticket_numbers = array_fill(0,4,0);

		for($i=0; $i<$how_many_contestants; $i++) {
			$unique = FALSE;
			while ($unique == FALSE) {
				$number = rand(1,$how_many_contestants);
				for($y=0; $y<$how_many_contestants; $y++) {
					if ($ticket_numbers[$y] != $number) {
						$unique = TRUE;
					}
					else {
						$unique = FALSE;
						break;
					}
				}
			}
		$ticket_numbers[$i] = $number;
		}
	var_dump($ticket_numbers);
	# Assign each contestant a unique random number between 1 and the number of contestants
		foreach($contestants as $name => $ticket) {
			$unique = FALSE;
			while ($unique == FALSE) {
				$number = rand(1,$how_many_contestants);
				foreach ($contestants as $index => $ticket_number) {
					if ($number != $ticket_number) {
						$unique = TRUE;
					}
					else {
						$unique = FALSE;
						break;
					}
				}
			}
		$contestants[$name] = $number;
		}
	# Loop through contestants, printing their number and seeing if they won 
        foreach($contestants as $name => $ticket_number) {
            
			# Print their number
			
			echo "$name has the number $ticket_number";
			
            # See if their generated random  number mathches the winning number
            if($ticket_number == $winning_number) {
            echo "$name is a winner";
            }
            else {
            echo "$name is a loser";
            }        
        }
    ?>
    
</head>    
<body>
    Refresh to play again <br><br>
    
    The winning number is <?=$winning_number?>!<br><br>
        
    <? foreach($contestants as $contestant => $winner_or_loser): ?>
        <?=$contestant?> is a <?=$winner_or_loser?><br>
    <? endforeach; ?>
</body>
</html>