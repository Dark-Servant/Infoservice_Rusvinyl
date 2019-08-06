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
        $userId = intval($this->request->getPost('new-2-pit-user'));
        if (!$userId || !($user = \CUser::GetById($userId)->Fetch()))
            throw new \Exception(Loc::getMessage('ERROR_USER_ID'));
            
        $dateFrom = $this->request->getPost('new-2-pit-from');
        $dateTo = $this->request->getPost('new-2-pit-to');
        $fields = [
            'ACTIVE' => 'N',
            'NAME' => Loc::getMessage('2PIT_TITLE', [
                'ID' => $userId,
                'FULL_NAME' => trim($user['LAST_NAME'] . ' ' . $user['NAME']) ?: $user['email']
            ]),
            'IBLOCK_ID' => $this->optionUnits['IBlocks'][INFS_IBLOCK_2_PIT],
            'DETAIL_TEXT' => Loc::getMessage('PERIOD_TEXT', [
                'FROM' => $dateFrom,
                'TO' => $dateTo
            ]),
            'PROPERTY_VALUES' => [
                INFS_IB_2_PIT_PR_EMPLOYEE => $userId,
                INFS_IB_2_PIT_PR_FROM => new DateTime($dateFrom),
                INFS_IB_2_PIT_PR_TO => new DateTime($dateTo)
            ]
        ];
        $TwoPitIBElement = new \CIBlockElement;
        if (!($TwoPitId = $TwoPitIBElement->Add($fields)))
          throw new \Exception($TwoPitIBElement->LAST_ERROR);

        return $TwoPitId;
    }
}
