<div class="col">
    <div class="row">
        <div class="col-12">
            <h5>Inserir um novo Bairro</h5>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="NovoBairro">Bairro</label>
                <input type="text" class="form-control" id="NovoBairro" >
            </div>
            <button SalvarBairro type="button" class="btn btn-primary">Salvar</button>
        </div>
    </div>
</div>
<script>
    $(function(){
        $("button[SalvarBairro]").click(function(){
            cod='<?=$_GET['codigo']?>';
            municipio='<?=$_GET['municipio']?>';
            bairro=$("#NovoBairro").val();
            if(bairro){
                $.ajax({
                    url:"paginas/cadastros/eleitores/bairros/new.php",
                    type:"POST",
                    data:{
                        cod,
                        municipio,
                        bairro
                    },
                    success:function(dados){
                        JanelaAddBairro.close();
                    }
                });
            }

        });
    })
</script>