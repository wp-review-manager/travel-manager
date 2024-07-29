<?php namespace WPTravelManager\Classes;

class View
{
    public static function make($path, $data = [])
    {
        $path = str_replace('.', DIRECTORY_SEPARATOR, $path);
        $file = TRM_DIR.'includes/Views/'.$path.'.php';
        if (!file_exists($file)) {
            return 'View not found: '.$file;
        }
        ob_start();
        extract($data);
        include $file;
        return ob_get_clean();
    }

    public static function render($path, $data = [])
    {
        echo static::make($path, $data);
    }
}
