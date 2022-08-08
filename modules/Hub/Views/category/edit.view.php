@include('@Advert/Views/header') 


@component('p_header_form',[
 'title'=>'Categoria',
 'route'=>'adverts/categories',
 'method'=>'PUT'
])  

@component('p_csrf')     
@component('p_hidden',['id'=>$list->id])    

<div class="container-fluid np-scroll py-4">

  <div class="col-6">
    @component('p_input',['name'=>'name','text'=>'Nome da categoria','value'=>$list->name])  
  </div>

  <div class="col-6">
    @component('p_select',['name'=>'category_id','text'=>'Categoria mãe','select'=>$categories,'value'=>$list->category_id])  
  </div>

  <div class="col-6">
     @component('p_input',['name'=>'description','type'=>'textarea','text'=>'Descrição da categoria','value'=>$list->description])  
  </div>

</div>

@component('p_close_form')   

@include('@Painel/Views/footer')   
