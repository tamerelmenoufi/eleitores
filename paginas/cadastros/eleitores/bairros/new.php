<?php
    include "../../../../lib/includes.php";

    if($_POST){

        if($_POST['cod'] and $_POST['bairro']){
            mysql_query("update bairros set descricao = '{$_POST['bairro']}' where codigo = '{$_POST['cod']}'");
        }else if($_POST['bairro']){
            mysql_query("insert into bairros set descricao = '{$_POST['bairro']}', municipio = '{$_POST['municipio']}'");
        }
        exit();
    }


    if($_GET['cod']){
        $d = mysql_fetch_object(mysql_query("select *, (select count(*) from eleitores where bairro = '{$_GET['cod']}') as qt from bairros where codigo = '{$_GET['cod']}'"));
    }

?>

<div class="col">
    <div class="row">
        <div class="col-12">
            <h5>Inserir um novo Bairro</h5>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="NovoBairro">Bairro</label>
                <input type="text" class="form-control" id="NovoBairro" value='<?=$d->descricao?>' >
            </div>
            <button SalvarBairro type="button" class="btn btn-primary">Salvar (<?=$d->qt?> Registros)</button>
        </div>
    </div>
</div>
<script>
    $(function(){
        $("button[SalvarBairro]").click(function(){
            cod='<?=$_GET['cod']?>';
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
                        $.ajax({
                            url:"paginas/cadastros/eleitores/bairros/list.php",
                            type:"POST",
                            data:{
                                municipio
                            },
                            success:function(dados){
                                $("#bairro").html(dados);
                                JanelaAddBairro.close();
                            }
                        });

                    }
                });
            }

        });
    })
</script>