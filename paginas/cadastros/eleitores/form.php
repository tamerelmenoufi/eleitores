<?php
include "config_eleitores.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $attr = [];

    $codigo = $data['codigo'] ?: null;

    unset($data['codigo']);

    foreach ($data as $name => $value) {
        $attr[] = "{$name} = '" . mysql_real_escape_string($value) . "'";
    }

    if (!$codigo) {
        $attr[] = "assessor = '" . $_SESSION['usuario']['codigo'] . "'";
    }
        $attr[] = "data_cadastro = NOW()";

    $attr = implode(', ', $attr);

    if ($codigo) {
        $query = "UPDATE eleitores SET {$attr} WHERE codigo = '{$codigo}'";
    } else {
        $query = "INSERT INTO eleitores SET {$attr}";
    }

    if (mysql_query($query)) {
        $codigo = $codigo ?: mysql_insert_id();

        sis_logs('eleitores', $codigo, $query);

        echo json_encode([
            'status' => true,
            'msg' => 'Dados salvo com sucesso',
            'codigo' => $codigo,
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'msg' => 'Erro ao salvar',
            'codigo' => $codigo,
            'mysql_error' => mysql_error(),
        ]);
    }

    exit;
}

$codigo = $_GET['codigo'];

if ($codigo) {
    $query = "SELECT * FROM eleitores WHERE codigo = '{$codigo}'";
    $result = mysql_query($query);
    $d = mysql_fetch_object($result);
}

?>
<style>
    span[AddBairro]{
        cursor:pointer;
    }
</style>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb shadow bg-gray-custom">
        <li class="breadcrumb-item"><a href="#" url="content.php">Início</a></li>
        <li class="breadcrumb-item" aria-current="page">
            <a href="#" url="<?= $urlEleitores; ?>/index.php">Cadastros</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <?= $codigo ? 'Alterar' : 'Cadastrar'; ?>
        </li>
    </ol>
</nav>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <?= $codigo ? 'Alterar' : 'Novo'; ?> Cadastro
        </h6>
    </div>
    <div class="card-body">
        <form id="form-eleitores">
            <div class="form-group">
                <label for="nome">Nome <i class="text-danger">*</i></label>
                <input
                        type="text"
                        class="form-control"
                        id="nome"
                        name="nome"
                        value="<?= $d->nome; ?>"
                        required
                >
            </div>

            <div class="form-group">
                <label for="nome_mae">Nome da mãe <i class="text-danger">*</i></label>
                <input
                        type="text"
                        class="form-control"
                        id="nome_mae"
                        name="nome_mae"
                        value="<?= $d->nome_mae; ?>"
                        required
                >
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="cpf">CPF <i class="text-danger"></i></label>
                        <input
                                type="text"
                                class="form-control"
                                id="cpf"
                                name="cpf"
                                value="<?= $d->cpf; ?>"
                        >

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="data_nascimento">
                            Data de Nascimento <i class="text-danger">*</i>
                        </label>
                        <input
                                type="date"
                                class="form-control"
                                id="data_nascimento"
                                name="data_nascimento"
                                value="<?= $d->data_nascimento; ?>"
                                required
                        >

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="sexo">Sexo <i class="text-danger">*</i></label>
                        <select
                                class="form-control"
                                id="sexo"
                                name="sexo"
                                required
                        >
                            <option value=""></option>
                            <?php foreach (getSexo() as $key => $sexo) : ?>
                                <option
                                    <?= ($codigo and $d->sexo == $key) ? "selected" : ""; ?>
                                        value="<?= $key; ?>"
                                >
                                    <?= $sexo; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="email">
                            E-Mail <i class="text-danger"></i>
                        </label>
                        <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                value="<?= $d->email; ?>"

                        >

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="telefone">
                            Telefone <i class="text-danger">*</i>
                        </label>
                        <input
                                type="text"
                                class="form-control"
                                id="telefone"
                                name="telefone"
                                value="<?= $d->telefone; ?>"
                                required
                        >

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="municipio">
                            Municipio <i class="text-danger">*</i>
                        </label>
                        <select
                                class="form-control"
                                id="municipio"
                                name="municipio"
                                data-live-search="true"
                                required
                        >
                            <option value=""></option>
                            <?php
                            $query = "SELECT * FROM municipios";
                            $result = mysql_query($query);

                            while ($m = mysql_fetch_object($result)): ?>
                                <option
                                    <?= ($codigo and $d->municipio == $m->codigo) ? 'selected' : ''; ?>
                                        value="<?= $m->codigo ?>">
                                    <?= $m->municipio; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>

                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label for="bairro">
                            Bairro <i class="text-danger">*</i>
                            <span AddBairro="<?=$d->municipio?>"><i class="fa-solid fa-circle-plus"></i></span>
                        </label>
                        <select
                                class="form-control"
                                id="bairro"
                                name="bairro"
                                data-live-search="true"
                                required
                        >
                            <option value=""></option>
                            <?php
                            $query = "SELECT * FROM bairros where municipio = '{$d->municipio}' and deletado = '0'";
                            $result = mysql_query($query);

                            while ($m = mysql_fetch_object($result)): ?>
                                <option
                                    <?= ($codigo and $d->bairro == $m->codigo) ? 'selected' : ''; ?>
                                        value="<?= $m->codigo ?>">
                                    <?= $m->descricao; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label for="cpf">
                            CEP <i class="text-danger"></i>
                        </label>
                        <input
                                type="text"
                                class="form-control"
                                id="cep"
                                name="cep"
                                value="<?= $d->cep; ?>"
                        >

                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="endereco">
                    Endereco <i class="text-danger">*</i>
                </label>
                <input
                        type="text"
                        class="form-control"
                        id="endereco"
                        name="endereco"
                        value=" <?= $d->endereco; ?>"
                        required
                >

            </div>



            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="zona">
                            Zona <i class="text-danger">*</i>
                        </label>
                        <input
                                type="zona"
                                class="form-control"
                                id="zona"
                                name="zona"
                                value="<?= $d->zona; ?>"
                                required
                        >

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="secao">
                            Seção <i class="text-danger">*</i>
                        </label>
                        <input
                                type="text"
                                class="form-control"
                                id="secao"
                                name="secao"
                                value="<?= $d->secao; ?>"
                                required
                        >

                    </div>
                </div>
            </div>

            <input type="hidden" id="codigo" value="<?= $codigo; ?>">

            <button type="submit" class="btn btn-success">Salvar</button>
        </form>
    </div>
