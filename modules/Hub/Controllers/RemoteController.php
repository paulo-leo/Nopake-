<?php 
namespace Modules\Hub\Controllers; 

use Nopadi\Http\Request;
use Nopadi\Http\Param;
use Nopadi\MVC\Controller;
use Nopadi\Http\JWT;
use Modules\Hub\Controllers\ModulesController;
use ZipArchive;

class RemoteController extends Controller
{
    public function remote()
    {
        $content = null;
        $request = new Request;
        $https = $request->get('https');
        $branch = $request->get('branch');

        $git_url = 'https://github.com/';
        $https = strtolower($https);
        $https = str_ireplace($git_url,'',$https);
        $https = explode('/',$https);
        $https_user = $https[0];
        $https_dir = isset($https[1]) ? str_ireplace('.git','',$https[1]) : null;

        $https =  $git_url.$https_user.'/'. $https_dir.'/archive/'.$branch.'.zip';

       $file = $this->checkFile($https);
       $destino = '../modules';

      if($file)
      {
          $path_file = $destino.'/'.$branch.'.zip';
          $content .= "Arquivo baixado.\n";

          file_put_contents($path_file,$file);
          $content .= "Arquivo salvo.\n";
          $zip = new ZipArchive;
          $zip->open($path_file);
          if($zip->extractTo($destino) == TRUE)
          {
            $content .= "Arquivo extraído.\n";
          }

          $zip->close();
          unlink($path_file);
          $this->loopFile($branch);

          $module = new ModulesController;
        
          $content .= "Arquivo instalado.\n";
          $module->updateJson();
      }else
      {
        $content .= "Arquivo não acessível.\n";
      }

      return $content;

    }

    public function loopFile($branch)
    {
        $branch = '-'.$branch;
        $count = strlen($branch);
        $path = "../modules/";
        $diretorio = dir($path);
          while($arquivo = $diretorio -> read())
          {
            if(substr($arquivo,-$count) == $branch)
            {
               $new_name = str_ireplace($branch,'',$arquivo);
               @rename($path.$arquivo, $path.$new_name);
            }  
          }
        $diretorio->close();
    }


    private function checkFile($file)
    {
       $file = @file_get_contents($file);
       return $file ? $file : false;
    }

}