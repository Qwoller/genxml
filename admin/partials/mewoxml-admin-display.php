<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://meowone.ru
 * @since      1.0.0
 *
 * @package    Mewoxml
 * @subpackage Mewoxml/admin/partials
 */
?>
<h2>Генерация yml для Яндекс Директа</h2>
<form method="post" action="">
    <button type="submit" class="btn btn-primary">Генерация xml (NO SALE)</button>
    <input type="hidden" name="action" value="import">
    Ссылка для просмотра фида основных категорий <a href="/wp-content/uploads/yandex_direct.yml">yandex_direct.yml</a>
</form>
<br>
<form method="post" action="">
    <button type="submit" class="btn btn-primary">Генерация xml (SALE)</button>
    <input type="hidden" name="action" value="import_sale">
    Ссылка для просмотра фида скидочных категорий <a href="/wp-content/uploads/yandex_direct_sale.yml">yandex_direct.yml</a>
</form>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
