<?
$arResult['USER_ID'] = $USER->GetId();
if ($arResult['USER_ID']) {
    $user = CUser::GetById($arResult['USER_ID'])->Fetch();
    if (!empty($user['PERSONAL_PHOTO']))
        $arResult['AVATAR'] = CFile::ResizeImageGet(
                                    $user['PERSONAL_PHOTO'],
                                    ['width' => 50, 'height' => 50],
                                    BX_RESIZE_IMAGE_EXACT
                                );
}

if (empty($arResult['AVATAR'])) {
    $arResult['NO_AVATAR'] = true;
    $arResult['AVATAR'] = ['src' => INFS_RUSVINYL_HEADER_USER_LOGO_SCR];
}