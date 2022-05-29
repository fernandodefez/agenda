<?php

/**
 * @author Fernando Defez <fernandodefez@outlook.com>
 */

namespace FernandoDefez\Agenda\App;

class Image {

    /**
     * Stores an image within /storage/contacts directory
     *
     * @author Fernando Defez
     * @function make
     * @param array $file
     * @param string $filename
     * @return bool
     */
    public static function make(array $file, string $filename) : bool
    {
        $thumbnail = $_SERVER['DOCUMENT_ROOT'] . "/storage/contacts/". $filename;
        return move_uploaded_file($file['tmp_name'], $thumbnail);
    }

    /**
     * Removes an image within /storage/contacts directory if it exists
     *
     * @author Fernando Defez
     * @function destroy
     * @param string $thumbnail
     * @return bool
     */
    public static function destroy(string $thumbnail) : bool
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . "/storage/contacts/". $thumbnail;

        if (file_exists($path)) {
            unlink($path);
            return true;
        }
        return false;
    }

}
