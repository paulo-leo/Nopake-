{include '@Hub/Views/header'} 

<div class="card text-center container-fluid">
  <div class="card-header">
     Anúncios REST API
  </div>
  <div class="card-body">
    <h1 class="card-title">Anúncios</h1>
    <h4 class="card-text">Crie anúncios personalizados para o e-commerce e os distribua de forma reativa por meio de API Rest</h4>
      <br><br>
      <p>Registre a chave <b>advert_manager</b> de permissão para que determinados usuários acesse este módulo.</b>
</p>
    <a href="{{url('adverts/items')}}" class="btn btn-primary">Criar anúncio</a>
  </div>
</div>


{include '@Painel/Views/template/footer'} 