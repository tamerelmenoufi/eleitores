<?php
//error_reporting(E_ALL);
include "lib/includes.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = mysql_real_escape_string($_POST['usuario']);
    $senha = ($_POST['senha']);

    $query = "SELECT * FROM assessores WHERE cpf = '{$usuario}' AND senha = '{$senha}' LIMIT 1";
    $result = mysql_query($query);

    if (mysql_num_rows($result)) {
        $d = mysql_fetch_array($result);

        if ($d['status'] === '0') {
            echo json_encode(['status' => false, 'msg' => 'Usuário inativo']);
        } else {
            $_SESSION['usuario'] = $d;
            echo json_encode(['status' => true]);
        }

    } else {
        echo json_encode(['status' => false, 'msg' => 'Usuário ou senha incorreto']);
    }
    exit;
}

?>
<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9 mt-5">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center">
                            <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                            <lottie-player
                                    src="https://assets4.lottiefiles.com/packages/lf20_chjeeskg.json"
                                    background="transparent"
                                    speed="0.8"
                                    style="width: 350px; height: 350px;"
                                    autoplay
                            ></lottie-player>
                        </div>
                        <div class="col-lg-6">
                            <div class="p-4 p-md-5 p-lg-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-700 mb-4">Acesso ao sistema</h1>
                                </div>
                                <form class="form-login">
                                    <div class="form-group">
                                        <input
                                                type="text"
                                                class="form-control form-control-user"
                                                id="usuario"
                                                name="usuario"
                                                aria-describedby="usuario"
                                                placeholder="Usuário"
                                                autocomplete="false"
                                        >
                                    </div>
                                    <div class="form-group">
                                        <input
                                                type="password"
                                                class="form-control form-control-user"
                                                name="senha"
                                                id="senha"
                                                placeholder="senha"
                                        >
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" id="customCheck">
                                            <label class="custom-control-label" for="customCheck">
                                                Lembrar me
                                            </label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-info btn-user btn-block">
                                        Entrar
                                    </button>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="#">Esqueceu a senha?</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<script>
    $(function () {
        $(".form-login").submit(function (e) {
            e.preventDefault();

            $.ajax({
                url: 'login.php',
                data: $(this).serializeArray(),
                method: 'POST',
                success: function (response) {
                    let retorno = JSON.parse(response);

                    if (retorno.status) {
                        window.location = './';
                    } else {
                        tata.error('Aviso', retorno.msg);
                    }
                }

            })
        });
    });
</script>