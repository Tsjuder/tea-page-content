=== Tea Page Content ===
Contributors: Tsjuder
Tags: plugin, widget, shortcode, posts, post, pages, page, content, template, templates
Requires at least: WP 4.0, PHP 5.6
Tested up to: 4.9
Stable tag: 1.3.1
Author URI: https://github.com/Tsjuder
Plugin URI: http://tsjuder.github.io/tea-page-content/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Позволяет создавать виджет или шорткод с контентом любой записи, и кастомизировать внешний вид через шаблоны.

== Description ==

Tea Page Content - мощный плагин, позволяющий создавать блоки с любым контентом любой страницы, поста, и т.п., и кастомизировать внешний вид блоков при помощи системы шаблонов. Вы можете выбирать одну или более записей, выбирать шаблон (или создавать собственные) и отображать их при помощи виджета или шорткода. Шаблоны представляют очень гибкую систему для контроля внешнего вида создаваемых блоков. На текущий момент есть два встроенных шаблона: **default** и **bootstrap 3.x**.

= Ключевые особенности =
* Очень гибкая система шаблонов
* Поддержка всех типов постов
* Возможность создавать ваши собственные шаблоны
* Дружелюбность к пользователям и разработчикам
* Прост в использовании и обладает красивым UI

= Migration Guides =
Stay tuned with new versions. For make updates safe and fast, check changelog at <a href="https://wordpress.org/plugins/tea-page-content/changelog/">Changelog</a> tab.

Если вы нашли баг или хотите предложить что-то, пожалуйста, создайте топик на форуме или напишите мне email (raymondcostner at gmail.com).

= Documentation =
Вы можете найти основы на вкладке <a href="https://wordpress.org/plugins/tea-page-content/other_notes/">Other Notes</a>, и детали (включая описание фильтров) на <a href="http://tsjuder.github.io/tea-page-content/">github-странице</a>.

== Installation ==

1. Загрузите архив с плагином в директорию `/wp-content/plugins` и распакуйте его, или установите плагин при помощи установщика плагина Wordpress напрямую
2. Активируйте плагин на странице 'Плагины' в Wordpress
3. Настройте плагин на странице настроек (опционально)

== Screenshots ==
1. Интерфейс виджета. Щелкните по шестеренке для открытия модального окна с переменными уровня страницы
2. Окно с переменными уровня страницы (открывается по щелчку на шестеренке)
3. Окно вставки шорткода в контент записи

== Frequently Asked Questions ==

= Это еще один плагин для вывода контента страниц? =

И да, и нет. Этот плагин лежит между двумя крайностями - маленькими плагинами для небольших специфичных задач и большими фреймворками.

= Этот плагин совместим с кастомными типами постов? =

Да.

= Этот плагин совместим с моей темой? =

Да. Но каждая тема имеет свой уникальный стиль, и внешний вид виджетов \ шорткодов будет зависеть от стилей темы.

= Я не вижу ссылки на полную запись. Как я могу ее добавить? =

Ссылки на полную запись появляются автоматически - эта особенность зависит от настроек темы и ядра Wordpress. Если вы имеете специальный тэг `more` в контенте записи, или если длина записи достаточно велика, ссылка "читать далее" будет доступна. Мы не перезаписываем это поведение. Используйте нативные фильтры Wordpress для того, чтобы изменить это.

= Я нашел баг, или имею предложение. Что я могу сделать? =
Вы можете создать новый топик на форуме, или отправить мне email. Я отвечу вас так быстро, как только смогу.

== Changelog ==
= 1.3.0 =
* \! **минимально совместимая версия PHP - PHP 5.6**
* \+ устранен баг с неработающим фильтром `tpc_config_array`
* \+ добавлена возможность выбирать поле, по которому сортируем
* \+ изменение в поведении: проверка количества постов в клиентском шаблоне производится нативным образом
* \+  шаблоны bootstrap x.x внесена возможность выбирать, на основе какого breakpoint будет производиться вычисление стартовых элементов в строке
* \- удалены все deprecated (default-padded, id параметр у шорткода, thumbnail параметр)
* \* совместимость с PHP7
* \* повышена производительность
* \* исправлен баг с некорректной обработкой некоторых имен переменных уровня шаблона
* \* global refactoring

= 1.2.3 =
* \+ New template "Waterfall" added
* \+ New template "Bootstrap 4" added
* \* Checked for Wordpress 4.7 support

= 1.2.2 =
* \+ Added child themes support
* \+ Added possibility to change thumbnail size via filter tpc_thumbnail_size
* \+ Added settings page
* \* Added popup notices for long titles in UI
* \* Private, protected or draft entries not showing anymore in lists of entries
* \* Fix bug with incorrect shortcode generate
* \* Fix bug with incorrect page-level variables handling

= 1.2.1 =
* \* Fix bug with non-opening modal window of page level variables

= 1.2.0 =
* \+ New feature - page-level variables
* \+ Added button for inserting shortcode in editor
* \+ New availaibe property in templates - `caller`. Value of caller maybe `widget` or `shortcode` if template in this moment using in shortcode or widget resp.
* \+ Enclosed shortcodes availaible
* \* User Interface was improved for more usability
* \* Checked for Wordpress 4.6 support

