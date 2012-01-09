@echo off

@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\core.js -o  ..\build\core.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\loader.js -o  ..\build\loader.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\ajax\ajax.js -o  ..\build\ajax\ajax.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\ajax\ajaxQueue.js -o  ..\build\ajax\ajaxQueue.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\auth\auth.js -o  ..\build\auth\auth.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\auth\devlogin.js -o  ..\build\auth\devlogin.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\auth\qqlogin.js -o  ..\build\auth\qqlogin.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\dialog\dialog.js -o  ..\build\dialog\dialog.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\dom\dom.js -o  ..\build\dom\dom.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\event\event.js -o  ..\build\event\event.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\log\debug.js -o  ..\build\log\debug.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\ui\modal.js -o  ..\build\ui\modal.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\ui\scroll.js -o  ..\build\ui\scroll.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\ui\tab.js -o  ..\build\ui\tab.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\ui\tips.js -o  ..\build\ui\tips.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\utils\invite.js -o  ..\build\utils\invite.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\utils\timer.js -o  ..\build\utils\timer.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\utils\util.js -o  ..\build\utils\util.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\verifycode\verifycode.js -o  ..\build\verifycode\verifycode.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\register\register.js -o  ..\build\register\register.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\calendar\simple.js -o  ..\build\calendar\simple.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\flash\flash.js -o  ..\build\flash\flash.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\form\form.js -o  ..\build\form\form.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\form\advForm.js -o  ..\build\form\advForm.js
@java -jar yuicompressor-2.4.2.jar  --charset utf-8  ..\src\speed\mo.js -o  ..\build\speed\mo.js