<?
namespace Infoservice\Rusvinyl\Ajax;

use \Bitrix\Main\{
    Localization\Loc,
    Loader
};

require __DIR__ . '/rusv_reference.php';

class RusvServicedesk extends RusvReference
{
    /**
     * Обработчик создания элемента
     * 
     * @return int
     */
    public function new()
    {
        $fields = [
            'ACTIVE' => 'N',
            'NAME' => Loc::getMessage('SERVICEDESK_TITLE'),
            'IBLOCK_ID' => $this->optionUnits['IBlocks'][INFS_IBLOCK_SERVICEDESK],
            'DETAIL_TEXT' => trim($this->request->getPost('new-servicedesk-text'))
        ];
        $referenceIBElement = new \CIBlockElement;
        if (!($referenceId = $referenceIBElement->Add($fields)))
          throw new \Exception($referenceIBElement->LAST_ERROR);

        return $referenceId;
    }
}

