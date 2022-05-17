<?php
    include "../../../../lib/includes.php";

    if($_POST){

        $query = "select * from bairros where municipio = '{$_POST['municipio']}' order by descricao asc";
        $result = mysql_query($query);
?>
        <option value="">::Selecione::</option>
<?php
        while($d = mysql_fetch_object($result)){
?>
        <option value="<?=$d->codigo?>"><?=$d->descricao?></option>
<?php
        }

        exit();
    }

?>