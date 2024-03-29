<?php

include "config_assessores.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $attr = [];

    $codigo = $data['codigo'] ?: null;

    unset($data['codigo'], $data['senha_2']);

    if ($codigo and empty($data['senha'])) unset($data['senha']);

    foreach ($data as $name => $value) {
        if ($name == 'senha') {
            $attr[] = "{$name} = '" . ($value) . "'";
        } else {
            $attr[] = "{$name} = '" . mysql_real_escape_string($value) . "'";
        }
    }

    $attr = implode(', ', $attr);


    $query = "UPDATE assessores SET {$attr} WHERE codigo = '{$codigo}'";

    #file_put_contents("query.txt",$query);

    if (mysql_query($query)) {
        $codigo = $codigo ?: mysql_insert_id();

        sis_logs('assessores', $codigo, $query);

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

$codigo = $_SESSION['usuario']['codigo'];

if ($codigo) {
    $query = "SELECT * FROM assessores WHERE codigo = '{$codigo}'";
    $result = mysql_query($query);
    $d = mysql_fetch_object($result);
}

?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb shadow bg-gray-custom">
        <li class="breadcrumb-item active" aria-current="page">
            Perfil - Atualização dos dados
        </li>
    </ol>
</nav>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            Cadastro do Usuário
        </h6>
    </div>
    <div class="card-body">
        <form id="form-usuarios">
            <div class="row">
                <div class="col-12">
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
                </div>
            </div>


            <div class="row">
                <div class="col-3">
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
                <div class="col-3">
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
                <div class="col-6">
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
            </div>

            <div class="row">
                <div class="col-3">
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
                <div class="col-9">
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

            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="endereco">
                            Endereco <i class="text-danger">*</i>
                        </label>
                        <input
                                type="text"
                                class="form-control"
                                id="endereco"
                                name="endereco"
                                value="<?= $d->endereco; ?>"
                                required
                        >

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="senha">Senha <i class="text-danger">*</i></label>
                        <input
                                type="password"
                                class="form-control"
                                id="senha"
                                name="senha"
                                value="<?= !$codigo ? $d->senha : ''; ?>"
                            <?= !$codigo ? 'required' : ''; ?>
                        >
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="senha_2">Confirmar senha <i class="text-danger">*</i></label>
                        <input
                                type="password"
                                class="form-control"
                                id="senha_2"
                                name="senha_2"
                            <?= !$codigo ? 'required' : ''; ?>
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

        $('#form-usuarios').validate({
            rules: {
                senha: {
                    minlength: 4
                },
                senha_2: {
                    minlength: 4,
                    equalTo: '[name="senha"]'
                }
            },
            messages: {
                senha: {
                    minlength: 'Digite minímo 4 caracteres'
                },
                senha_2: {
                    minlength: 'Digite minímo 4 caracteres',
                    equalTo: 'As senhas não conferem'
                }
            }
        });

        $("#cpf").mask("999.999.999-99");

        $("#telefone").mask("(99) 9999-9999");

        $('#municipio').selectpicker();

        $('#form-usuarios').validate();

        $('#form-usuarios').submit(function (e) {
            e.preventDefault();

            if (!$(this).valid()) return false;

            var codigo = $('#codigo').val();
            var dados = $(this).serializeArray();

            if (codigo) {
                dados.push({name: 'codigo', value: codigo})
            }

            $.ajax({
                url: '<?= $urlAssessores; ?>/perfil.php',
                method: 'POST',
                data: dados,
                success: function (response) {
                    let retorno = JSON.parse(response);

                    if (retorno.status) {
                        tata.success('Sucesso', retorno.msg);

                        window.location.href='./';

                    } else {
                        tata.error('Error', retorno.msg);
                    }
                }
            })
        });
    });
</script>



