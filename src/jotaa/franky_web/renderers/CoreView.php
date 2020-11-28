<?php declare(strict_types = 1);

namespace jotaa\franky_web\renderers;

use jotaa\core\FrankyCore;
use WeakReference;

final class CoreView
{
    public const DS = DIRECTORY_SEPARATOR;

    private \WeakReference $weakFranky;

    public function __construct(FrankyCore $franky)
    {
        $this->weakFranky = WeakReference::create($franky);
    }

    public function renderView(string $path, array $data = []) : string
    {
        $filePath = $this->getViewPath($path);
        $_ENV['file'] = $filePath;
        $exists = file_exists($filePath);
        if ($exists) {
            ob_start();
            extract($data);
            require $filePath;
            ob_end_flush();
            die;
        }
        throw new \Safe\Exceptions\FilesystemException("View file {$filePath} doent exists");
    }


    public function getViewAsString(string $path, array $data = []) : string
    {
        $filePath = $this->getViewPath($path);
        $_ENV['file'] = $filePath;
        $exists = file_exists($filePath);
        if ($exists) {
            ob_start();
            extract($data);
            require $filePath;
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }
        throw new \Safe\Exceptions\FilesystemException("View file {$filePath} doent exists");
    }


    /**
     * getViewPath
     *
     * Returns the full absolute path to a view file using the registered directory for views
     *
     * @param string $fileName
     * @return string
     */
    public function getViewPath(string $fileName) : string
    {
        $franky = $this->weakFranky->get();
        return $franky->config('viewPath') . self::DS . $fileName;
    }
}
