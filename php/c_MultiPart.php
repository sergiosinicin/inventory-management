<?php

/**
 * Description of the MultiPart Class
 *
 * @author Jonathan Jones
 */

class MultiPart
{
    // property declaration
    public $parts;
    public $user_input;
    public $num_results;
    
    
    public function __construct($input)
    {
        $this->parts = array();
        $this->user_input = $input;
    }
    
    
    // Performs database searches and attempts to locate all part
    // information that is in a specific location.
    public function findBin()
    {
        // Get an array of part_id numbers that all relate to the user-given location
        $part_ids = $this->filterSingle($this->queryDB("SELECT part_id FROM parts WHERE location=(?)", $this->user_input), 'part_id');
        foreach ($part_ids as $index => $part)
        {
            $temp_part = new Part($part);
            
            $temp_part->locateAllInfo();
            
            $this->parts[] = $temp_part;
            
        }
        
        $this->num_results = count($this->parts);
    }
    
    
    // Sends results to client as JSON encoded data.
    public function sendBinJSON()
    {
        echo json_encode($this);
    }
    
    public function sendBin()
    {
        foreach($this->parts as $index => $vals)
        {
            $vals->sendPart();
        }
    }
    
    
    // remove once confirmed not needed
    /*
    private function queryDB($sql, $user_input)
    {
        global $CONN;   // let function know about the global declared connection
        
        $input = (string)$user_input;

        if(!$query = $CONN->prepare($sql)){
            echo "Error: Could not prepare query statement. (" . $query->errno . ") " . $query->error . "\n";
        }
        if (!$query->bind_param("s", $input)) {
            echo "Error: Failed to bind parameters to statement. (" . $query->errno . ") " . $query->error . "\n";
        }
        if (!$query->execute()) {
            echo "Error: Failed to execute query. (" . $query->errno . ") " . $query->error . "\n";
        }
        
        return $query;   // return the results after formatting to an arry of php objects
    }   // function queryDB
    
    
    
    // filters a queries results
    private function filterSingle($query, $field_name)
    {
        $meta = $query->result_metadata();  // get the metadata from the results

        // store the field heading names into an array, pass by reference
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }

        // callback function; same as: $query->bind_result($params)
        call_user_func_array(array($query, 'bind_result'), $params);
       
        $results = array();
        while ($query->fetch()) {   // fetch the results for every field
            $results[] = $row[$field_name];
        }

        // close the open database/query information
        $meta->close();
        $query->close();

        return $results;
    }   // function filterSingle
    */
}   // end of Bin Class
?>