<?
use Bitrix\Main\{Localization\Loc, Loader, Config\Option};

if (!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();

class VoteList extends \CBitrixComponent
{
    const DEFAULT_PAGE_SIZE = 5;

    /**
     * Выполняет логику работы компонента
     * 
     * @return void|null - ничего не возвращает
     */
    public function executeComponent()
    {
        try {
            if (empty($this->arParams['CHANNEL_CODE']))
                throw new Exception(Loc::getMessage('ERROR_EMpTY_CHANNEL_CODE'));
            
            Loader::includeModule('vote');

            $channelUnit = null;
            // Потому что не работает фильтрация по символьному коду
            $channels = CVoteChannel::GetList($field = 'ID', $dir = 'ASC', [], $filtered);
            while ($channel = $channels->Fetch()) {
                if ($channel['SYMBOLIC_NAME'] != $this->arParams['CHANNEL_CODE']) continue;

                $channelUnit = $channel;
                break;
            }
            if (!$channelUnit)
                throw new Exception(
                    Loc::getMessage(
                        'ERROR_BAD_CHANNEL_CODE',
                        ['#CODE#' => $this->arParams['CHANNEL_CODE']]
                    )
                );

            $this->arResult['OPTIONS'] = json_decode(
                    Option::get(
                        INFS_RUSVINYL_MODULE_ID,
                        INFS_RUSVINYL_OPTION_NAME,
                        false, SITE_ID
                    ), true
                );
            if (!in_array($channelUnit['ID'], $this->arResult['OPTIONS']['VoteChannels']))
                throw new Exception(Loc::getMessage('ERROR_CHANNEL_ID_EXISTS'));

            $this->arResult['CHANNEL'] = $channelUnit + [
                'DETAIL_URL' => $this->arParams['DETAIL_URL']
                              ? preg_replace('/#([^#]+)#/ui', '{{$1}}', $this->arParams['DETAIL_URL'])
                              : $_SERVER['REQUEST_URI'] . '{{ID}}/',
                'PAGE_SIZE' => intval($this->arParams['PAGE_SIZE']) > 0
                             ? $this->arParams['PAGE_SIZE']
                             : self::DEFAULT_PAGE_SIZE
            ];
            $this->includeComponentTemplate();

        } catch (Exception $error) {
            ShowError($error->getMessage());
        }
    }
};