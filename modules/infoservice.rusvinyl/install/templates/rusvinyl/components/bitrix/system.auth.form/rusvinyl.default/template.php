<?
use Bitrix\Main\Localization\Loc;

if(!defined("B_PROLOG_INCLUDED") || (B_PROLOG_INCLUDED !== true)) die();

CJSCore::Init();?>

<div class="bx-system-auth-form rusv-user-auth">
    <div class="rusv-user-auth-logo<?=$arResult['NO_AVATAR'] ? ' rusv-user-auth-no-logo' : ''?>"<?
        if (!empty($arResult['USER_LOGIN'])):?>
        title="<?=$arResult['USER_NAME']?> [<?=$arResult['USER_LOGIN']?>]"<?
        endif;?>>
        <img src="<?=$arResult['AVATAR']['src']?>">
    </div><?

if ($arResult['FORM_TYPE'] == 'login'):?>
    <form name="system_auth_form<?=$arResult['RND']?>" class="rusv-user-auth-data" method="post" target="_top"
            action="/"><?

    if ($arResult['BACKURL'] <> ''):?>
        <input type="hidden" name="backurl" value="<?=$arResult['BACKURL']?>"><?
    endif;
    foreach ($arResult['POST'] as $key => $value):?>
        <input type="hidden" name="<?=$key?>" value="<?=$value?>"><?
    endforeach;?>
        <input type="hidden" name="AUTH_FORM" value="Y">
        <input type="hidden" name="TYPE" value="AUTH">

        <table width="95%">
            <tr>
                <td colspan="2">
                    <?=Loc::getMessage('AUTH_LOGIN')?>:<br>
                    <input type="text" name="USER_LOGIN" maxlength="50" value="" size="17">
                    <script>
                        BX.ready(function() {
                            var loginCookie = BX.getCookie("<?=CUtil::JSEscape($arResult['~LOGIN_COOKIE_NAME'])?>");
                            if (loginCookie) {
                                var form = document.forms['system_auth_form<?=$arResult['RND']?>'];
                                var loginInput = form.elements['USER_LOGIN'];
                                loginInput.value = loginCookie;
                            }
                        });
                    </script>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?=Loc::getMessage('AUTH_PASSWORD')?>:<br>
                    <input type="password" name="USER_PASSWORD" maxlength="50" size="17" autocomplete="off"><?
    if ($arResult['SECURE_AUTH']):?>
                    <span class="bx-auth-secure" id="bx_auth_secure<?=$arResult['RND']?>"
                        title="<?=Loc::getMessage('AUTH_SECURE_NOTE')?>" style="display:none">
                        <div class="bx-auth-secure-icon"></div>
                    </span>
                    <noscript>
                        <span class="bx-auth-secure" title="<?=Loc::getMessage('AUTH_NONSECURE_NOTE')?>">
                            <div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
                        </span>
                    </noscript>
                    <script type="text/javascript">
                        document.getElementById('bx_auth_secure<?=$arResult['RND']?>').style.display = 'inline-block';
                    </script><?
    endif?>
                </td>
            </tr><?
    if ($arResult['STORE_PASSWORD'] == 'Y'):?>
            <tr>
                <td valign="top">
                    <input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y">
                </td>
                <td width="100%">
                    <label for="USER_REMEMBER_frm"
                        title="<?=Loc::getMessage('AUTH_REMEMBER_ME')?>"><?=Loc::getMessage('AUTH_REMEMBER_SHORT')?></label>
                </td>
            </tr><?
    endif;
    if ($arResult['CAPTCHA_CODE']):?>
            <tr>
                <td colspan="2">
                <?=Loc::getMessage('AUTH_CAPTCHA_PROMT')?>:<br>
                <input type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>">
                <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" width="180" height="40" alt="CAPTCHA">
                <br><br>
                <input type="text" name="captcha_word" maxlength="50" value=""></td>
            </tr><?
    endif;?>
            <tr>
                <td colspan="2">
                    <input type="submit" name="Login" value="<?=Loc::getMessage('AUTH_LOGIN_BUTTON')?>">
                </td>
            </tr><?
    if ($arResult['NEW_USER_REGISTRATION'] == 'Y'):?>
            <tr>
                <td colspan="2">
                    <noindex>
                        <a href="<?=$arResult['AUTH_REGISTER_URL']?>" rel="nofollow"><?=Loc::getMessage('AUTH_REGISTER')?></a>
                    </noindex>
                    <br>
                </td>
            </tr><?
    endif;?>
            <tr>
                <td colspan="2">
                    <noindex>
                        <a href="<?=$arResult['AUTH_FORGOT_PASSWORD_URL']?>"
                            rel="nofollow"><?=Loc::getMessage('AUTH_FORGOT_PASSWORD_2')?></a>
                    </noindex>
                </td>
            </tr>
        </table>
    </form><?

