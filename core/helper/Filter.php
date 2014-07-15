<?php
/**
 * File Description
 * Class for basic Filters.  Uses static methods to
 * test the validity of input.
 *
 * No dependencies needed
 *
 * @category helper
 * @package  core
 * @author andy
 * @copyright 2014
 * @license  /license.txt
 *
 * @todo:  Some of these need improvement and simplification
 */
namespace fmvc\core\helper;

class Filter
{

    /**
     * Filters input to see if a file name is legitimate
     * underscore and period plus alphanumeric characters
     *
     * @param $input
     * @return boolean
     */
    public static function filename($input)
    {
        $clean = str_replace('_', '', $input);
        $clean = str_replace('.', '', $clean);
        if (self::alphanumeric($clean) == true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Filters to see if the input is an integer
     * @param $input
     * @return boolean
     */
    public static function integer($input)
    {
        if (is_int($input)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Filter to see if input is numeric
     * @param $input
     * @return boolean
     */
    public static function numeric($input)
    {
        if (is_numeric($input)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Filter to see if input is alphanumeric
     * @param $input
     * @return boolean
     */
    public static function alphanumeric($input)
    {
        if (ctype_alnum($input)) {
            return true;
        } else {
            return false;
        }
    }

     /**
     * Filter to see if input is alpha characters only
     * @param $input
     * @return boolean
     */
    public static function alpha($input)
    {
        if (ctype_alpha($input)) {
            return true;
        } else {
            return false;
        }
    }

     /**
     * Filter to see if input is an email address
     * @param $input
     * @return boolean
     */
    public static function email($input)
    {
        if (filter_var($input, FILTER_VALIDATE_EMAIL) != false) {
            return true;
        } else {
            return false;
        }
    }

     /**
     * Filter to see if input is a boolean
     * @param $input
     * @return boolean
     */
    public static function boolean($input)
    {
        if (filter_var($input, FILTER_VALIDATE_BOOLEAN) != false) {
            return true;
        } else {
            return false;
        }
    }

     /**
     * Filter to see if input is a valid page name
     * @param $input
     * @return boolean
     */
    public static function pageName($input)
    {
        $newInput = str_replace('_', '', $input);
        if (self::alphanumeric($newInput) === true) {
            return true;
        } else {
            return false;
        }
    }

     /**
     * Filter to see if input is a valid database field
     * @param $input
     * @return boolean
     */
    public static function databaseField($input)
    {
        $newInput = '';
        $newInput = str_replace('_', '', $input);
        $newInput = str_replace('.', '', $newInput);
        if (self::alphanumeric($newInput) === true) {
            return true;
        } else {
            return false;
        }
    }

     /**
     * Filter to see if input is a valid date
     * @param $string
     * @return boolean
     */
    public static function checkDateIsValid($string)
    {
        $parts = explode('/', $string);

        //does it have 3 parts
        if (count($parts) != 3) {
            return false;
        }

        $yr = substr(intval($parts[2]), -2)  ;
        $curr_yr = substr(intval(date('y', time())), -2);

        return checkdate($parts[0], $parts[1], $parts[2]);
    }
}
