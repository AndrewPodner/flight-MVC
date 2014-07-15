<?php
/**
 * File Description:
 *
 * Main view for the FlightMVC documentation
 *
 * @category   view
 * @package    application
 * @author     Andrew Podner <andy@unassumingphp.com>
 * @copyright  2013
 * @license    /license.txt
 */
?>
<html>
    <head>
        <?=$html_head?>
    </head>
    <body>
        <div class="wrapper">
            <div>
                <h1>Documentation Viewer for the Flight-MVC Extension</h1>
            </div>
                <div class="leftCol">
                    <p><strong>Main Menu</strong></p>
                    <ul>
                        <li><a href="/doc/index/">About Flight-MVC</a></li>
                    <?php
                    foreach ($filelist as $file) {
                        $display = ucwords(str_replace('.md', '', str_replace('_', ' ', $file)));
                        $param = str_replace('.md', '', $file);
                        echo '<li><a href="/doc/index/'.$param.'">'.$display.'</a></li>';
                    }
                    ?>
                </div>

                <div class="rightCol">
                    <?=$text?>
                </div>
        </div>
    </body>
</html>