{include '@Hub/Views/header'}

<form id="formulario" class="row p-4" enctype="multipart/form-data" action="{{url('dashboard/settings/modules/import')}}" method="POST">
     <h1 class="center">Importador e descompactar.</h1>
	 
	
	 
    {csrf_field}

	<div class="col-12">
	 <p class="text-danger">O arquivo de módulo deverá ser compactado no formato .ZIP <span class="badge bg-danger">Beta 0.0.2</span></p>
    <input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
    <div class="file-field input-field">
      <div class="btn">
        <input type="file" class="form-control" name="userfile" required>
      </div>
      <div class="file-path-wrapper">
        <input class="file-path validate" type="hidden">
      </div>
    </div>
	</div>
	<div class="col-12 p-4">
	 <input type="submit" id="btn-submit" class="btn btn-primary" value="Importar" />
	 <button class="btn btn-primary" style="display:none" id="btn-submit-load" type="button" disabled>
  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  Importando módulo...
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
    var formData = new FormData(this);

    $.ajax({
        url: '{{url("hub/modules/import")}}',
        type: 'POST',
        data: formData,
        success: function(data) {
             $('#response').html(data);
			 progress.hide();
			 btnSubmit.show();
        },
        cache: false,
        contentType: false,
        processData: false,
        xhr: function() { // Custom XMLHttpRequest
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) { // Avalia se tem suporte a propriedade upload
                myXhr.upload.addEventListener('progress', function() {
                    /* faz alguma coisa durante o progresso do upload */
					$('#response').html('');
					progress.show();
					btnSubmit.hide();
                }, false);
            }
            return myXhr;
        }
    });
	
	return false;
});


     
 });
</script>

{include '@Painel/Views/footer'}