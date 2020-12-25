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

    public function renderView(string $path, array $data = [])
    {
        $filePath = $this->getViewPath($path);
        $_ENV['file'] = $filePath;
        $exists = file_exists($filePath);
        if ($exists) {
            ob_start();
            extract($data);
            require $filePath;
            ob_end_flush();
            return;
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


    public static function renderBootstrapNavLinks(array $themeData)
    {
        $index = 0;
        $html  = '';
        foreach ($themeData['nav'] as $linkLabel => $url) {
            if ($index === 0) {
                $aria   = 'aria-current="page"';
                $active = 'active';
            }
            $id = str_replace(' ', '_', $linkLabel);

            $html .= "
            <li class='nav-item'>
              <a id='{$id}_{$index}'
                 data-label='{$linkLabel}'
                 class='nav-link {$active}' {$aria}
                 href='{$url}'>{$linkLabel}</a>
            </li>
            ";
        }
        return $html;
    }


    public static function renderLi(array $elements = [], string $classnames = '')
    {
        return self::renderTag(
            function ($index, $key) use ($classnames) {
                $ids = str_replace(' ', '_', $key);
                return "<li id='{$ids}_{$index}' class= {$classnames}'>{$key}</li>";
            },
            $elements
        );
    }


    public static function renderLinks(array $elements = [], string $classnames = '', $parentTag = '')
    {
        return self::renderTag(
            function ($index, $key, $value) use ($classnames, $parentTag) {
                $ids = str_replace(' ', '_', $key);
                $openTag  = '';
                $closeTag = '';
                if ((bool) $parentTag) {
                    $openTag = "<{$parentTag} id='{$parentTag}_link_{$ids}_{$index}' class='{$parentTag}_links'>";
                    $closeTag = "</{$parentTag}>";
                }
                return "{$openTag}<a id='{$ids}_{$index}' href='{$value} class= {$classnames}'>{$key}</a>{$closeTag}";
            },
            $elements
        );
    }

    public static function renderTag(callable $renderer, array $elements = [])
    {
        $html = '';
        $index = 0;
        foreach ($elements as $key => $value) {
            $html .= $renderer($index, $key, $value, $elements);
            $index++;
        }
        return $html;
    }
}
