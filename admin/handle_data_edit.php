<?php

require_once("_constants.php");
require_once("scripts/catalog_handler.php");
require_once("scripts/logger.php");

class Map_Data_Editor {
    
    function update_field($pin, $field, $value) {
        if (!$pin) {
            echo(json_encode(['success' => false, 'error' => 'No PIN provided']));
            die();
        }
        
        $value = $this->clean_text($value);
        if (!$value) {
            echo(json_encode(['success' => false, 'error' => 'No value provided']));
            die();
        }

        $catalog_handler = new Catalog_Handler();
        if (in_array($field, ['lumpname', 'map_name', 'author'])) {
            $catalog_handler->update_map_property($pin, $field, $value);
            echo(json_encode(['success' => true]));
            die();
        }
        else if ($field == 'lock') {
            if ($value > 0) {
                $catalog_handler->lock_map($pin);
            } else {
                $catalog_handler->unlock_map($pin);
            }
            echo(json_encode(['success' => true]));
            die();
        }
        echo(json_encode(['success' => false, 'error' => 'Not a supported field']));
    }

    function clean_text($string, $length = 0) {
       $string = trim($string);
       $string = preg_replace('/[^A-Za-z0-9\-\'! ]/', '', $string); // Removes special chars.
       if ($length) {
           $string = substr($string, 0, $length);
       }
       return $string;
    }
}

$pin = $_POST['pin'];
$field = $_POST['field'];
$value = $_POST['value'];

$handler = new Map_Data_Editor();
$handler->update_field($pin, $field, $value);
