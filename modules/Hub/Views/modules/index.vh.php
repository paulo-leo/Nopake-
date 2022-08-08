{include '@Hub/Views/header'}

<div class="container-fluid np-scroll">

<div class="row g-4 py-4">
{in $list}

<div class="col-3">
  <div class="card">
    <div class="card-body">
       <h5 class="card-title"><i class="material-icons" style="position:relative;top:4px">{{$icon}}</i>{{$name}}</h5>
       <span style="font-size:10px" class="badge bg-secondary {{$status == 'active' ? 'bg-success' : 'bg-danger'}}">
       {{$status == 'active' ? 'Habilitado' : 'Desabilitado'}}
       </span>
       <span>{{$version}}</span>
       <p class="card-text text-truncate">{{$description}}</p>
       <hr>
        {if $status == 'active'}
        <div class="spinner-grow spinner-grow-sm bg-success" role="status">
            <span class="visually-hidden">Loading...</span>
         </div>
         {/if}

      
       <a href="{{url('hub/modules/'.$key)}}" >Gerenciar módulo</a>
    </div>
   </div> 
</div>
{/in}
</div>


</div>

{include '@Painel/Views/footer'}