<?
namespace Infoservice\RusVinyl\EventHandles;

use Bitrix\Main\Loader;

abstract class RusvThanks
{
    /**
     * Обработчик события ПОСЛЕ добавления элемента
     * 
     * @param $element - данные элемента
     * @return mixed
     */
    public static function OnAfterIBlockElementAdd($element)
    {
        if (preg_match('/^\/bitrix\/admin\//i', $_SERVER['REQUEST_URI'])) return;

        Loader::includeModule('bizproc');

        $documentType = [
            'iblock',
            'CIBlockDocument',
            'iblock_' . $element['IBLOCK_ID']
        ];
        $workFlows = \CBPWorkflowTemplateLoader::GetList(
            [], [
                'DOCUMENT_TYPE' => $documentType,
                'MODULE_ID' => 'iblock',
                'ACTIVE' => 'Y',
                'AUTO_EXECUTE' => \CBPDocumentEventType::Create
            ]
        );
        $documentType[2] = $element['ID'];
        while ($workFlow = $workFlows->Fetch()) {
            \CBPDocument::StartWorkflow(
                $workFlow['ID'], $documentType, $parameters = [], $errors = []
            );
        }
    }
}