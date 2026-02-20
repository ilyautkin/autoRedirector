# CLAUDE.md

## Обзор проекта

autoRedirector — расширение (extra) для MODX CMS. Автоматически отслеживает изменение URI страниц и создаёт правила 301-редиректов. При обращении к старому URL плагин перенаправляет пользователя на актуальную страницу вместо ошибки 404.

## Структура проекта

```
_build/                                  — скрипты сборки transport-пакета MODX
  resolvers/                             — пост-установочные резолверы (таблицы, чанки, меню)
  data/                                  — определения настроек, меню, плагинов, чанков
core/components/autoredirector/
  elements/plugins/plugin.autoredirector.php  — основной плагин (обработка событий MODX)
  model/autoredirector/                  — xPDO ORM-модели
    autoredirector.class.php             — базовый класс компонента
    arrule.class.php                     — модель arRule (таблица ar_rules)
    mysql/arrule.map.inc.php             — маппинг полей таблицы
  model/schema/                          — XML-схема базы данных
  processors/mgr/item/                   — CRUD-процессоры (create, get, getlist, update, remove)
  processors/mgr/items/                  — пакетные операции
  processors/mgr/resource/               — процессор списка ресурсов
  controllers/                           — контроллеры админ-панели
  lexicon/{en,ru,cs}/                    — переводы
assets/components/autoredirector/
  js/mgr/widgets/items.grid.js           — ExtJS-грид правил редиректов
  js/mgr/widgets/home.panel.js           — панель админки
  connector.php                          — точка входа для AJAX-запросов
```

## Технологии

- **PHP 5.6+** (совместимость с PHP 8)
- **MODX CMS** с **xPDO** (ORM)
- **ExtJS 3.x** — фронтенд админ-панели
- **MySQL** — таблица `ar_rules` (поля: `res_id`, `context_key`, `uri`)

## Стиль кода

### PHP

- Отступы: **табы** (не пробели)
- Классы: CamelCase — `autoRedirector`, `arRule`, `AutoredirectorItemCreateProcessor`
- Методы и свойства: camelCase — `getChunk()`, `initialize()`, `$modx`
- Приватные методы с подчёркиванием: `_getTplChunk()`
- Открывающая фигурная скобка на той же строке
- PHPDoc-комментарии для публичных методов
- Процессоры наследуют стандартные классы MODX: `modObjectCreateProcessor`, `modObjectUpdateProcessor` и т.д.
- Файл процессора возвращает имя класса в конце: `return 'ClassName';`

### JavaScript

- Пространство имён через `var autoRedirector = function(config) { ... }`
- Наследование через `Ext.extend()`
- Регистрация компонентов через `Ext.reg()`

### Локализация

- Строки в файлах `lexicon/{lang}/*.inc.php`
- Формат: `$_lang['autoredirector_key'] = 'value';`
- Поддерживаемые языки: en, ru, cs

## Архитектура (событийная модель MODX)

Плагин реагирует на системные события MODX:

| Событие | Действие |
|---------|----------|
| `OnBeforeDocFormSave` | Сохраняет старый URI в свойства ресурса |
| `OnDocFormSave` | Сравнивает URI, создаёт правило редиректа при изменении |
| `OnResourceBeforeSort` / `OnResourceSort` | Обрабатывает изменение URI при сортировке |
| `OnPageNotFound` | Ищет URI в таблице правил, выполняет 301-редирект |
| `OnManagerPageInit` | Регистрирует CSS/JS для админки |

## Сборка пакета

Сборка transport-пакета выполняется через `_build/build.transport.php`. Конфигурация в `_build/build.config.php` — содержит версию (`PKG_VERSION`), путь к MODX и флаги сборки.

## База данных

Единственная таблица `ar_rules` (класс `arRule`, наследует `xPDOSimpleObject`):

| Поле | Тип | Описание |
|------|-----|----------|
| `id` | int | Первичный ключ (автоинкремент) |
| `res_id` | int | ID ресурса MODX |
| `context_key` | varchar | Контекст (по умолчанию `web`) |
| `uri` | text | Старый URI (fulltext-индекс) |

## Нет тестов и линтеров

В проекте отсутствуют: phpunit, phpcs, composer.json, CI/CD конфигурация.