</div>

<script>
    $(function () {
        $('#cpf').mask('999.999.999-99');

        $('#cep').mask('99999-999');

        $('#telefone').mask('(99) 9 9999-9999');

        //$('#municipio').selectpicker();

        $('#form-eleitores').validate();

        $("#municipio").change(function(){
            municipio = $(this).val();
            if(municipio){
                $("span[AddBairro]").attr("AddBairro", municipio);

                $.ajax({
                    url:"paginas/cadastros/eleitores/bairros/list.php",
                    type:"POST",
                    data:{
                        municipio
                    },
                    success:function(dados){
                        $("#bairro").html(dados);
                    }
                });

            }else{
                $("span[AddBairro]").attr("AddBairro", '');
            }
        });


        $("span[AddBairro]").click(function(){
            municipio = $(this).attr("AddBairro");
            cod = $("#bairro").val();
            if(municipio){
                JanelaAddBairro = $.dialog({
                    content:"url:paginas/cadastros/eleitores/bairros/new.php?municipio="+municipio+"&cod="+cod,
                    title:false,
                    columnClass:'col-md-5'
                });
            }else{
                $.alert("Selecione um município!");
            }

        });


        $("#cep").blur(function () {
            var cep = $(this).val().replace(/\D/g, '');

            if (cep != "") {
                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;

                if (validacep.test(cep)) {
                    $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {
                        if (!("erro" in dados)) {
                            $("#endereco").val(`${dados.logradouro}, ${dados.bairro}`);
                        } //end if.
                        else {
                            $("#endereco").val("");
                        }
                    });
                }

            }
        });

        $('#form-eleitores').submit(function (e) {
            e.preventDefault();

            if (!$(this).valid()) return false;

            var codigo = $('#codigo').val();
            var dados = $(this).serializeArray();

            if (codigo) {
                dados.push({name: 'codigo', value: codigo})
            }

            $.ajax({
                url: '<?= $urlEleitores; ?>/form.php',
                method: 'POST',
                data: dados,
                success: function (response) {
                    let retorno = JSON.parse(response);

                    if (retorno.status) {
                        tata.success('Sucesso', retorno.msg);

                        $.ajax({
                            url: '<?= $urlEleitores; ?>/visualizar.php',
                            data: {codigo: retorno.codigo},
                            success: function (response) {
                                $('#palco').html(response);
                            }
                        })
                    } else {
                        tata.error('Error', retorno.msg);
                    }
                }
            })
        });
    });
</script>



