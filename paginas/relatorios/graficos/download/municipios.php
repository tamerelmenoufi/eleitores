<?php
    include "../../../../lib/includes.php";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=municipios.csv');

$md5 = md5(date("YmdHis") . $_SERVER["PHP_SELF"]);

$query = "SELECT m.municipio AS descricao, COUNT(*) AS qt FROM eleitores b "
. "INNER JOIN municipios m ON b.municipio = m.codigo "
. "GROUP BY b.municipio ORDER BY qt DESC, m.municipio asc";

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
echo "Municípios;Quantidade\n";

    for ($i = 0; $i < count($lg); $i++) {
        echo "{$rotulo[$i]};{$qt[$i]}\n";
    }
    ?>
