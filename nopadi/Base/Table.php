<?php

namespace Nopadi\Base;

use PDO;
use Nopadi\Base\DB;
use Nopadi\FS\Json;

class Table
{
	 private $prefix = null;
	 private $sql = null;
	 private $message = null;
	 private $tables = array();
	 private $path = null;
	 /*cria um prefixo de tabelas*/
	 public function prefix($name)
	 {
		$this->prefix = $name.'_';
	 }
	
	public function schema($db)
	{        
	        $this->path = $db;
			$db = new Json($this->path);
			$db = $db->gets();
			
			
			$sql = null;
			foreach($db as $table_name=>$values)
			{
				$this->tables[] = $table_name;
				$sql .= "CREATE TABLE IF NOT EXISTS {$table_name}(";
				
				foreach($values as $name=>$val)
				{
					$val = (array) $val;
					$type = isset($val['type']) ? $val['type'] : 'varchar';
					$size = isset($val['size']) ? $val['size'] : 30;
					$table = isset($val['table']) ? $val['table'] : null;
					$ref = isset($val['ref']) ? $val['ref'] : null;
					$on = isset($val['on']) ? $val['on'] : [];
					$default = isset($val['default']) ? $val['default'] : null;
					$null = isset($val['null']) ? (bool) $val['null'] : false;
					$negative = isset($val['negative']) ? (bool) $val['negative'] : false;
					
					$null = (!is_bool($null)) ? false : $null;
					$on = (!is_array($on)) ? array() : $on;
					$negative = (!is_bool($negative)) ? false : $negative;
					
					$args = array(
					  'type'=>$type,
					  'size'=>$size,
					  'table'=>$table,
					  'ref'=>$ref,
					  'name'=>$name,
					  'default'=>$default,
					  'on'=>$on,
					  'null'=>$null,
					  'negative'=>$negative
					);
					
				 	$sql .= "{$name} ".$this->type($args).", ";
				}
				
				$sql .= ") ENGINE=innodb DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
			}
			
			
			$sql = str_ireplace('), )','))',$sql);
			$sql = str_ireplace(', )',')',$sql);
			$this->sql = $sql;	  
	  
    }
	
    public function up() : void
    {
	   $query = DB::executeSql($this->sql);
	   if($query)
	   {   
		   $path_mod = substr($this->path,8);
		   $path_mod = explode('/',$path_mod);
		   $path_mod = $path_mod[0];
		   $path_mod = "../modules/{$path_mod}/schemas";
		   
		   if(!is_dir($path_mod))
		   {
			  mkdir($path_mod, 0777,true);
		   }
		   $path_mod = $path_mod.'/'.'SQL-'.date('Y-m-d-his').'.sql';
		   file_put_contents($path_mod,$this->sql); 
		   DB::executeSql($this->sql);
	   }
    }
   
    public function down() : void
    {
	   $tables = array_reverse($this->tables);
	   $sql = null;
       foreach($tables as $table)
	   {   
	        $table = $this->prefix.$table;
			$sql .= "DROP TABLE IF EXISTS {$table};";
	   }
	   
	   DB::executeSql($sql);
    }
   
   
   public function ons($ons) : string
   {
	   
	   
	 $sql = "";
	 
	 if(count($ons) >= 1)
	 {
	 $cascate = array(
        'delete'=>'ON DELETE CASCADE',
		'update'=>'ON UPDATE CASCADE'
	 );
     foreach($ons as $key)
	 {
		$sql .= " {$cascate[$key]}"; 
	 }
	 }
	 
     return $sql;	 
   }

	
   public function type(array $args)
   {
	   $name = $args['name'];
       $table = $args['table'];
       $size = $args['size'];	  
       $ref = $args['ref'];	
       $type = $args['type'];
	   $null = $args['null'];
	   $negative = $args['negative'];
	   $default = $args['default'];
       $on = $args['on'];
	   $null = $null ? 'NULL' : 'NOT NULL';
	   $negative = $negative ? 'SIGNED' : 'UNSIGNED';

	   $q = null;
	   
	   $type = explode(':',$type);
	   $type = $type[0];
	   $subtype = isset($type[1]) ? $type[1] : $type;
	   
	   switch($type)
	   {
		  /*Campos do tipo numerico*/
		  case "pk" : 
		      $q = "BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY({$name})";
		   break;
		   case "int" : 
		      $size = (int) $size;
			  $size = $size < 1 ? 10 : $size;
			  $q = $default ? "INTEGER({$size}) {$negative} {$null} DEFAULT {$default}" : "INTEGER({$size}) {$negative} {$null}";
		   break;
		   case "float" :
		      $size = (float) $size;
              $size = $size < 1 ? 10 : $size;
              $size = str_ireplace('.',',',$size);			  
			  $q = $default ? "FLOAT({$size}) {$negative} {$null} DEFAULT {$default}" : "FLOAT({$size}) {$negative} {$null}";
		   break;
		   /*Campos do tipo string*/
		   case "string" : 
		      $size = (int) $size;
			  $size = $size < 1 ? 1 : $size;
			  $size = $size > 255 ? 255 : $size;
		      $q = $default ? "VARCHAR({$size}) {$null} DEFAULT '{$null}'" : "VARCHAR({$size}) {$null}";
		   break;
		   
		    case "mediumtext" : 
		      $q = $default ? "MEDIUMTEXT {$null} DEFAULT '{$null}'" : "MEDIUMTEXT {$null}";
		    break;
			
			case "longtext" : 
		      $q = $default ? "LONGTEXT {$null} DEFAULT '{$null}'" : "LONGTEXT {$null}";
		    break;
		   
		   case "char" : 
		      $size = (int) $size;
			  $size = $size < 1 ? 1 : $size;
			  $size = $size > 250 ? 250 : $size;
		      $q = "CHAR({$size})";
		   break;
		   case "timestamp" : 
		      $q = "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
		   break;
		   
		    case "date" : 
		      $q = $default ? "DATE {$null} DEFAULT '{$null}'" : "DATE {$null}";
		    break;
		   
		   case "fk" :
		      $q = "BIGINT(20) UNSIGNED NOT NULL,CONSTRAINT fk_{$name} FOREIGN KEY({$name}) REFERENCES {$table}({$ref}) ".$this->ons($on);
			break;
	   }
	   
	   return $q;
   }
	
	
}