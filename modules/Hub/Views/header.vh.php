{php}

$sidebar = [
"text=Módulos|icon=widgets|path=hub/modules"=> 
[
  "text=Todos|link=hub/modules",
   "text=Habilitados|link=hub/modules?all:1&all:active",
   "text=Desabilitados|link=hub/modules?all:1&all:disabled",
   "text=Importador e descompactar|link=hub/modules/form/import",
   "text=Instalação remota|link=hub/modules/form/remote"
],
"text=Instalação personalizada|link=adverts/categories|icon=settings",
"text=Atualização do kernel|link=adverts/items/create|icon=developer_mode"]; 

$config_header = [
'sidebar'=>$sidebar,
'color_top'=>'primary',
'title_app'=>'Hub - Gerenciador de módulos'];

{/php}

{include '@Painel/Views/template/header',$config_header }