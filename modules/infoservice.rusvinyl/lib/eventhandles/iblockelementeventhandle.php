<?
namespace Infoservice\RusVinyl\EventHandles;

use Bitrix\Main\Loader;

abstract class IBlockElementEventHandle
{
    /**
     * Метод для вызова обработчика для указанного событияи конкретного инфоблока
     * 
     * @param $iblockId - идентификатор инфоблока
     * @param string $methodName - название события
     * @param array $parameters - список параметров для обработчика
     * @return mixed
     */
    protected static function sendByIBlockId($iblockId, string $methodName, array $parameters)
    {
        if (!Employment::setBussy()) return;

        Loader::includeModule('iblock');

        $iblock = \CIBlock::GetById($iblockId)->Fetch();
        return Employment::sendOtherHandle($iblock['CODE'], $methodName, $parameters);
    }

    /**
     * Обработчик события НА добавление элемента
     * 
     * @param $element - данные элемента
     * @return mixed
     */
    public static function OnIBlockElementAdd($element)
    {
        return self::sendByIBlockId($element['IBLOCK_ID'], __METHOD__, [$element]);
    }

    /**
     * Обработчик события ПОСЛЕ добавления элемента
     * 
     * @param $element - данные элемента
     * @return mixed
     */
    public static function OnAfterIBlockElementAdd($element)
    {
        return self::sendByIBlockId($element['IBLOCK_ID'], __METHOD__, [$element]);
    }

    /**
     * Обработчик события НА обновление элемента
     * 
     * @param $shortInfo - краткая информация об элементе и его инфоблоке
     * @return mixed
     */
    public static function OnIBlockElementUpdate($shortInfo)
    {
        return self::sendByIBlockId($shortInfo['IBLOCK_ID'], __METHOD__, [$shortInfo]);
    }

    /**
     * Обработчик события ПОСЛЕ обновления элемента
     * 
     * @param $shortInfo - краткая информация об элементе и его инфоблоке
     * @return mixed
     */
    public static function OnAfterIBlockElementUpdate($shortInfo)
    {
        return self::sendByIBlockId($shortInfo['IBLOCK_ID'], __METHOD__, [$shortInfo]);
    }

    /**
     * Обработчик события НА удаление элемента
     * 
     * @param $id - идентификатор элемента
     * @param $shortInfo - краткая информация об элементе и его инфоблоке
     * @return mixed
     */
    public static function OnIBlockElementDelete($id, $shortInfo)
    {
        return self::sendByIBlockId($shortInfo['IBLOCK_ID'], __METHOD__, [$id, $shortInfo]);
    }

    /**
     * Обработчик события ДО удаления элемента
     * 
     * @param $id - идентификатор элемента
     * @return mixed
     */
    public static function OnBeforeIBlockElementDelete($id)
    {
        if (!Employment::setBussy()) return;

        Loader::includeModule('iblock');

        $iblock = \CIBlockElement::GetById($id)->Fetch();
        return Employment::sendOtherHandle($iblock['IBLOCK_CODE'], __METHOD__, [$id]);
    }

    /**
     * Обработчик события ПОСЛЕ удаления элемента
     * 
     * @param $shortInfo - краткая информация об элементе и его инфоблоке
     * @return mixed
     */
    public static function OnAfterIBlockElementDelete($shortInfo)
    {
        return self::sendByIBlockId($shortInfo['IBLOCK_ID'], __METHOD__, [$shortInfo]);
    }
}