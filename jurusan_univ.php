<?php
    require 'vendor/autoload.php';

    \EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
    \EasyRdf\RdfNamespace::set('d', 'http://learningsparql.com/ns/data#');
    
    $jena_endpoint ='http://localhost:3030/data3/query';
    $sparql_jena = new \EasyRdf\Sparql\Client($jena_endpoint);
    $param = $_GET['q'];
    $sparql_query = "
        SELECT ?namaJurusan ?akreditasi ?namaFakultas ?namaUniv ?namaUnivEng
        WHERE
        {
          ?j d:jurusan ?jurusan;
             d:akreditasi ?akreditasi;
             d:fakultas ?fakultas;
             d:univ ?univ.
          ?jurusan d:namaJurusan ?namaJurusan.
          ?fakultas d:namaFakultas ?namaFakultas.
          ?univ d:namaUniv ?namaUniv;
                d:namaUnivEng '".$param."'.
        }
        ORDER BY ?namaJurusan ?namaUniv";

    $result = $sparql_jena->query($sparql_query);

    foreach($result as $row){
        $univ_name = $row->namaUniv;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Jurusan</title>
</head>
<?php include('layouts/nav.php')?>
<body>
    <div class="container text-center">
        <div><h1 class="page-title">Jurusan di <a href="detail_univ.php?q=<?= $param ?>"><?php echo($univ_name) ?></a></h1></div>
        
        <div class="table-responsive">
            <table class="table table-striped mx-auto w-auto">
                <tr>
                    <th>No</th>
                    <th>Jurusan</th>
                    <th>Akreditasi</th>
                    <th>Fakultas</th>
                </tr>
                <?php 
                    $count = 0;
                    foreach($result as $row): ?>
                <tr style="text-align:center">
                    <td><?= $count += 1 ?></td>
                    <td><?= $row->namaJurusan ?></td>
                    <td><?= $row->akreditasi ?></td>
                    <td><?= $row->namaFakultas ?></td>
                </tr>
                <?php endforeach ?>
            </table>
        </div>
    </div>
    <?php include('layouts/footer.php')?>
</body>
</html>