<?php
include "config_eleitores.php";

$column = [
    'nome',
    'cpf',
    'm.municipio',
    (($_SESSION['usuario']['codigo'] == 'u')?"'assessor'":false)
];

$where = false;

if($_SESSION['usuario']['codigo'] == 'u') $where = " and assessor = '{$_SESSION['usuario']['codigo']}' ";

$query = "SELECT b.*, m.municipio AS municipio, a.nome assessor FROM eleitores b "
    . "LEFT JOIN municipios m ON m.codigo = b.municipio"
    . "LEFT JOIN assessores a ON a.codigo = b.assessor"
    . "WHERE b.deletado = '0' {$where}";

$result = mysql_query($query);

if (isset($_POST["search"]["value"])) {
    $valor = trim($_POST["search"]["value"]);

    $query .= "AND (b.nome LIKE '%{$valor}%' "
        . "OR b.cpf LIKE '%{$valor}%' "
        . "OR m.municipio LIKE '%{$valor}%') ";
}

if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY b.codigo DESC ';
}

$query1 = '';

if ($_POST['length'] != -1) $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];

$result = mysql_query($query);

$number_filter_row = mysql_num_rows($result);

$result1 = mysql_query($query . $query1);

$data = [];

while ($row = mysql_fetch_array($result1)) {
    $sub_array = [];

    $btn_acoes = '<button class="btn btn-sm btn-link" url="' . $urlEleitores . '/visualizar.php?codigo=' . $row['codigo'] . '">
                        <i class="fa-regular fa-eye text-info"></i>
                   </button>';

    $btn_acoes .= '<button class="btn btn-sm btn-link" url="' . $urlEleitores . '/form.php?codigo=' . $row['codigo'] . '">
                    <i class="fa-solid fa-pencil text-warning"></i>
                </button>';

    $btn_acoes .= '<button class="btn btn-sm btn-link btn-excluir" data-codigo="' . $row['codigo'] . '">
                    <i class="fa-regular fa-trash-can text-danger"></i>
                </button>';

    $sub_array[] = $row['nome'];
    $sub_array[] = $row['cpf'];
    $sub_array[] = $row['municipio'];
    $sub_array[] = $btn_acoes;

    $data[] = $sub_array;
}

// @formatter:off
$output = [
    "draw"            => intval($_POST["draw"]),
    "recordsTotal"    => count_all_data(),
    "recordsFiltered" => $number_filter_row,
    "data"            => $data
];
// @formatter:on

echo json_encode($output);
// exit();

function count_all_data()
{
    $query = "SELECT COUNT(codigo) FROM eleitores WHERE deletado != '1'";
    $result = mysql_query($query);
    list($qtd) = mysql_fetch_row($result);
    return $qtd;
}