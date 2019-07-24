<?

$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/helpers/list+pages/style.css');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/helpers/list+pages/script.js');

?>
<script id="rusv-unit-list-pages-template" type="text/x-handlebars-template">
    {{#each PAGES}}
    --><span
            class="rusv-unit-list-page {{#if CURRENT}} rusv-unit-list-current-page{{/if}}"
            data-number="{{NUMBER}}">{{NUMBER}}</span><!--
    {{/each}}
</script>