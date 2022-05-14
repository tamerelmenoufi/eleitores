<?php
include_once '../lib/includes.php';

$queryUsuarios = "(SELECT COUNT(*) FROM usuarios) AS usuarios";
$queryBeneficiado = "(SELECT COUNT(*) FROM beneficiados) AS beneficiados, ";
$queryServicos = "(SELECT COUNT(*) FROM servicos) AS servicos, ";
$queryAssessores = "(SELECT COUNT(*) FROM assessores) AS assessores, ";

$queryCount = "SELECT {$queryBeneficiado}{$queryServicos}{$queryAssessores}{$queryUsuarios}";
$dadosCount = mysql_fetch_object(mysql_query($queryCount));
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>

<!-- Content Row -->
<div class="row">

    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Usuários
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $dadosCount->usuarios; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa-solid fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Assessores
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $dadosCount->assessores; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Beneficiados
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $dadosCount->beneficiados; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa-solid fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Serviços
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $dadosCount->servicos; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fa-solid fa-user-gear fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Content Row -->
<div class="row">

    <!-- Content Column -->
    <div class="col-lg-6 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Solicitações de Serviços</h6>
            </div>
            <div class="card-body">

                <?php
                    $query = "select a.*, (select count(*) from servicos where deletado = '0') as geral,  (select count(*) from servicos where deletado = '0' and tipo = a.codigo) as quantidade from servico_tipo a where a.deletado = '0' order by a.tipo";
                    $result = mysql_query($query);
                    while($d = mysql_fetch_object($result)){
                        $pct = number_format(($d->quantidade*100)/$d->geral,0,false,false);
                ?>
                <h4 class="small font-weight-bold"><?=$d->tipo?> <span
                            class="float-right"><?=$pct?>%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-success" role="progressbar" style="width: <?=$pct?>%"
                         aria-valuenow="<?=$pct?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <?php
                    }
                ?>



                <!-- <h4 class="small font-weight-bold">Server Migration <span
                            class="float-right">20%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"
                         aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Sales Tracking <span
                            class="float-right">40%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 40%"
                         aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Customer Database <span
                            class="float-right">60%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar" role="progressbar" style="width: 60%"
                         aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Payout Details <span
                            class="float-right">80%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 80%"
                         aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <h4 class="small font-weight-bold">Account Setup <span
                            class="float-right">Complete!</span></h4>
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                         aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div> -->
            </div>
        </div>

    </div>
</div>
