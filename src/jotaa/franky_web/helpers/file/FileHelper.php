<?php

namespace jotaa\franky_web\helpers\file;

use jotaa\franky_web\exceptions\file\FileUploadException;
use jotaa\franky_web\helpers\string\Uuid;

class FileHelper
{

    public static function tryToCreateAnUploadDir($path)
    {
        if (!file_exists($path)) {
            try {
                mkdir(
                    $path,
                    0775
                );
                return;
            } catch (\Exception $e) {
                throw new \Exception('Directorio de carga ' . $path .
                   ' no existe en sistema de archivos y no pudo ser creado');
            }
        }

        if (!is_dir($path)) {
            throw new \Exception('Ruta de carga de archivos ' . $path . ' no es un directorio');
        }

        if (!is_writable($path)) {
            throw new \Exception('Ruta de carga de archivos ' . $path . ' no es escribible');
        }
    }

    public static function tryToRemoveFile(string $oldPath)
    {
        if (!file_exists($oldPath)) {
            try {
                return unlink($oldPath);
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }


    public static function isUploadedFileSupported(array $file, array $supportedMimeList)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimetype = finfo_file($finfo, $file['tmp_name']);

        //if ($mimetype != 'image/jpg' && $mimetype != 'image/jpeg' && $mimetype != 'image/png') {
        return (!in_array($mimetype, $supportedMimeList));
    }


    public static function uniqueFileName(string $filename)
    {
        return self::filterFilename(Uuid::v4() . $filename);
    }

    public static function filterFilename(string $filename, bool $beautify = true)
    {
        // sanitize filename
        $filename = preg_replace(
            '~
            [<>:"/\\|?*]|            # file system reserved https://en.wikipedia.org/wiki/Filename#Reserved_characters_and_words
            [\x00-\x1F]|             # control characters http://msdn.microsoft.com/en-us/library/windows/desktop/aa365247%28v=vs.85%29.aspx
            [\x7F\xA0\xAD]|          # non-printing characters DEL, NO-BREAK SPACE, SOFT HYPHEN
            [#\[\]@!$&\'()+,;=]|     # URI reserved https://tools.ietf.org/html/rfc3986#section-2.2
            [{}^\~`]                 # URL unsafe characters https://www.ietf.org/rfc/rfc1738.txt
            ~x',
            '-',
            $filename
        );
        // avoids ".", ".." or ".hiddenFiles"
        $filename = ltrim($filename, '.-');
        // optional beautification
        if ($beautify) {
            $filename = self::beautifyFilename($filename);
        }
        // maximize filename length to 255 bytes http://serverfault.com/a/9548/44086
        $ext = pathinfo(
            $filename,
            PATHINFO_EXTENSION
        );
        $filename = mb_strcut(
            pathinfo(
                $filename,
                PATHINFO_FILENAME
            ),
            0,
            255 -
            ($ext ? strlen($ext) + 1 : 0),
            mb_detect_encoding($filename)
        ) .
        ($ext ? '.' . $ext : '');
        return $filename;
    }

    public static function beautifyFilename(string $filename)
    {
        // reduce consecutive characters
        $filename = preg_replace(array(
            // "file   name.zip" becomes "file-name.zip"
            '/ +/',
            // "file___name.zip" becomes "file-name.zip"
            '/_+/',
            // "file---name.zip" becomes "file-name.zip"
            '/-+/'
        ), '-', $filename);
        $filename = preg_replace(array(
            // "file--.--.-.--name.zip" becomes "file.name.zip"
            '/-*\.-*/',
            // "file...name..zip" becomes "file.name.zip"
            '/\.{2,}/'
        ), '.', $filename);
        // lowercase for windows/unix interoperability http://support.microsoft.com/kb/100625
        $filename = mb_strtolower($filename, mb_detect_encoding($filename));
        // ".file-name.-" becomes "file-name"
        $filename = trim($filename, '.-');
        return $filename;
    }
}
