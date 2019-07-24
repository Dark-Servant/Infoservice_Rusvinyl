<?
namespace Infoservice\RusVinyl\Helpers;

abstract class StringWorker
{
    /**
     * Убирает лишнии переходы на новую строку, оставляя максимальное допустимое
     * их количество
     *
     * @return string
     */
    public static function setMaxNewLine(string $value, int $maxLineCounbt = 1)
    {
        return preg_replace('/([\r\n](?:\s*?[\r\n])?)\s*/iu', '$1', trim($value));
    }
}