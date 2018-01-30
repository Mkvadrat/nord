<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'u0381560_nord');

/** Имя пользователя MySQL */
define('DB_USER', 'u0381560_nord');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', '-u*8yO5xHOI{');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8mb4');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         't&bCo=n|e;TABNWm[k:m&FgJN>[8xkbz@x7bX/nN,ZU0eF*6N${^>LcT:b:v71Q$');
define('SECURE_AUTH_KEY',  'tT%;<G!HaR`C(rcr`D}8-MV.XkjxbVaQC#075u|= aZ1]L[yERk$VSefp[53}9SO');
define('LOGGED_IN_KEY',    'h-^d>vn5d4c{p{o4}TcYqL|3c@(.X7$<cJqn@%AJCR3.54^svZ=qK,;i&b,#}#DA');
define('NONCE_KEY',        '6MC<,Mt(a>_!cDs1eY!kNf9g|VaZgw.a|/`Oy5g;J^Md%b3BY&&RfV<c~(zJY5.@');
define('AUTH_SALT',        'L{w<QQyat{0b310}MIG5+.GMSechQY TWK!1AF-%B*]2R*{i4jBifC]O~6YB_M[C');
define('SECURE_AUTH_SALT', 'B</>l0Gh>6Q~..O3LBl3|aP<%;VHnk-8j:b!(LySjfEl!2lJDydNk-E@}}R5qPrL');
define('LOGGED_IN_SALT',   'G6,9P{wFKh7]AJW7(4w>Td5(),nx*uET1e?YS,Xd)+])-bbw=:[5<[FH<:)6[Yq#');
define('NONCE_SALT',       'wBDRgNhTXuLGdW+}0IEUyIZd_b2}#1t7)7JxGKEI`jLy:$`l;_c $Nz@/pZQxfST');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'ivrll_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 * 
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
//Disable File Edits
define('DISALLOW_FILE_EDIT', false);