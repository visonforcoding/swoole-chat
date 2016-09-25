<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 *  递归显示目录文件 
 * @param string $source_dir   目录名
 * @param type $directory_depth  深度
 * @param type $hidden  是否显示隐藏文件
 * @return boolean
 */
function directory_tree($source_dir) {
    $realPath =  $source_dir;
    if ($fp = opendir($realPath)) {
        $filedata = array();
//            $source_dir = rtrim($source_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        while (FALSE !== ($file = readdir($fp))) {
            // Remove '.', '..', and hidden files [optional]
            if (!trim($file, '.') OR ( $file[0] == '.')) {
                continue;
            }
            if (is_dir($realPath . '/' . $file)) {
                $filedata[] = array(
                    'isDir' => true,
                    'name' => $file,
                    'path' => $source_dir . '/' . $file
                );
            } else {
                $filedata[] = array(
                    'isDir' => false,
                    'name' => $file,
                    'path' => $source_dir . '/' . $file
                );
            }
        }
        closedir($fp);
        return $filedata;
    }
    return FALSE;
}
