<?php

namespace Modules\Advert\Models;

use Nopadi\MVC\Model;
use Nopadi\Http\Request;
use Modules\FileManager\Models\UploadModel;
use Modules\Advert\Models\CategoryModel;


class AdvertModel extends Model
{


	/*Nome da tabela*/
	protected $table = "ec_adverts";

	public function register()
	{
         $request = new Request;

		 $name = $request->getString('name','2:80');
		 $description = $request->get('description');
		 $category_id = $request->getInt('category_id');
		 $stars =  $request->get('stars');
		 $image =  $request->get('image');
		 $hour_long =  $request->getInt('hour_long');
		 $status =  $request->getBit('status');
		 $url =  $request->get('url');
		 $price =  $request->getMoney('price');


		 if($category_id == 0)
		 {
			$request->setError('category_id','Você deve selecionar uma categoria.');    
		 }

		 $request->getUnique('name',$this->getTable());

		 if($request->checkError()){
         
			$insert = $this->insert([
               'name'=>$name,
			   'category_id'=>$category_id,
			   'stars'=> $stars,
			   'image'=> $image,
			   'url'=> $url,
			   'price'=> $price,
			   'status'=>$status,
			   'hour_long'=>$hour_long,
			   'description'=>$description
			]);

           return alert("Anúncio com ID [{$insert}] criado com sucesso.",'successs');

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
		 $stars =  $request->get('stars');
		 $status =  $request->getBit('status');
		 $image =  $request->get('image');
		 $url =  $request->get('url');
		 $price =  $request->getMoney('price');
		 $hour_long =  $request->getInt('hour_long');


		 if($category_id == 0)
		 {
			$request->setError('category_id','Você deve selecionar uma categoria.');    
		 }

		 $request->getUnique('name',$this->getTable(),$id);

		 if($request->checkError()){
         
			$insert = $this->update([
               'name'=>$name,
			   'category_id'=>$category_id,
			   'stars'=> $stars,
			   'image'=> $image,
			   'url'=> $url,
			   'price'=> $price,
			   'status'=>$status,
			   'hour_long'=>$hour_long,
			   'description'=>$description
			],$id);

           return alert("Anúncio de ID [{$id}] atualizado com sucesso!",'successs');

		 }else{

			return alert($request->getErrorMessage(),'error');
		 }
	}

	
	public function getPaginate()
	{
		$request = new Request;
		$search = $request->get('search');
		$category_id = $request->getInt('category_id',0);
		$limit = $request->getInt('limit',10);

		$clicks = $request->getBool('clicks');
		$stars = $request->getBool('stars');

		$uplods = new UploadModel;

		$category_model = new CategoryModel;

		$groups = $this->select(['a.id','a.stars','a.name','a.clicks','a.hour_long','a.description','a.id as url','a.price','u.path as image'])->as('a')
		->select('c.name as category')
		->leftJoin($category_model->getTable('c'), 'c.id', 'a.category_id')
		->leftJoin($uplods->getTable('u'), 'a.image', 'u.id');

        if(strlen($search) >= 2)
		{
			$groups = $groups->where('a.name','.like.',$search);
		}

		if($category_id > 0)
		{
			$groups = $groups->where('a.category_id',$category_id);
		}

		if($clicks)
		{
			$groups = $groups->orderBy('a.clicks DESC');
		}

		if(!$clicks && $stars)
		{
			$groups = $groups->orderBy('a.stars DESC');
		}


		$groups = $groups->ipaginate($limit);

		if($groups->results)
		{
            $groups->results = np_map($groups->results, 'image', function ($image) {
				return url($image);
			 });

             $groups->results = np_map($groups->results, 'url', function ($url) {
				return url("api/ecommerce/adverts/clicks?advert_id={$url}");
			 });

		}else{
			$groups = array();
		}
       
		return $groups;
	}

	public function setClick()
	{
	   $request = new Request;
	   $id = $request->getInt('advert_id',0);
	   $r = null;

       if($id != 0)
	   {
		  $r = $this->find($id);
          if($r)
		  {
			$clicks = (int) $r->clicks;
			$clicks++;
			$this->update(['clicks'=>$clicks],$id);
		  }

	   }
       return $r;
	}
}