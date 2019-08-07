<?
namespace Infoservice\Rusvinyl\Ajax;

use \Bitrix\Main\{
    Localization\Loc,
    Loader,
    Type\DateTime
};

require __DIR__ . '/rusv_reference.php';

class RusvVisa extends RusvReference
{
    /**
     * Обработчик создания элемента
     * 
     * @return int
     */
    public function new()
    {
        $dateValue = $this->request->getPost('new-visa-date');
        $properties = [
            INFS_IB_VISA_PR_COUNTRY => $this->request->getPost('new-visa-country'),
            INFS_IB_VISA_PR_DATE => new DateTime($dateValue),
            INFS_IB_VISA_PR_PURPOISE => $this->request->getPost('new-visa-purpoise'),
            INFS_IB_VISA_PR_PASSPORT => $this->request->getPost('new-visa-passport'),
            INFS_IB_VISA_PR_LANGUAGE => $this->request->getPost('new-visa-language'),
        ];
        $purpoise = !$properties[INFS_IB_VISA_PR_PURPOISE] ? null
                  : \CIBlockPropertyEnum::GetById($properties[INFS_IB_VISA_PR_PURPOISE]);

        $language = !$properties[INFS_IB_VISA_PR_LANGUAGE] ? null
                  : \CIBlockPropertyEnum::GetById($properties[INFS_IB_VISA_PR_LANGUAGE]);

        $fields = [
            'ACTIVE' => 'N',
            'NAME' => Loc::getMessage('VISA_COUNTRY', ['COUNTRY' => $properties[INFS_IB_VISA_PR_COUNTRY]]),
            'IBLOCK_ID' => $this->optionUnits['IBlocks'][INFS_IBLOCK_VISA],
            'DETAIL_TEXT' => Loc::getMessage(
                                'VISA_DETAIL_TEXT', [
                                    'DATE' => $dateValue,
                                    'PURPOISE' => $purpoise ? $purpoise['VALUE'] : '',
                                    'PASSPORT' => $properties[INFS_IB_VISA_PR_PASSPORT],
                                    'LANGUAGE' => $language ? $language['VALUE'] : '',
                                ]
                            ),
            'PROPERTY_VALUES' => $properties
        ];
        $visaIBElement = new \CIBlockElement;
        if (!($visaId = $visaIBElement->Add($fields)))
          throw new \Exception($visaIBElement->LAST_ERROR);

        return $visaId;
    }
}