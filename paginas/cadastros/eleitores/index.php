<?php
include "config_eleitores.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' and $_POST['acao'] === 'excluir') {
    $codigo = $_POST['codigo'];

    if (exclusao('eleitores', $codigo)) {
        echo json_encode(["status" => true, "msg" => "Registro excluído com sucesso"]);
    } else {
        echo json_encode(["status" => false, "msg" => "Error ao tentar excluír"]);
    }
    exit;
}

$query = "SELECT b.*, m.municipio AS municipio FROM eleitores b "
    . "LEFT JOIN municipios m ON m.codigo = b.municipio "
    . "WHERE b.deletado = '0' "
    . "ORDER BY codigo desc";
$result = mysql_query($query);

?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb shadow bg-gray-custom">
        <li class="breadcrumb-item"><a href="#" url="content.php">Início</a></li>
        <li class="breadcrumb-item active" aria-current="page">Cadastros</li>
    </ol>
</nav>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            Cadastros
        </h6>
            <button type="button" class="btn btn-success btn-sm" url="paginas/cadastros/eleitores/form.php">
                <i class="fa-solid fa-plus"></i> Novo
            </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <table id="datatable" class="table" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Município</th>
                    <?=(($_SESSION['usuario']['perfil'] == 'a')?'<th>Assessores</th>':false)?>
                    <th class="mw-20">Ações</th>
                </tr>
                </thead>
                <tbody>

                </tbody>

            </table>
        </div>
    </div>
</div>

<script>
    $(function () {
        dataTable = $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "retrieve": true,
            "paging": true,
            "stateSave": true,
            "ajax": {
                url: "<?= $urlEleitores; ?>/fetch.php",
                method: "POST",
            },
            "columnDefs": [
                {
                    "targets": 3,
                    "orderable": false,
                },
            ],
        });

        $("#datatable").on("click", "tbody tr td .btn-excluir", function () {
            var codigo = $(this).data('codigo');

            $.confirm({
                title: 'Aviso',
                content: 'Deseja excluir este registro?',
                type: 'red',
                icon: 'fa fa-warning',
                buttons: {
                    sim: {
                        text: 'Sim',
                        btnClass: 'btn-red',
                        action: function () {
                            $.ajax({
                                url: '<?= $urlEleitores;?>/index.php',
                                method: 'POST',
                                data: {
                                    acao: 'excluir',
                                    codigo
                                },
                                success: function (response) {
                                    let retorno = JSON.parse(response);

                                    if (retorno.status) {
                                        tata.success('Sucesso', retorno.msg);
                                        //$(`#linha-${codigo}`).remove();
                                        $(this).parent().parent().remove();
                                    } else {
                                        tata.error('Error', retorno.msg);
                                    }

                                }
                            })
                        }
                    },
                    nao: {
                        text: 'Não'
                    }
                }
            })
        });


    });
</script>