elseif ($arResult['FORM_TYPE'] == 'otp'):?>

    <form name="system_auth_form<?=$arResult['RND']?>" method="post" target="_top" action="<?=$arResult['AUTH_URL']?>"> <?
    if ($arResult['BACKURL'] <> ''):?>
        <input type="hidden" name="backurl" value="<?=$arResult['BACKURL']?>"><?
    endif;?>
        <input type="hidden" name="AUTH_FORM" value="Y">
        <input type="hidden" name="TYPE" value="OTP">
        <table width="95%">
            <tr>
                <td colspan="2">
                    <?=Loc::getMessage('AUTH_FORM_COMP_OTP')?><br>
                    <input type="text" name="USER_OTP" maxlength="50" value="" size="17" autocomplete="off"></td>
            </tr><?
            if ($arResult['CAPTCHA_CODE']):?>
            <tr>
                <td colspan="2">
                    <?=Loc::getMessage('AUTH_CAPTCHA_PROMT')?>:<br>
                    <input type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>">
                    <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" width="180"
                        height="40" alt="CAPTCHA">
                    <br><br>
                    <input type="text" name="captcha_word" maxlength="50" value="">
                </td>
            </tr><?
            endif;
            if ($arResult['REMEMBER_OTP'] == 'Y'):?>
            <tr>
                <td valign="top">
                    <input type="checkbox" id="OTP_REMEMBER_frm" name="OTP_REMEMBER" value="Y">
                </td>
                <td width="100%">
                    <label for="OTP_REMEMBER_frm" title="<?=Loc::getMessage('AUTH_FORM_COMP_OTP_REMEMBER_TITLE')?>">
                        <?=Loc::getMessage('AUTH_FORM_COMP_OTP_REMEMBER')?>
                    </label>
                </td>
            </tr><?
            endif;?>
            <tr>
                <td colspan="2">
                    <input type="submit" name="Login" value="<?=Loc::getMessage('AUTH_LOGIN_BUTTON')?>">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <noindex>
                        <a href="<?=$arResult['AUTH_LOGIN_URL']?>" rel="nofollow"><?=Loc::getMessage('AUTH_FORM_COMP_AUTH')?></a>
                    </noindex>
                    <br>
                </td>
            </tr>
        </table>
    </form><?

else:?>
    <form class="rusv-user-auth-data" action="<?=$arResult['AUTH_URL']?>">
        <table width="95%">
            <tr>
                <td align="center">
                    <?=$arResult['USER_NAME']?><br>
                    [<?=$arResult['USER_LOGIN']?>]<br>
                    <a href="<?=$arResult['PROFILE_URL']?>" title="<?=Loc::getMessage('AUTH_PROFILE')?>">
                        <?=Loc::getMessage('AUTH_PROFILE')?>
                    </a><br>
                </td>
            </tr>
            <tr>
                <td align="center"><?
                    foreach ($arResult['GET'] as $key => $value):?>
                        <input type="hidden" name="<?=$key?>" value="<?=$value?>"><?
                    endforeach;?>
                    <input type="hidden" name="logout" value="yes">
                    <input type="submit" name="logout_butt" value="<?=Loc::getMessage('AUTH_LOGOUT_BUTTON')?>">
                </td>
            </tr>
        </table>
    </form><?
endif;?>
</div>
