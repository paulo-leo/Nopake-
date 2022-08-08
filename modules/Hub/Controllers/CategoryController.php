<?php 
namespace Modules\Advert\Controllers; 

use Nopadi\Http\Request;
use Nopadi\Http\Param;
use Nopadi\Base\DB;
use Nopadi\MVC\Controller;
use Modules\Advert\Models\CategoryModel; 
use Nopadi\Http\JWT;
use Modules\Advert\Models\AdvertModel; 

class CategoryController extends Controller
{
    public function index()
    {
        $model = new CategoryModel;
        $list =  $model->paginate(20);
        return view('@Advert/Views/category/index',[
            'list'=>$list
        ]);
    }

    public function destroy()
    {
        $request = new Request;
        $advert_model = new AdvertModel;
        $id = $request->getInt('id');

       if(!$advert_model->exists(['category_id'=>$id]))
       {
          $model = new CategoryModel;  
          $id = $model->delete($id);
          $id = $id ? 'Categoria excluída com sucesso!' : 'Erro ao tentar excluir categoria.';
          return alert($id,'success');    
       }else{
        return alert('Essa categoria possuí itens vinculados a ela.','danger'); 
       } 
    }

    public function create()
    {
            $model = new CategoryModel;
            return view('@Advert/Views/category/create',array(
                'categories'=>$model->getList()
            ));
    }

    public function edit()
    {
            $model = new CategoryModel;

            $id = Param::int('id');

            $list =  $model->find($id);

            return view('@Advert/Views/category/edit',array(
                'categories'=>$model->getList(),
                'list'=>$list
            ));


    }

    public function update()
    {
        $model = new CategoryModel;
        return $model->updateRegister();
    }

    public function store()
    {
        $model = new CategoryModel;
        return $model->register();
    }

    public function getCategories()
    {
        $model = new CategoryModel;
        $model = $model->getPaginate();

        $jwt = new JWT;
        if($model)
        {
           $jwt->setCode(201);
           $jwt->setMessage('Listagem de categorias com sucesso.');
           return $jwt->response(['results'=>$model]);
        }else{
            $jwt->setCode(404);
            $jwt->setMessage('Não há categorias com os parâmetros fornecidos.');
            return $jwt->response();
        }

    }

}