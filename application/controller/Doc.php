<?php
/**
 * File Description:
 *
 * Controller for the Documentation Viewer
 *
 * @category   application
 * @package    controller
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2014
 * @license    /license.txt
 */
namespace fmvc\application\controller;

class Doc extends \fmvc\core\lib\Controller
{

    public function __construct($deps, $params)
    {
        parent::__construct($deps, $params);

        $this->parser = new \Parsedown();
        $this->doc = new \fmvc\core\helper\Documentation();
        $this->head->style(array('docs'));
        $this->head->title('FlightMVC Documentation Viewer');
    }

    public function index()
    {
        $data = array();

        $data['html_head'] = $this->head->head();
        $data['filelist'] = $this->doc->getDocumentationFileNames();


        if (isset($this->param[0])) {
            $data['text'] = $this->doc->parseDocumentationFile($this->param[0].'.md', $this->parser);
        } else {
            $markdown = file_get_contents('./README.md');
            $data['text'] = $this->parser->text($markdown);
        }

        \Flight::render('docs/index', $data);
    }
}
