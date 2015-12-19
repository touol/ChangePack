## ChangePack

ChangePack is a component for synchronization of resources and elements of the local copy of your site on MODx with a working copy of the site.
File language only Russian

## How to Export

First, clone this repository somewhere on your development machine:

`git clone https://github.com/touol/ChangePack.git ./`

Then, create the target directory where you want to create the file.

Then, navigate to the directory ChangePack is now in, and do this:

`git archive HEAD | (cd /path/where/I/want/my/new/repo/ && tar -xvf -)`

(Windows users can just do git archive HEAD and extract the tar file to wherever
they want.)

Then you can git init or whatever in that directory, and your files will be located
there!

## Configuration



## Information

Компонент для синхронизации ресурсов и элементов локальной копии сайта на MODx с рабочей копией сайта.
Ведёт лог изменений ресурсов и элементов с флагом последних изменений (поле last). Лог доступен в Приложения->ChangePack. 
На первой копии сайта, кнопкой "Зафиксировать изменения" в json-файл в папке "assets/components/changepack/commit" сохраняются измененные ресурсы и элементы. 
На второй копии сайта, на вкладке "Применение коммитов и беккап" этот файл можно загрузить и применить. 
Так можно, быстро, применить изменения от копии сайта разработчика на рабочий сайт. 
При загрузке, создается файл беккапа старой версии ресурсов и элементов. Им, из меню таблицы беккапов, можно, откатить изменения.

## Copyright Information

ChangePack is distributed as GPL (as MODx Revolution is), but the copyright owner
(Touol) grants all users of ChangePack the ability to modify, distribute
and use ChangePack in MODx development as they see fit, as long as attribution
is given somewhere in the distributed source of all derivative works.