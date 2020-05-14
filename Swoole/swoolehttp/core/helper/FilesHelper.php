<?php


namespace Core\helper;


class FilesHelper
{
    /**
     * 返回文件的 md5 过后的值
     * @param $dir
     * @param $ignore
     * @return string
     */
    public static function getFileMd5($dir, $ignore)
    {
        $files = glob($dir);
        $ret = [];
        foreach ($files as $file) {
            if (is_dir($file) && strpos($file, $ignore) === false) {
                // 如果是文件夹，则需要递归，
//                $ret[] = self::getFileMd5("{$file}/*", $ignore);
                $ret[] = self::getFileMd5($file . '/*', $ignore);
            } elseif (!empty(pathinfo($file)['extension']) && pathinfo($file)['extension'] == "php") {
                $ret[] = md5_file($file);
            }
        }
        return md5(implode("", $ret));
    }
}