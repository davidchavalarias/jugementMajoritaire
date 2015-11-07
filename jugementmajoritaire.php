<?php
echo '<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>';
/*
 * This program takes a CSV and compute the jugement majoritaire of candidates.
Commande line : php jugementmajoritaire.php name_of_candidates list
CSV is one column per
You have to specify the names of the marks in ascending order in the variable $scores2mention
You have to specity the delimiters of your CSV. Default is tab delimiter, no enclosure
Launch the script in command line with 'php jugementmajoritaire.php path-to-your-csv
Output is the list of candidates sorted by descending ranks with their marks.
 */

$delimiter = "\t";
$enclosure = ' ' ;
$enclosure_out='"'; // pour le fichier de sortie
$scores2mention=array('Insuffisant','Passable','Assez bien','Bien','Très bien','Excellent');

$mention2scores=array();
foreach ($scores2mention as $key => $value) {
	$mention2scores[$value]=$key;
}
//print_r($mention2scores);

if ($_GET) {
    $project_name= $_GET['project']; // nombre de proce        
} else {
    $project_name= $argv[1]; // nom du projet    
}

$line_id=0;
$handle = fopen($project_name, "r","UTF-8");
        while (($line= fgetcsv($handle, 4096,$delimiter,$enclosure)) !== false) {
        	$line_id+=1;
        	if ($line_id<2){
        		$candidates=array();
        		$candidates_names=array();
        		foreach ($line as $key => $value) {
        			$candidates[$key]=array();
        			$candidates_names[$key]=$value;
        		}
        	}else{        		
        		foreach ($candidates as $key => $marks) {        			 
        				array_push($marks,$line[$key] );
        				$candidates[$key]=$marks;        		
        		}
        	}	        	
        }

$candidates_scores_for_sorting=array();
$candidates_scores=array();

foreach ($candidates as $key => $marks) {    
    $scores=(rank($marks,$mention2scores));
    $n=count($scores);// nombre de vote exprimés pour ce candidat
    $size=count($scores);// on fait une récursion sur la taille du tableau
    $final_mark=array();
    //print_r($scores);
    while ($size>0){ 
        $temp_mark=$scores[floor(count($scores)/2)];       
        $final_mark[]=$temp_mark;
        $to_remove=array_keys($scores,$temp_mark,true);
        array_splice($scores,$to_remove[0],count($to_remove));
        $size=count($scores);
    }
    $scores_for_sorting='';
    foreach ($final_mark as $key2 => $value) {
        $scores_for_sorting.=$value;
    }
    $candidates_scores_for_sorting[$key]=$scores_for_sorting;
    $candidates_scores[$key]=$final_mark;
    //echo 'score for '.$candidates_names[$key].'='.$scores_for_sorting.PHP_EOL;  
    //echo score2mention($final_mark,$scores2mention).PHP_EOL;
    //print_r($candidates_scores[$key]);
}
arsort($candidates_scores_for_sorting,SORT_STRING);
//print_r($candidates_scores_for_sorting);


// output
$output= fopen('results.txt', "w","UTF-8");
foreach ($candidates_scores_for_sorting as $key => $value) {
    fputs($output,$candidates_names[$key].' : '.score2mention($candidates_scores[$key],$scores2mention).PHP_EOL);
    echo $candidates_names[$key].' : '.score2mention($candidates_scores[$key],$scores2mention).PHP_EOL; 
}

fclose($output);





function rank($array,$mention2scores)
// converts an array with mention into a sorted array with their rank without empty cells
{
    //print_r($array);
    //print_r($mention2scores);
        
    $temp=array();
    foreach ($array as $key => $value) {
        if ($value!=""){
            $temp[]=$mention2scores[$value];
        }
        
    }
    sort($temp);
    return($temp);
}
function score2mention($candidates_scores,$scores2mention){
    $mention='';
    foreach ($candidates_scores as $key => $score) {
        $mention.=$scores2mention[$score].'/';
    }
    return($mention);
}