@include('@Advert/Views/header') 



@component('p_title','Categorias') 


<div class='container-fluid np-scroll mb-3'>

	@component('p_paginate',['header'=>['id|!ID','name','description'],
	'rows'=>$list,
	'options'=>[
	 'view'=>false,
	 'edit'=>true,
	 'delete'=>true]])     

</div>


@include('@Painel/Views/footer') 


