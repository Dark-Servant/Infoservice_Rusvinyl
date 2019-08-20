<?
namespace Infoservice\RusVinyl\EventHandles;

abstract class Employment
{
    /**
     * Устанавливает занятость для всех обработчиков событий
     *
     * @return boolean
     */
    public static function setBussy()
    {
        if ($_SESSION[INFS_RUSVINYL_MODULE_ID]['PROCESS']) return false;
        
        return $_SESSION[INFS_RUSVINYL_MODULE_ID]['PROCESS'] = true;
    }

    /**
     * Снимает занятость для всех обработчиков событий
     *
     * @return boolean
     */
    public static function setFree()
    {
        $oldFree = $_SESSION[INFS_RUSVINYL_MODULE_ID]['PROCESS'];
        $_SESSION[INFS_RUSVINYL_MODULE_ID]['PROCESS'] = false;
        return !$oldFree;
    }

    /**
     * Вызывает обработчики из других классов
     * 
     * @param string $symCode - символьный код какого-нибудь класса в этом же
     * пространстве имен, что и текущий класс. Может быть указан в нижнем регистре
     * с любыми разделителями, кроме символов латинского алфавита и чисел
     * 
     * @param string $methodName - название метода. Может быть указано с именем
     * класса и пространством имен, будет выделено только имя метода
     * 
     * @param array $parameters - массив параметров
     * @return mixed
     */
    public static function sendOtherHandle(
            string $symCode, string $methodName, 
            array $parameters
        )
    {
        $methodName = preg_replace('/^[^:]+::/', '', $methodName);
        $className = __NAMESPACE__ . '\\'
                   . preg_replace_callback(
                        '/(?:^|[^a-z\d])(\w)/',
                        function($word) {
                            return strtoupper($word[1]);
                        }, 
                        $symCode
                    );

        $result = null;
        if (class_exists($className) && method_exists($className, $methodName))
            $result = call_user_func_array($className . '::' . $methodName, $parameters);

        self::setFree();
        return $result;
    }
}