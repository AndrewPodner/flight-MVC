<?php
/**
 * File Description:
 *
 * File Generator class for creating MVC objects and files
 *
 * @category   cli
 * @package    lib
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2013
 * @license    /license.txt
 */
namespace fmvc\cli\lib;

class Filegen
{
    /**
     * Generates a model, controller, or view from a text file in the
     * /cli/templates directory.
     *
     * @param string $objectType the type of object (model, view, controller)
     * @param string $fileName name of the object to be created, will be exploded if it has a `/`
     * @param string $path the location where the object will be created
     * @param string $templatePath location of templates for objects
     * @return bool
     * @throws \Exception
     *
     */
    public function createObject($objectType, $fileName, $path, $templatePath)
    {
        //Import the contents of the proper text file
        $string = file_get_contents("$templatePath$objectType.txt");

        // if there is a slash, we need to break this into a subdirectory and file
        // and also deal with putting the namespace another level down
        if (strpos($fileName, '/') !== false) {
            $arrParts = explode('/', $fileName);
            $object = ucfirst($arrParts[1]);
            $namespace = '\\' . strtolower($arrParts[0]);
            $objectFile = ucfirst($arrParts[1]) . '.php';
            $path .= strtolower($arrParts[0]);

        // otherwise blank the namespace addon and prep the file and class names
        } else {
            $object = ucfirst($fileName);
            $namespace = '';
            $objectFile = ucfirst($fileName . '.php');
        }

        // We do not want views to have a capital letter in the file name
        if ($objectType == 'view') {
            $objectFile = strtolower($objectFile);
        }

        // replace the variable placeholders in the template with the object values
        $string = str_replace('{MODEL_NAME}', $object, $string);
        $string = str_replace('{SUBDIR_NAME}', $namespace, $string);

        //check to make sure it doesn't already exist before committing
        if (file_exists($path.$objectFile)) {
            throw new \Exception("CLI ERROR: $path$objectFile already exists\r\n");
        } else {
            return $this->createFile($path, $objectFile, $string);
        }

    }

    /**
     * Writes out the object file
     *
     * @param string $path path to the file
     * @param string $name the name of the file with extension
     * @param string $contents the contents of the file to be written
     * @return bool
     * @throws \Exception
     */
    public function createFile($path, $name, $contents)
    {
        if (file_exists($path . DIRECTORY_SEPARATOR) === false) {
            mkdir($path . DIRECTORY_SEPARATOR, 0755);
        }

        if (! is_writeable($path)) {
            throw new \Exception('File Could not be written');
        } else {
            file_put_contents($path . DIRECTORY_SEPARATOR .$name, $contents."\r\n");
            return true;
        }
    }
}
