<?php
ini_set("log_errors", 1);
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set("error_log", "../../tmp/emx.log");

class Test_Controller{
	
	/*
		Main function, responds to questions.
		
		@param	string $d
		@param  string $q
		$return array || string
	*/
	function get_answer($q, $d){
		//The below could be put into a database and gathered from there or even put in a config file and pulled from there.
		//In this case, a switch would work just fine.
		error_log($q);
		
			switch($q){
				case "Ping":
				$result = "OK";
				break;
				case "Referrer":
				$result = "Indeed.com";
				break;
				case "Source":					
				$result = "https://github.com/greglesiak/EMXchallenge";
				break;
				case "Phone":
				$result = "(224)-430-0183";
				break;
				case "Resume":
				$result = "http://greglesiak.com/resume/";
				break;
				case "Status":
				$result = "Yes";
				break;
				case "Years":
				$result = "6 Years";
				break;
				case "Email Address":
				$result = "greglesiak@gmail.com";
				break;
				case "Degree":
				$result = "Bachelors of Science in Interactive Media from Depaul University";
				break;
				case "Name":
				$result = "Grzegorz (Greg) Lesiak";
				break;
				case "Position":
				$result = "Software Engineer";
				break;
				case "Puzzle":
				$result = $this->puzzle($d);
				break;
				default:
				$result = "Error, this is not a valid question";
				break;
			}
		
		return $result;
	}
	
	/*
		Main controller, completes the puzzle
		
		@param	string $d
		$return array
	*/
	function puzzle($d){
		
			$puzzleString = $this->setUpPuzzle($d);
			$puzzle = $this->processPuzzle($puzzleString);
			$puzzle = $this->addLetters($puzzle);
			return $puzzle;
	}
	/*
		Takes the input string and converts it into an array while also removing unneeded characters.
		
		@param	string $puzzleString
		$return array
	*/
	function setUpPuzzle($puzzleString){
			
			//separate puzzle from text
			$puzzleString = explode(':',$puzzleString);
			
			//Remove linebreak
			$puzzleString = preg_replace("/\r|\n/", "", $puzzleString[1]);
			$puzzleString = str_replace(' ', '', $puzzleString);
			$puzzleString = substr($puzzleString,4);
			
			//separate out each row
			$puzzleString = str_split($puzzleString, 5);
			
			//move the letter from each row
			foreach($puzzleString as &$row){
					$row = substr($row,1);
			}
			
			return $puzzleString;

	
	}

	/*
		Does some initial processing of the data we already know, and then mirrors it across the = line.
		
		@param	array $puzzle
		$return array
	*/
	function processPuzzle($puzzle){
		
		$operators = ['>','<'];
		for($row = 0;$row < count($puzzle); $row++){
			
			//Automatically set = for A=A B=B and so on
			$puzzle[$row][$row] = '=';
			//check each column of each row and flip flop since we know that the puzzle is mirrored across the = line.
			for($column = 0; $column < strlen($puzzle[$row]); $column++){
				if(in_array($puzzle[$row][$column],$operators)){
					$puzzle[$column][$row] = ($puzzle[$row][$column] == '>') ? '<' : '>';
				}
				
			}
		}
				
		return $this->solvePuzzle($puzzle);
		
	}
	
	/*
		completes the puzzle
		
		@param	string $puzzle
		@param  array @solved
		$return array, array
	*/
	function SolvePuzzle($puzzle,$solved = array()){
				
		//check to see if puzzle is solved
		if(count($solved) >= count($puzzle)){
			
			return $puzzle;
			
		}
		for($row = 0; $row < count($puzzle); $row++){
			//Check if row is already solved. if it is, skip it.
			
			if(in_array($row, $solved)){
				
				continue;
			}
			
			if($this->isBiggest($puzzle[$row], $solved)){
				for($column = 0; $column < strlen($puzzle[$row]); $column++){
										
					if($puzzle[$row][$column] == '-'){
						$puzzle[$row][$column] = '>';
						$puzzle[$column][$row] = '<';
					}
					
				}
				
				array_push($solved,$row);
			}
			
		}
		
		//repeat until puzzle is solved by passing the updated puzzle as well as which rows we have solved
		return $this->solvePuzzle($puzzle, $solved);
		
	}
	/*
		Checks to see if this is the biggest row. if not, returns false.
		
		@param	string $row
		@param  array $solved
		$return boolean
	*/
	function isBiggest($row, $solved){
		for($i = 0;$i < strlen($row); $i++){
			
			if($row[$i] == '<'){
				
				if(in_array($i, $solved) !== false){
					continue;
				}
				return false;
			}
				
		}
		return true;
	}
	
	/*
		Re-adds the letters to the first position of each row as well as the top row of letters.
		
		@param	array $puzzle
		$return array
	*/
	function addLetters($puzzle){
		$i=0;
		
		//cycle through each row and add its' corresponding letter
		foreach($puzzle as &$p){
			if($i==0){
				$p = 'A' . $p;
			}elseif($i==1){
				$p = 'B' . $p;
			}elseif($i==2){
				$p = 'C' . $p;
			}elseif($i==3){
				$p = 'D' . $p;
			}else{
				//Do nothing
			}
			$i++;
		}
		
		//add string to front of array.
		array_unshift($puzzle, ' ABCD');
		return $puzzle;
	}
			
	
}
?>