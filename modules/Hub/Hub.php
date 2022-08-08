<?php 
namespace Modules\Hub; 

use Nopadi\MVC\Module;
use Nopadi\Http\Route;
use Nopadi\Http\Param;

class Hub extends Module
{
	private $tables = 'modules/Advert/tables.json';
	/*Este é método principal para execução do módulo enquanto ativo*/
	public function main()
  {
      
    Route::get('hub',function(){ return view('@Hub/Views/index');  });

    $modules = array(
      'get:hub/modules'=>'index',
      'get:hub/modules/form/import'=>'import',
      'get:hub/modules/form/remote'=>'remote',
      'post:hub/modules/import'=>'importModule',
      'get:hub/modules/apps'=>'apps',
      'post:hub/modules'=>'update',
      'post:hub/modules/update'=>'updateJson',
      'get:hub/modules/{key}'=>'show'
      );

      Route::controllers($modules,'@Hub/Controllers/ModulesController');

      Route::post('hub/modules/remote','@Hub/Controllers/RemoteController@remote');

  }
  
  public function on()
  {
	
  }
  
  
   public function off()
   {
	 
   }
} 
