<?php
    include "../../../../lib/includes.php";

    if($_POST['excluir']){
        mysql_query("update bairros set deletado = '1' where codigo = '{$_POST['cod']}'");
        exit();
    }

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
            <button SalvarBairro type="button" class="btn btn-primary">Salvar</button>
            <button ExcluirBairro type="button" class="btn btn-danger">Exluir</button>
            <?=$d->qt?> Registros
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

        $("button[ExcluirBairro]").click(function(){

            $.confirm({
                content:"Deseja realmente excluir o registro do Bairro <b><?=$d->descricao?></b>?",
                title:false,
                buttons:{
                    'SIM':function(){
                        $.ajax({
                            url:"paginas/cadastros/eleitores/bairros/new.php",
                            type:"POST",
                            data:{
                                cod,
                                excluir:'1'
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
                                        $.alert('Registro excluido com sucesso!');
                                        JanelaAddBairro.close();
                                    }
                                });

                            }
                        });
                    },
                    'NÃO':function(){

                    }
                }
            })

        });


    })
</script>