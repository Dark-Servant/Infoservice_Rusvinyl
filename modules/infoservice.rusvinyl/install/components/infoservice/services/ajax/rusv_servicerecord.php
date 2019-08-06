<?
namespace Infoservice\Rusvinyl\Ajax;

use \Bitrix\Main\{
    Localization\Loc,
    Loader
};

require __DIR__ . '/rusv_reference.php';

class RusvServicerecord extends RusvReference
{
    /**
     * Обработчик создания элемента
     * 
     * @return int
     */
    public function new()
    {
        $countValue = $this->request->getPost('new-servicerecord-count');
        $fields = [
            'ACTIVE' => 'N',
            'NAME' => Loc::getMessage('SERVICERECORD_TITLE'),
            'IBLOCK_ID' => $this->optionUnits['IBlocks'][INFS_IBLOCK_SERVICERECORD],
            'DETAIL_TEXT' => Loc::getMessage(
                                'SERVICERECORD_COUNT', [
                                    'COUNT' => $countValue
                                ]
                            ),
            'PROPERTY_VALUES' => [
                INFS_IB_SERVICERECORD_PR_COUNT => $countValue
            ]
        ];
        $servicerecordIBElement = new \CIBlockElement;
        if (!($servicerecordId = $servicerecordIBElement->Add($fields)))
          throw new \Exception($servicerecordIBElement->LAST_ERROR);

        return $servicerecordId;
    }
}


