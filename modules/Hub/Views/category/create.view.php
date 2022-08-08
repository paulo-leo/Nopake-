@include('@Advert/Views/header') 


@component('p_header_form',[
 'title'=>'Categoria',
 'route'=>'adverts/categories'
])  

@component('p_csrf')      

<div class="container-fluid np-scroll py-4">

  <div class="col-6">
    @component('p_input',['name'=>'name','text'=>'Nome da categoria'])  
  </div>

  <div class="col-6">
    @component('p_select',['name'=>'category_id','text'=>'Categoria mãe','select'=>$categories])  
  </div>

  <div class="col-6">
     @component('p_input',['name'=>'description','type'=>'textarea','text'=>'Descrição da categoria'])  
  </div>

</div>

@component('p_close_form')   

@include('@Painel/Views/footer')   
