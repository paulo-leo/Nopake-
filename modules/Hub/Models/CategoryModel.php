<?php

namespace Modules\Advert\Models;

use Nopadi\MVC\Model;
use Nopadi\Http\Request;
use Modules\Advert\Models\AdvertModel; 


class CategoryModel extends Model
{


	/*Nome da tabela*/
	protected $table = "ec_categories";

	/*Retorna  as categorias para o painel admnistrativo*/
	public function getCategories()
	{
		$request = new Request;
        $limit = $request->getInt('limit',20);
		$search = $request->get('search');  
		
		return $this->ipaginate(2);
	}

	public function edit()
	{
		
	}

    /*Listar todas as categorias mães*/
	public function getList()
	{
		$list = $this->select(['id','name'])
		->where('category_id','0')
		->get();

		return array_merge(['0'=>'Sem categoria'], id_value($list));
 	}

	public function register()
	{
         $request = new Request;

		 $name = $request->getString('name','2:80');
		 $description = $request->get('description');
		 $category_id = $request->getInt('category_id');

		 $request->getUnique('name',$this->getTable());

		 if($request->checkError()){
         
			$insert = $this->insert([
               'name'=>$name,
			   'category_id'=>$category_id,
			   'description'=>$description
			]);

           return alert("Categoria com ID {$insert} criada com sucesso.",'successs');

		 }else{

			return alert($request->getErrorMessage(),'error');
		 }
	}

	public function updateRegister()
	{
         $request = new Request;

		 $id = $request->getInt('id');
		 $name = $request->getString('name','2:80');
		 $description = $request->get('description');
		 $category_id = $request->getInt('category_id');

		 $request->getUnique('name',$this->getTable(),$id);

		 if($request->checkError()){
         
			$insert = $this->update([
               'name'=>$name,
			   'category_id'=>$category_id,
			   'description'=>$description
			],$id);

           return alert("Categoria de ID {$id} foi atualizada com sucesso.",'successs');

		 }else{

			return alert($request->getErrorMessage(),'error');
		 }
	}

	public function getPaginate()
	{
		$request = new Request;
        $limit = $request->getInt('limit',10);
		$search = $request->get('search');

        $advert = new AdvertModel;

		$groups = $this->as('c')->select(['c.id as items_url','c.name','c.description',"(SELECT COUNT(*) FROM ".$advert->getTable('a')." WHERE a.category_id = c.id) as count_items"]);

        if(strlen($search) >= 2){ $groups = $groups->where('name',$search); }

		$groups = $groups->limit($limit)->get();
        
		$groups = np_map($groups, 'items_url', function ($id) {
			return url("api/ecommerce/adverts/items?category_id={$id}");
		 });

        return $groups;
	}

	

}