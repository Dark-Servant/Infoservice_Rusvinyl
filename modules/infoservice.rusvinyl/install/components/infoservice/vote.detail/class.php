<?
use Bitrix\Main\{Localization\Loc, Loader};

if (!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();

class VoteDetail extends \CBitrixComponent
{
    /**
     * Выполняет логику работы компонента
     * 
     * @return void|null - ничего не возвращает
     */
    public function executeComponent()
    {
        try {
            if (!intval($this->arParams['VOTE_ID']))
                throw new Exception(Loc::getMessage('ERROR_EMpTY_VOTE_ID'));
            
            Loader::includeModule('vote');
            if (empty($voteUnit = CVote::GetById($this->arParams['VOTE_ID'])->Fetch()))
                throw new Exception(Loc::getMessage('ERROR_BAD_VOTE_ID', ['#ID#' => $this->arParams['VOTE_ID']]));

            $this->arResult['OPTIONS'] = Infoservice\RusVinyl\Helpers\Options::getParams();
            if (!in_array($voteUnit['CHANNEL_ID'], $this->arResult['OPTIONS']['VoteChannels']))
                throw new Exception(Loc::getMessage('ERROR_BAD_CHANNEL_ID'));

            $this->arResult['VOTE'] = $voteUnit;

            $channelUnit = CVoteChannel::GetById($voteUnit['CHANNEL_ID'])->Fetch();
            $this->arResult['CHANNEL'] = $channelUnit + [
                                            'LIST_URL' => INFS_RUSVINYL_VOTE_LIST_URL[$channelUnit['SYMBOLIC_NAME']]
                                        ];

            $this->includeComponentTemplate();

        } catch (Exception $error) {
            ShowError($error->getMessage());
        }
    }
};