= 1.1.1 =
* \+ Added new template-variable type "caption" that allows you describe your template
* \* Checked for Wordpress 4.5 support
* \* Improved Bootstrap template

= 1.1.0 =
* \+ Native support for all existed post types (and custom too)
* \+ Count of entries now passed in template
* \+ New feature - template-level variables
* \+ New template: Bootstrap 3.x
* \+ Added possibility hide title, content and link it. This feature depends of used template (all built-in templates except deprecated supports it)
* \- Default-Padded template, `thumbnail` widget and shortcode parameter, `id` shortcode parameter is **deprecated**
* \* CSS for frontend part changed, improved paddings, added some hover effects
* \* Global code refactoring. We are friendly for developers!
* \* Bug fixes

= 1.0.0 =
* First release with basic functionality

== Documentation ==

= Шорткоды =
Есть только один шорткод `tea_page_content`. Ниже вы видите пример с базовыми параметрами.
`[tea_page_content template="default" order="asc" posts="12,45,23"]`
Вы можете также использовать переменные уровня шаблона (см. секцию "Шаблоны" в документации) и опции.

= Параметры =
Есть несколько встроенных опций. Давайте взглянем поближе:

* **show_page_thumbnail** позволяет вам включить или отключить отображение миниатюры записи. Если вы не хотите видеть миниатюру в виджете или шорткоде, просто снимите галочку с чекбокса (для виджета). По умолчанию - **true**.
* **show_page_content** позволяет вам включить или выключить отображение контента записи. По умолчанию - **true**.
* **show_page_title** позволяет вам включить или выключить отображение заголовка записи. По умолчанию - **true**.
* **linked_page_title** позволяет вам включить или выключить кликабельность заголовка записи. Другими словами, если опция включена, заголовок будет ссылкой на запись. По умолчанию - **false**.
* **linked_page_thumbnail** позволяет вам включить или выключить кликабельность миниатюры записи. Другими словами, если опция включена, миниатюра будет ссылкой на запись. По умолчанию - **false**.
* **order** позволяет вам установить порядок записей. Все посты и страницы сортируются по дате их создания, и вы можете выбрать направление сортировки - в порядке возрастания или в порядке убывания. По умолчанию сортировка производится в **порядке убывания**.
* **template** позволяет вам выбрать лэйаут, который будет выглядеть так, как вы хотите. В шорткоде просто напишите полное имя желаемого шаблона (без расширения), например - `default` или `tpc-your-template-name`. По умолчанию - **default**.
* **posts** позволяет вам выбрать записи, которые вы хотите отобразить. В виджете просто отметьте желаемые посты, в шорткоде - перечислите их ID через запятую. Например, `posts="12,4,63"`.

= Creating simplest custom template =
По умолчанию плагин ищет пользовательские шаблоны в папке с именем "templates" в корне используемой темы. Для создания собственного шаблона просто создайте новый файл с именем `tpc-{template-name}.php`, где `template-name` - желаемое имя вашего шаблона, в указанной выше директории. Каждый шаблон должен быть назван согласно этой маске! После этого добавьте в созданный файл код шаблона. Вы можете использовать любую верстку и любые из перечисленных выше опций для создания собственных условий и разметки. Простейший пример:
`
<?php foreach ($entries as $entry) : ?>
    <div class="entry">
        <h3>
            <?php echo $entry['title'] ?>
        </h3>
        
        <div class="post-content">
            <?php echo $entry['content'] ?>
        </div>
    </div>
<?php endforeach; ?>
`
Чтобы использовать ваш шаблон в шорткоде, вам нужно написать его полное имя (но без расширения). Также вы можете выбрать шаблон в окне вставки шорткода, или использовать виджет. Пример ввода параметра вручную:
`template="tpc-my-template"`

= Параметры в шаблоне =

Выше вы могли увидеть очень простой пример пользовательского шаблона с параметрами `title` и `content`. Но это не все: здесь показан полный список параметров, которые вы можете использовать.

* **$entries** - Массив всех выбранных записей. Каждый элемент массива также является массивом, и содержит в себе:
    * **title** - Заголовок текущей записи
    * **content** - Контент текущей записи. Когда запись имеет тэг more, будет использована функция `the_content`, в противном случае - `the_excerpt`.
    * **thumbnail** - Миниатюра записи (если есть)
    * **link** - Ссылка на запись
    * **id** - ID записи
* **$count** - Количество переданных плагином записей
* **$instance** - Массив с параметрами. Включает в себя все опции, перечисленные в разделе "Опции" выше.
* **$template_variables** - Массив с переменными шаблона.
* **$caller** - Особый флаг, указывающий, откуда был вызван шаблон: из виджета или из шорткода. Может иметь значения `widget` или `shortcode`.

= Подробности & Фильтры =
Поскольку полный мануал очень длинный, вы можете увидеть его на <a href="http://tsjuder.github.io/tea-page-content/">Github-странице</a>. Также там находится информация о новых возможностях, включая фильтры, переменные уровня шаблона, переменные уровня страницы и другое.