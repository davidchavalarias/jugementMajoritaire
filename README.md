# jugementMajoritaire
 * This program takes a CSV and compute the jugement majoritaire of candidates.
Commande line : php jugementmajoritaire.php name_of_candidates list
CSV is one column per candidate with first line being the names of candidates.
- You have to specify the names of the marks in ascending order in the variable $scores2mention
- You have to specity the delimiters of your CSV. Default is tab delimiter, no enclosure
- Launch the script in command line with 'php jugementmajoritaire.php path-to-your-csv
- Output is the list of candidates sorted by descending ranks with their marks.
 
