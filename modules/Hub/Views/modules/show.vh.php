{include '@Hub/Views/header'}

{php}
extract($mod);

$status_text = $status == 'active' ? 'Desinstalar módulo' : 'Instalar módulo';

{/php}


<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">{{$status_text}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      A instalação ou desistição de um módulo poderá comprometer a segurança e a integridade de sua aplicação, pois um módulo de uma fonte desconhecida pode não atender os requisitos de sistema ou ser utilizado para objetivos obscuros. Por outro lado, a desinstalação de um módulo poderá fazer a sua aplicação parar de funcionar corretamente, pois outros módulos podem depender do mesmo. 
      </div>
      <div class="modal-footer">
        <button type="button" id="{{$key}}" class="btn btn-primary btn-save">Confirmar</button>
        <button type="button" class="btn btn-secondary btn-closex" data-bs-dismiss="modal">Cancelar</button>


        <button class="btn btn-primary btn-load" type="button" disabled style="display:none">
        <span class="spinner-border spinner-border-sm btn-load" role="status" aria-hidden="true"></span>
               Processando... Por favor, aguarde!</button>
      </div>
    </div>
  </div>
</div>


<main class="container-fluid np-scroll">

  <h1 class="visually-hidden">{{$name}}</h1>

  <div class="px-2 py-2 my-2 text-center">
    <div class="d-block mx-auto mb-2">
      <i class="material-icons" style="font-size:72px">{{$icon}}</i>
    </div>
    <h1 class="display-5 fw-bold">{{$name}}</h1>
    <div class="col-lg-12 mx-auto">
      
      <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
        {if $status == 'active'}
        <div class="spinner-grow spinner-grow-sm bg-success" role="status">
            <span class="visually-hidden">Loading...</span>
         </div>

         <button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-outline-secondary btn-lg px-4">Desinstalar</button>
        {else}
        <button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-outline-success btn-lg px-4">Instalar</button>
        {/if}

        <button type="button" onclick="window.history.back()" class="btn btn-primary btn-lg px-4">Voltar</button>

      </div>
      <hr>
      <p class="lead mb-4">{{$description}}</p>

   

  <div class="b-example-divider"></div>

<div class="card">
  <table class="table">
  <thead>
    <tr>
      <th scope="col">Campo</th>
      <th scope="col">Valor</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">Nome</th>
      <td>{{$name}}</td>
    </tr>

    <tr>
      <th scope="row">Versão</th>
      <td>{{$version}}</td>
    </tr>

    <tr>
      <th scope="row">Autor</th>
      <td>{{$author}}</td>
    </tr>

    <tr>
      <th scope="row">Licença</th>
      <td>{{$license}}</td>
    </tr>

    <tr>
      <th scope="row">Dependências</th>
      <td>
        {{is_array($require) ? implode(',',$require) : $require}}
      </td>
    </tr>

  </tbody>
</table>
</div>
</div>
  </div>

  <div class="b-example-divider mb-0"></div>
</main>

<script>
$(function(){
  $(".btn-save").click(function(){
   let id = $(this).attr('id'); 
   $.ajax({
				url:"{{url('dashboard/settings/modules')}}",		
				type:"post",
				dataType: "json",
				data:{'_token':"{{csrf_token()}}",'key':id},
				beforeSend:function(){

				   $('.btn-load').show();
               $('.btn-save').hide();
               $('.btn-close').hide();
               $('.btn-closex').hide();

				},
				success:function(data)
            {
               location.reload();
            }
			});
  });
});
</script>

{include '@Painel/Views/footer'}