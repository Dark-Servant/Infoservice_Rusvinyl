<?
if (!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();

class Iframe extends \CBitrixComponent
{
    /**
     * Выполняет логику работы компонента
     * 
     * @return void|null - ничего не возвращает
     */
    public function executeComponent()
    {
        try {
            $this->includeComponentTemplate();

        } catch (Exception $error) {
            ShowError($error->getMessage());
        }
    }
};