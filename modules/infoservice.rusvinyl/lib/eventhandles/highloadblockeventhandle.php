<?
namespace Infoservice\RusVinyl\EventHandles;

use Bitrix\Highloadblock\HighloadBlockTable as HLT;
use Bitrix\Main\{Loader, Type\DateTime};
use Infoservice\RusVinyl\Helpers\Options;

abstract class HighloadBlockEventHandle
{
    /**
     *  Обработчики highloadblock
     *    <символьное имя highloadblock>OnBeforeAdd
     *    <символьное имя highloadblock>OnAdd
     *    <символьное имя highloadblock>OnAfterAdd
     *    <символьное имя highloadblock>OnBeforeUpdate
     *    <символьное имя highloadblock>OnUpdate
     *    <символьное имя highloadblock>OnAfterUpdate
     *    <символьное имя highloadblock>OnBeforeDelete
     *    <символьное имя highloadblock>OnDelete
     *    <символьное имя highloadblock>OnAfterDelete
     */

    /**
     * Получение информации об элементе инфоблока "Участвовать"
     * с данными о том, какие бизнес-процессы отвечают за прием желания
     * на участие и ответ пользователю
     * 
     * @param $elementId - идентификатор элемента инфоблока
     * @return CIBlockElement
     */
    protected static function getParticipateUsersIBlockElement($elementId)
    {
        Loader::includeModule('iblock');
        return \CIBlockElement::GetList(
                        [],
                        [
                            'IBLOCK_ID' => Options::getIBlocks(INFS_RUSVINYL_IBLOCK_PARTICIPATE),
                            'ID' => $elementId
                        ], false, false,
                        [
                            'ID',
                            'PROPERTY_' . INFS_IB_PARTICIPATE_PR_SEND_DESIRE,
                            'PROPERTY_' . INFS_IB_PARTICIPATE_PR_SEND_ANSWER,
                        ]
                    )->Fetch();
    }

    /**
     * Обработчик события ПОСЛЕ создания записи о желании участвовать
     * в элементе инфоблока "Участвовать"
     * 
     * @param $event - данные события
     * @return  void
     */
    public static function ParticipateUsersOnAfterAdd($event)
    {
        if (!Employment::setBussy()) return;

        $eventFields = $event->getParameters()['fields'];
        if (empty($eventFields[INFS_HL_PARTICIPATE_ELEMENT_FIELD])) {
            Employment::setFree();
            return;
        }

        $element = self::getParticipateUsersIBlockElement(
                        $eventFields[INFS_HL_PARTICIPATE_ELEMENT_FIELD]
                    );
        if (!$element) {
            Employment::setFree();
            return;
        }
        $documentType = ['iblock', 'CIBlockDocument', $element['ID']];

        Loader::includeModule('bizproc');
        \CBPDocument::StartWorkflow(
            $element['PROPERTY_' . INFS_IB_PARTICIPATE_PR_SEND_DESIRE . '_VALUE'],
            $documentType,
            [
                'user' => $eventFields[INFS_HL_PARTICIPATE_USER_FIELD],
                'date' => $eventFields[INFS_HL_PARTICIPATE_DATE_FIELD]
            ],
            $errors = []
        );
        Employment::setFree();
    }

    /**
     * Обработчик события ПЕРЕД обновлением записи о желании участвовать
     * в элементе инфоблока "Участвовать"
     * 
     * @param $event - данные события
     * @return  void
     */
    public static function ParticipateUsersOnBeforeUpdate($event)
    {
        if (!Employment::setBussy()) return;

        Loader::includeModule('highloadblock');
        $hlblock = HLT::getById(Options::getHighloadBlock(INFS_HL_PARTICIPATE_USERS))->fetch();
        $hlblock = HLT::compileEntity($hlblock)->getDataClass();
        $_SESSION[INFS_ABVEXPO_MODULE_ID]['HL'][INFS_HL_PARTICIPATE_USERS] = 
            $hlblock::GetList(['filter' => ['ID' => $event->getParameters()['id']['ID']]])->Fetch();

        Employment::setFree();
    }

    /**
     * Обработчик события ПОСЛЕ обновления записи о желании участвовать
     * в элементе инфоблока "Участвовать"
     * 
     * @param $event - данные события
     * @return  void
     */
    public static function ParticipateUsersOnAfterUpdate($event)
    {
        global $USER;
        if (
            empty($_SESSION[INFS_ABVEXPO_MODULE_ID]['HL'][INFS_HL_PARTICIPATE_USERS])
            || !Employment::setBussy()
        ) return;

        $eventFields = $event->getParameters()['fields'];
        $oldFields = $_SESSION[INFS_ABVEXPO_MODULE_ID]['HL'][INFS_HL_PARTICIPATE_USERS];
        if ($eventFields[INFS_HL_PARTICIPATE_CONFIRMATION_FIELD] == $oldFields[INFS_HL_PARTICIPATE_CONFIRMATION_FIELD]) {
            Employment::setFree();
            return;
        }
        $element = self::getParticipateUsersIBlockElement(
                        $eventFields[INFS_HL_PARTICIPATE_ELEMENT_FIELD]
                    );
        if (!$element) {
            Employment::setFree();
            return;
        }
        $documentType = ['iblock', 'CIBlockDocument', $element['ID']];
        $confirmation = false;
        if ($eventFields[INFS_HL_PARTICIPATE_CONFIRMATION_FIELD])
            $confirmation = \CUserFieldEnum::GetList(
                                [],
                                [
                                    'ID' => $eventFields[INFS_HL_PARTICIPATE_CONFIRMATION_FIELD]
                                ]
                            )->Fetch()['VALUE'];

        Loader::includeModule('bizproc');
        \CBPDocument::StartWorkflow(
            $element['PROPERTY_' . INFS_IB_PARTICIPATE_PR_SEND_ANSWER . '_VALUE'],
            $documentType,
            [
                'date' => new DateTime(),
                'toUser' => $eventFields[INFS_HL_PARTICIPATE_USER_FIELD],
                'fromUser' => $USER->GetID(),
                'confirmationId' => $eventFields[INFS_HL_PARTICIPATE_CONFIRMATION_FIELD],
                'confirmation' => $confirmation
            ],
            $errors = []
        );
        Employment::setFree();
    }
}