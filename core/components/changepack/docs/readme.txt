--------------------
ChangePack
--------------------
Author: Touol <touols@yandex.ru>
--------------------

ChangePack is a component for synchronization of resources and elements of the local copy of your site on MODx with a working copy of the site.
File language only Russian

Компонент для синхронизации ресурсов и элементов локальной копии сайта на MODx с рабочей копией сайта.
Ведёт лог изменений ресурсов и элементов с флагом последних изменений (поле last). Лог доступен в Приложения->ChangePack. 
На первой копии сайта, кнопкой "Зафиксировать изменения" в json-файл в папке "assets/components/changepack/commit" сохраняются измененные ресурсы и элементы. 
На второй копии сайта, на вкладке "Применение коммитов и беккап" этот файл можно загрузить и применить. 
Так можно, быстро, применить изменения от копии сайта разработчика на рабочий сайт. 
При загрузке, создается файл беккапа старой версии ресурсов и элементов. Им, из меню таблицы беккапов, можно, откатить изменения.

Feel free to suggest ideas/improvements/bugs on GitHub:
https://github.com/touol/ChangePack/issues