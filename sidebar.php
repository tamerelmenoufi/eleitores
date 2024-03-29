<ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion menus" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <!-- <i class="fas fa-laugh-wink"></i> -->
            <i class="fa-solid fa-cubes"></i>
        </div>
        <div class="sidebar-brand-text mx-3" title="Sistema de Cadastros">SC</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <?php
    if($_SESSION['usuario']['perfil'] == 'a'){
    ?>
    <!-- Nav Item - Dahboard -->
    <li class="nav-item active">
        <a class="nav-link" href="./">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">



    <li class="nav-item active">
        <a class="nav-link" href="#" url="paginas/cadastros/assessores/index.php">
        <i class="fa-solid fa-users"></i>
            <span>Assessores</span></a>
    </li>
    <?php
    }
    ?>

    <li class="nav-item active">
        <a class="nav-link" href="#" url="paginas/cadastros/eleitores/index.php">
            <i class="fa-solid fa-clipboard-list"></i>
            <span>Cadastros</span></a>
    </li>

    <li class="nav-item" >
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#relatorios"
           aria-expanded="true" aria-controls="relatorios">
           <i class="fas fa-chart-pie"></i>
            <span>Relatórios</span>
        </a>
        <div id="relatorios" class="collapse" aria-labelledby="headingUtilities"
             data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="#" url="paginas/relatorios/index.php?tipo=bairros">Bairros</a>
                <a class="collapse-item" href="#" url="paginas/relatorios/index.php?tipo=municipios">Municípios</a>
                <a class="collapse-item" href="#" url="paginas/relatorios/index.php?tipo=assessores">Assessores</a>
            </div>
        </div>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>