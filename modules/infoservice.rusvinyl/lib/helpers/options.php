<?
namespace Infoservice\RusVinyl\Helpers;

use Bitrix\Main\Config\Option;

abstract class Options
{
    protected static $params;

    /**
     * Получение всех параметров модуля
     * 
     * @return array
     */
    public static function getParams()
    {
        if (!self::$params) {
            $data = Option::get(
                        INFS_RUSVINYL_MODULE_ID,
                        INFS_RUSVINYL_OPTION_NAME, false,
                        \CSite::GetDefSite()
                    );
            self::$params = $data ? json_decode($data, true) : [];
        }
        return self::$params;
    }

    /**
     * Сохранение всех параметров в модуле
     * 
     * @return void
     */
    public static function save()
    {
        Option::set(INFS_RUSVINYL_MODULE_ID, INFS_RUSVINYL_OPTION_NAME, json_encode(self::getParams()));
    }

    /**
     * Общий для всех статических get/set-методов
     * 
     * @param $method - Название метода
     * @param $params - параметры метода
     * @return mixed
     */
    public static function __callStatic($method, $params)
    {
        if (!preg_match('/^([sg]et)(\w+)$/i', $method, $methodParts)) return;
        $group = self::getParams()[$methodParts[2]];

        if (strcasecmp('get', $methodParts[1])) {
            $paramsCount = count($params);
            if ($paramsCount > 1) {
                self::$params[$methodParts[2]][$params[0]] = $params[1];

            } elseif ($paramsCount) {
                self::$params[$methodParts[2]] = $params[0];
            }

        } else {
            if (empty($params)) return $group;

            if (!empty($group[$params[0]]))
                return $group[$params[0]];
        }
    }
}