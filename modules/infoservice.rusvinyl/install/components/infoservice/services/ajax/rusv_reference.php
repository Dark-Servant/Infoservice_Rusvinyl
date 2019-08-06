<?
namespace Infoservice\Rusvinyl\Ajax;

use \Bitrix\Main\{
    Localization\Loc,
    Loader
};

class RusvReference
{
    protected $request;
    protected $optionUnits;
    protected $answer;
    protected $currentUserId;
    protected $currentTime;

    function __construct($request, $optionUnits, &$answer, $currentUserId, $currentTime)
    {
        $this->request = $request;
        $this->optionUnits = $optionUnits;
        $this->answer = &$answer;
        $this->currentUserId = $currentUserId;
        $this->currentTime = $currentTime;
    }

    /**
     * Обработчик создания элемента
     * 
     * @return int
     */
    public function new()
    {
        $salary = $this->request->getPost('new-reference-salary');
        $salaryUnit = $salary ? \CIBlockPropertyEnum::GetById($salary) : null;
        $fields = [
            'ACTIVE' => 'N',
            'NAME' => Loc::getMessage('REFERENCE_TITLE'),
            'IBLOCK_ID' => $this->optionUnits['IBlocks'][INFS_IBLOCK_REFERENCE],
            'DETAIL_TEXT' => Loc::getMessage(
                                'REFERENCE_SALARY', [
                                    'ANSWER' => $salaryUnit ? $salaryUnit['VALUE'] : '-'
                                ]
                            ),
            'PROPERTY_VALUES' => [INFS_IB_REFERENCE_PR_SALARY => $salary]
        ];
        $referenceIBElement = new \CIBlockElement;
        if (!($referenceId = $referenceIBElement->Add($fields)))
          throw new \Exception($referenceIBElement->LAST_ERROR);

        return $referenceId;
    }
};
