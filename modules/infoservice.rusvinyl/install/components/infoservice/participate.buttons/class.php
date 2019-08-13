<?
use Bitrix\Main\{Localization\Loc, Loader};
use Bitrix\Highloadblock\HighloadBlockTable as HLT;
use Infoservice\RusVinyl\Helpers\Options;

if (!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();

class ParticipateButtons extends \CBitrixComponent
{
    /**
     * Выполняет логику работы компонента
     * 
     * @return void|null - ничего не возвращает
     */
    public function executeComponent()
    {
        global $USER;

        $this->arResult['currentUserId'] = $USER->getId();
        try {
            Loader::includeModule('iblock');
            Loader::includeModule('highloadblock');
            
            $this->arResult['OPTIONS'] = Options::getParams();
            if (
                empty($this->arParams['ELEMENT_ID'])
                || !($elementId = intval($this->arParams['ELEMENT_ID']))
                || !($element = CIBlockElement::GetById($elementId)->Fetch())
                || ($element['IBLOCK_ID'] != $this->arResult['OPTIONS']['IBlocks'][INFS_RUSVINYL_IBLOCK_PARTICIPATE])
            ) throw new Exception(Loc::getMessage('ERROR_BAD_ELEMENT_ID'));
            
            $hlblock = HLT::getById($this->arResult['OPTIONS']['HighloadBlock'][INFS_HL_PARTICIPATE_USERS])->fetch();
            $hlblock = HLT::compileEntity($hlblock)->getDataClass();

            $this->arResult['STATUS'] = $hlblock::GetList(['filter' => [
                INFS_HL_PARTICIPATE_USER_FIELD => $this->arResult['currentUserId'],
                INFS_HL_PARTICIPATE_ELEMENT_FIELD => $element['ID']
            ]])->Fetch();
            if ($this->arResult['STATUS']) {
                if (!$this->arResult['STATUS'][INFS_HL_PARTICIPATE_CONFIRMATION_FIELD]) {
                    $this->arResult['STATUS'] = [
                        'VALUE' => Loc::getMessage('PARTICIPATE_DESIRE_WAS_SENT'),
                        'CLASS' => ''
                    ];

                } elseif (
                    $this->arResult['STATUS'][INFS_HL_PARTICIPATE_CONFIRMATION_FIELD] ==
                        $this->arResult['OPTIONS']['HighloadFields'][INFS_HL_PARTICIPATE_CONFIRMATION_FIELD]['YES_ID']
                ) {
                    $this->arResult['STATUS'] = [
                        'VALUE' => Loc::getMessage('PARTICIPATE_DESIRE_WAS_CONFIRMED'),
                        'CLASS' => 'confirmed'
                    ];

                } elseif (
                    $this->arResult['STATUS'][INFS_HL_PARTICIPATE_CONFIRMATION_FIELD] ==
                        $this->arResult['OPTIONS']['HighloadFields'][INFS_HL_PARTICIPATE_CONFIRMATION_FIELD]['NO_ID']
                ) {
                    $this->arResult['STATUS'] = [
                        'VALUE' => Loc::getMessage('PARTICIPATE_DESIRE_WAS_REFUSED'),
                        'CLASS' => 'refused'
                    ];

                } else {
                    $this->arResult['STATUS'] = [
                        'VALUE' => Loc::getMessage('PARTICIPATE_DESIRE_SOME_STATUS'),
                        'CLASS' => 'unknown'
                    ];
                }
            }
            $this->includeComponentTemplate();

        } catch (Exception $error) {
            ShowError($error->getMessage());
        }
    }
};