<?
namespace Infoservice\Rusvinyl\Ajax;

use \Bitrix\Main\{
    Localization\Loc,
    Loader,
    Type\DateTime
};

require __DIR__ . '/rusv_reference.php';

class Rusv2Pit extends RusvReference
{
    /**
     * Обработчик создания элемента
     * 
     * @return int
     */
    public function new()
    {
        global $USER; 

        $userId = $USER->GetId();
        $user = \CUser::GetById($userId)->Fetch();
            
        $period = $this->request->getPost('new-2-pit-period');
        $fields = [
            'ACTIVE' => 'N',
            'NAME' => Loc::getMessage('2PIT_TITLE', [
                'ID' => $userId,
                'FULL_NAME' => trim($user['LAST_NAME'] . ' ' . $user['NAME']) ?: $user['email']
            ]),
            'IBLOCK_ID' => $this->optionUnits['IBlocks'][INFS_IBLOCK_2_PIT],
            'DETAIL_TEXT' => Loc::getMessage('PERIOD_TEXT', [
                'PERIOD' => $period
            ]),
            'PROPERTY_VALUES' => [
                INFS_IB_2_PIT_PR_PERIOD => $period,
            ]
        ];
        $TwoPitIBElement = new \CIBlockElement;
        if (!($TwoPitId = $TwoPitIBElement->Add($fields)))
          throw new \Exception($TwoPitIBElement->LAST_ERROR);

        return $TwoPitId;
    }
}
