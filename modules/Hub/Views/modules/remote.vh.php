{include '@Hub/Views/header'}

<form id="formulario" class="row p-4" enctype="multipart/form-data" action="{{url('dashboard/settings/modules/import')}}" method="POST">
     <h1 class="center"><svg style="position:relative;top:-4px" height="32" aria-hidden="true" viewBox="0 0 16 16" version="1.1" width="32" data-view-component="true" class="octicon octicon-mark-github v-align-middle">
    <path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path>
</svg>Instalação remota via GitHub</h1>
	 
	  
	 
    {csrf_field}

	<div class="row">

   <div class="col-12">
   

   <p class="alert alert-primary">Você pode baixar e instalar um módulo direto de um repositório no GitHub, desde que este repositório seja publico. Para os demais casos, orientamos o uso da ferramenta de <a href="{{url('hub/modules/form/import')}}">importar e descompactar</a> módulo.</p>
   </div>

   <div class="col-4">
      <input class="form-control" type="text" name="https" placeholder="HTTPS do repositório" aria-label="default input example">
   </div>

   <div class="col-2">
      <input class="form-control" type="text" name="branch" placeholder="Branch" value="main" aria-label="default input example">
   </div>

    <div class="col-12">
          <div class="div-msg"></div>
    </div>

	</div>
	<div class="col-12 py-4">
	 <input type="submit" id="btn-submit" class="btn btn-primary" value="Baixar e instalar" />
	 <button class="btn btn-primary" style="display:none" id="btn-submit-load" type="button" disabled>
   <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        Baixando e instalando módulo...
   </button>
	</div>
  </form>


<div class="col m12"style="padding:30px">
   <div class="responsive-table">
     <table class="highlight" id="response"></table>
  </div>
</div>


<script>
 $(function(){
    
let progress = $("#btn-submit-load");
let btnSubmit = $("#btn-submit");
progress.hide();
$("#formulario").submit(function() {


    $.ajax({
        url: '{{url("hub/modules/remote")}}',
        type: 'POST',
        data: $(this).serialize(),
        success: function(data) {
            $(".div-msg").html("<p class='alert alert-primary my-4'>"+data+"</div>");
            $("#btn-submit-load").hide();
            $("#btn-submit").show();
        },
       beforeSend:function(){
        $("#btn-submit-load").show(); 
        $("#btn-submit").hide();
       }
    });
	
	return false;
});


     
 });
</script>

{include '@Painel/Views/footer'}