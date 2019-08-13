<?
use Bitrix\Main\{
    Config\Option,
    Type\DateTime
};

if (!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();

class IncidentsCount extends \CBitrixComponent
{
    /**
     * Выполняет логику работы компонента
     * 
     * @return void|null - ничего не возвращает
     */
    public function executeComponent()
    {
        global $USER;
        try {
            $this->arResult['currentUserID'] = $USER->GetId();
            $this->arResult['isAdmin'] = $USER->isAdmin();

            $this->arResult['lastTime'] = intval(Option::get(INFS_RUSVINYL_MODULE_ID, INFS_RUSVINYL_OPTION_INCIDENT_NAME));
            if (!$this->arResult['lastTime']) {
                $this->arResult['lastTime'] = (new DateTime(date('Y-m-d'), 'Y-m-d'))->getTimestamp();
                Option::set(INFS_RUSVINYL_MODULE_ID, INFS_RUSVINYL_OPTION_INCIDENT_NAME, $this->arResult['lastTime']);
            }

            $this->includeComponentTemplate();

        } catch (Exception $error) {
            ShowError($error->getMessage());
        }
    }
};