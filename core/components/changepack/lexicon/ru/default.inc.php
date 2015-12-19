<?php
include_once 'setting.inc.php';

$_lang['changepack'] = 'ChangePack';
$_lang['changepack_menu_desc'] = 'Упаковка и перенос изменных элементов и ресурсов.';

$_lang['changepack_commit'] = 'Коммиты и лог изменений';
$_lang['changepack_backup'] = 'Применение коммитов и беккап';

$_lang['changepack_commit_msg'] = 'Таблица коммитов (фиксаций изменений).';
$_lang['changepack_log_msg'] = 'Таблица логов';

$_lang['changepack_intro_msg'] = 'Вы можете выделять сразу несколько предметов при помощи Shift или Ctrl.';

$_lang['changepack_grid_user'] = 'Пользователь';

$_lang['changepack_log_id'] = 'Id';
$_lang['changepack_log_commit_id'] = 'Коммит ID';
$_lang['changepack_log_action'] = 'Действие';
$_lang['changepack_log_mod_class'] = 'Класс ресурса';
$_lang['changepack_log_mod_id'] = 'Id ресурса';
$_lang['changepack_log_name'] = 'Имя ресурса';
$_lang['changepack_log_user_id'] = 'Id пользователя';
$_lang['changepack_log_last'] = 'В коммит';
$_lang['changepack_log_data'] = 'Дата';

$_lang['changepack_logs_remove'] = 'Удалить логи';
$_lang['changepack_log_remove'] = 'Удалить лог';
$_lang['changepack_logs_remove_confirm'] = 'Вы уверены, что хотите удалить эти логи?';
$_lang['changepack_log_remove_confirm'] = 'Вы уверены, что хотите удалить этот лог?';

$_lang['changepack_logs_enable'] = 'Включить логи';
$_lang['changepack_log_enable'] = 'Включить лог';
$_lang['changepack_logs_disable'] = 'Отключить логи';
$_lang['changepack_log_disable'] = 'Отключить лог';

$_lang['changepack_log_err_nf'] = 'Лог не найден.';
$_lang['changepack_log_err_ns'] = 'Лог не указан.';

$_lang['changepack_commit_create'] = 'Зафиксировать изменения';
$_lang['changepack_commit_active'] = 'Включено';

//'id', 'name', 'description', 'change_count', 'user_id', 'data', 'filename'
$_lang['changepack_commit_id'] = 'Id';
$_lang['changepack_commit_name'] = 'Имя';
$_lang['changepack_commit_description'] = 'Описание';
$_lang['changepack_commit_change_count'] = 'Число изменений';
$_lang['changepack_commit_user_id'] = 'Id пользователя';
$_lang['changepack_commit_data'] = 'Дата';
$_lang['changepack_commit_filename'] = 'Файл';

$_lang['changepack_commits_remove'] = 'Удалить коммиты';
$_lang['changepack_commit_remove'] = 'Удалить коммит';
$_lang['changepack_commits_remove_confirm'] = 'Вы уверены, что хотите удалить эти коммиты?';
$_lang['changepack_commit_remove_confirm'] = 'Вы уверены, что хотите удалить этот коммит?';
$_lang['changepack_commit_update'] = 'Изменить коммит';

$_lang['changepack_commit_err_name'] = 'Вы должны указать имя коммита.';
$_lang['changepack_commit_err_ae'] = 'Коммит с таким именем уже существует.';
$_lang['changepack_commit_err_ns'] = 'Коммит не указан.';
$_lang['modextra_commit_err_nf'] = 'Коммит не найден.';

$_lang['changepack_log_err_ns'] = 'Лог не указан.';
$_lang['changepack_log_err__nf'] = 'Лог не найден.';

$_lang['changepack_backup_file_commit'] = 'Файл коммита';
$_lang['changepack_backup_file_backup'] = 'Файл востановления';

$_lang['changepack_backups_remove'] = 'Удалить беккапы';
$_lang['changepack_backup_remove'] = 'Удалить беккап';
$_lang['changepack_backups_remove_confirm'] = 'Вы уверены, что хотите удалить эти беккапы?';
$_lang['changepack_backup_remove_confirm'] = 'Вы уверены, что хотите удалить этот беккап?';
$_lang['changepack_backup_update'] = 'Изменить беккап';
$_lang['changepack_backup_err_ae'] = 'Беккап с таким именем уже существует.';

$_lang['changepack_backup_commitapply'] = 'Применить коммит';
$_lang['changepack_backup_backupapply'] = 'Востановить из беккапа';

$_lang['changepack_backup_err_name'] = 'Вы должны указать имя беккапа.';
$_lang['changepack_backup_err_ae'] = 'Беккап с таким именем уже существует.';
$_lang['changepack_backup_err_ns'] = 'Беккап не указан.';
$_lang['changepack_backup_err_nf'] = 'Беккап не найден.';

$_lang['changepack_backup_create'] = 'Загрузить коммит';

$_lang['changepack_grid_search'] = 'Поиск по имени или описанию';
$_lang['changepack_grid_actions'] = 'Действия';

$_lang['changepack_log_search_commit'] = 'Фильтр по коммит ID';
$_lang['changepack_log_search_last'] = 'Фильтр по флагу last';

$_lang['changepack_commit_import'] = 'Импорт';

$_lang['changepack.tab.input.desc'] = 'Загрузите json файл коммита';
$_lang['changepack.jsonfile'] = 'Загрузить файл json';
$_lang['changepack.importfailure'] = 'К сожалению, возникла ошиба при импорте.';

$_lang['changepack.log.backupfilecreate'] = 'Создаем файл беккапа.';
$_lang['changepack.log.backupsavetable'] = 'Сохраняем данные в базу.';
$_lang['changepack.log.commitnotapplybackup'] = 'Коммит не применен. Ошибка при создании беккапа. Примените коммит из таблици беккапов.';
$_lang['changepack.log.backupsaved'] = 'Беккап сохранен.';

$_lang['changepack.log.applycommit'] = 'Применение коммита.';

$_lang['changepack.log.objectsaved'] = 'Ресурс класса: [[+class]] id: [[+id]] name: [[+name]] сохранен.';
$_lang['changepack.log.objectnewsaved'] = 'Ресурс класса: [[+class]] id: [[+id]] name: [[+name]] создан.';
$_lang['changepack.log.objectremove'] = 'Ресурс класса: [[+class]] id: [[+id]] name: [[+name]] удален.';

$_lang['changepack.log.fileuploadfound'] = 'Файл: [[+filename]] загружен.';
$_lang['changepack.err.fileuploadfailed'] = 'Ошибка чтения загруженного файла.';
$_lang['changepack.err.filemovefailed'] = 'Ошибка перемешения файла.';
$_lang['changepack.err.filenotfailed'] = 'Нет файла.';
$_lang['changepack.err.objectfailed'] = 'Ресурс класса: [[+class]] id: [[+id]] name: [[+name]] не найден.';
$_lang['changepack.err.objectnewfailed'] = 'Ресурс класса: [[+class]] id: [[+id]] name: [[+name]] уже сущестует. Ресурс не обновлен.';
