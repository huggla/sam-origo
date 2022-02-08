<?php
    function pgArrayToPhp($text) 
    {
        if(is_null($text)) 
        {
            return array();
        }
        elseif(is_string($text) && $text != '{}') 
        {
            $text = substr($text, 1, -1);// Removes starting "{" and ending "}"
            if(substr($text, 0, 1) == '"') 
            {
                $text = substr($text, 1);
            }
            if(substr($text, -1, 1) == '"') 
            {
                $text = substr($text, 0, -1);
            }
            
            // If double quotes are present, we know we're working with a string.
            if(strstr($text, '"'))  // Assuming string array.
            {
                $values = explode('","', $text);
            } 
            else  // Assuming Integer array.
            {
                $values = explode(',', $text);
            }
            $fixed_values = array();
            foreach($values as $value) 
            {
                $value = str_replace('\\"', '"', $value);
                $fixed_values[] = $value;
            }
            return $fixed_values;
        }
        else 
        {
            return array();
        }
    }
?>
