<?php
/**
 * File Description:
 *
 * Helper class to manage calls to a RESTful API
 *
 * @category   helper
 * @package    core
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2014
 * @license    /license.txt
 */
namespace fmvc\core\helper;

class RestApiCaller
{

    /**
     * Simple function to manage a get request.
     *
     * @param string $url  location to make the API call to
     * @return string      the data returned by the API call
     */
    public static function get($url)
    {
        return file_get_contents($url);
    }

    /**
     * Helper function that manages post requests via cURL.  It
     * will accept an associative array or an XML string.
     *
     * @param string $url  Location to make the API call to
     * @param mixed  $data associative array or XML string
     * @param string $dataType `fields`, `json`, or `xml`
     * @return boolean true  return value of curl_exec() call
     *
     */
    public static function post($url, $data, $dataType)
    {
        $curlInput = self::prepareData($data, $dataType);

        $ch = curl_init();
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_URL => $url,
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => 1,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: ' . $curlInput['type'],
                    'Content-Length: ' . strlen($curlInput['data'])
                ),
                CURLOPT_POSTFIELDS => $curlInput['data'],
            )
        );
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * send delete request to API
     * @param $url
     * @return mixed
     */
    public static function delete($url)
    {
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_URL => $url,
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "DELETE",
            )
        );
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * Helper function that manages post requests via cURL.  It
     * will accept an associative array or an XML string.
     *
     * @param string $url  Location to make the API call to
     * @param mixed  $data associative array or XML string
     * @param string $dataType `fields`, `json`, or `xml`
     * @return boolean true  return value of curl_exec() call
     *
     */
    public static function put($url, $data, $dataType)
    {
        $curlInput = self::prepareData($data, $dataType);

        $ch = curl_init();
        curl_setopt_array(
            $ch,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: ' . $curlInput['type'],
                    'Content-Length: ' . strlen($curlInput['data'])
                ),
                CURLOPT_POSTFIELDS => $curlInput['data'],
            )
        );
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * Prepares the data string to go into a PUT or POST
     * request
     *
     * @param mixed $data  either an XML string or associative array
     * @param string $dataType type of data (`xml`, `json`, `fields`)
     * @return array associative array where `data` is the formatted data string and `type`
     *                  is the Content Type string for the HTTP request
     *
     */
    protected static function prepareData($data, $dataType)
    {
        switch ($dataType)
        {
            // accepts an associative array of data (like $_POST from a form)
            case 'fields':
                $curlData = http_build_query($data);
                $contentType = 'application/x-www-form-urlencoded';
                break;

            // expects an associative array and converts to JSON
            case 'json':
                $curlData = json_encode($data);
                $contentType = 'application/json';
                break;

            //expects a properly formatted XML string
            case 'xml':
                $curlData = $data;
                $contentType = 'text/xml';
                break;
        }
        return array('data' => $curlData, 'type' => $contentType);
    }
}
