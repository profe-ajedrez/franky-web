<?php declare(strict_types = 1);

namespace jotaa\franky_web\helpers;

use OutOfBoundsException;
use Safe\Exceptions\FilesystemException;

/**
 * LanguageHelper
 *
 * Has the responsability of provide traductions based in keys.
 *
 * Somewhere you have to put your language file with your definitions in the form:
 *
 * ```php
 * $definitions = [
 *     'your-key' => 'Your message',
 * ]
 * ```
 *
 * So, somewhere in your app you create an instance of this class, passing the
 * language and the path to the definitions file for that language, and then you only
 * have to call the method `l()` with the required key
 *
 *```php
 * echo $languageHelperInstance->l('your-key');  //  echoes 'Your message'
 *```
 */
class LanguageHelper
{
    private array $definitions;
    private string $language;

    /**
     * LanguageHelper Class Constructor
     *
     * @param string $language
     * @param string $filePath
     */
    public function __construct(string $language, string $filePath)
    {
        $this->language = $language;
        if (file_exists($filePath)) {
            $this->definitions = json_decode(\Safe\file_get_contents($filePath), true);
            return;
        }
        throw new FilesystemException("Language file {$filePath} doesnt exists");
    }

    /**
     * l
     *
     * Returns the registered traduction for the passed key
     *
     * @param string $key
     * @return void
     */
    public function l(string $key)
    {
        if (array_key_exists($key, $this->definitions)) {
            return $this->definitions[$key];
        }
        throw new OutOfBoundsException("Undefined language definition key {$key} in language {$this->language}");
    }
}
