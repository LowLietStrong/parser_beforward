<?php
function siteAutoload($className) {
    $fileName = $className . '.php';
    include_once  $fileName;
}

spl_autoload_register('siteAutoload');

function set_autoload_class_path($path)
{

    if ($handle = opendir($path)) {

        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                $file = explode('.', $file, 2);

                if ($file[1] == 'php') {
                    siteAutoload($path.$file[0]);
                }

            }
        }

        closedir($handle);
    }

}
set_autoload_class_path(__DIR__ . '/helpers/');
set_autoload_class_path(__DIR__ . '/models/');

category::setCategory();