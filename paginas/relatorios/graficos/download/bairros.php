<?php
    include "../../../../lib/includes.php";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=bairros.csv');

$md5 = md5(date("YmdHis") . $_SERVER["PHP_SELF"]);

$query = "SELECT bai.descricao AS descricao, COUNT(*) AS qt FROM eleitores b INNER JOIN bairros bai ON b.bairro = bai.codigo GROUP BY bai.descricao ORDER BY qt DESC, bai.descricao asc";

/*$query = "SELECT b.tipo, COUNT(*) AS qt FROM servicos a "
    . "LEFT JOIN servico_tipo b ON a.tipo = b.codigo GROUP BY a.tipo"
    . "LEFT JOIN ";*/

$result = mysql_query($query);
$i = 0;
while ($d = mysql_fetch_object($result)) {
    $rotulo[] = $d->descricao;
    $qt[] = $d->qt;
    $lg[] = $d->descricao; //$Legenda[$i];
    $bg[] = $Bg[$i];
    $bd[] = $Bd[$i];
    $i++;
}
echo "Bairro;Quantidade\n";

for ($i = 0; $i < count($lg); $i++) {
        echo "{$rotulo[$i]};{$qt[$i]}\n";
    }
?>
