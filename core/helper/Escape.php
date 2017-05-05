<?php
/**
 *
 * File Description
 * Class for escaping input and output.
 * Uses static methods to do the escaping.
 *
 * No dependencies needed
 *
 * @category helper
 * @package  core
 * @author andy
 * @copyright 2017
 * @license  /license.txt
 *
 */

namespace fmvc\core\helper;


class Escape
{

    /**
     * Escapes input for variables and arrays using htmlentities
     * including single and double quotes.
     *
     * @param $input
     * @return string
     */
    public static function encode($input)
    {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                $input[$key] = htmlentities(trim($value), ENT_QUOTES);
            }
        } else {
            $input = htmlentities(trim($input), ENT_QUOTES);
        }
        return $input;
    }

    /**
     * Decodes strings and arrays that were escaped with the encode
     * method above
     *
     * @param $input
     * @return string
     */
    public static function decode($input)
    {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                $input[$key] = html_entity_decode($value, ENT_QUOTES);
            }
        } else {
            $input = html_entity_decode(trim($input), ENT_QUOTES);
        }
        return $input;
    }



}