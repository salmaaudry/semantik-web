<?php
    require 'vendor/autoload.php';

    // \EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
    // \EasyRdf\RdfNamespace::set('d', 'http://learningsparql.com/ns/data#');
    
    // $jena_endpoint ='http://localhost:3030/data3/query';
    // $sparql_jena = new \EasyRdf\Sparql\Client($jena_endpoint);

    \EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
    \EasyRdf\RdfNamespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
    \EasyRdf\RdfNamespace::set('dbo', 'http://dbpedia.org/ontology/');
    \EasyRdf\RdfNamespace::set('dbp', 'http://dbpedia.org/property/');
    \EasyRdf\RdfNamespace::set('dbr', 'http://dbpedia.org/resource/');
    \EasyRdf\RdfNamespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
    \EasyRdf\RdfNamespace::set('d', 'http://learningsparql.com/ns/data#');
    \EasyRdf\RdfNamespace::setDefault('og');
    
    $sparql_jena1 = new \EasyRdf\Sparql\Client('http://dbpedia.org/sparql');
    $param = $_GET['q'];
    $sparql_query1 = '
    SELECT ?abstract ?cityName ?nama ?website ?wikiLink
    WHERE
    {
        dbr:'.$_GET['q'].'  rdfs:comment ?abstract;
                            dbo:city ?city;
                            dbp:nativeName ?nama;
                            dbp:website ?website;
                            foaf:isPrimaryTopicOf ?wikiLink.
        ?city dbp:name ?cityName.
        
        FILTER (lang(?abstract) = "en")
    } LIMIT 1 ';

    $result1 = $sparql_jena1->query($sparql_query1);

    $jena_endpoint ='http://localhost:3030/data3/query';
    $sparql_jena = new \EasyRdf\Sparql\Client($jena_endpoint);
    $sparql_query = "
        SELECT ?akreditasi ?univ ?namaUniv
        WHERE
        {
          ?univ d:namaUniv ?namaUniv;
                d:namaUnivEng '".$param."';
                d:akreditasi ?akreditasi .
        }";

        $result = $sparql_jena->query($sparql_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php foreach($result1 as $row1): ?>
    <title><?= $row1->nama ?></title>
    <?php endforeach ?>
</head>
<?php include('layouts/nav.php')?>
<body>
    <?php foreach($result1 as $row1): 
        $logo_img = \EasyRdf\Graph::newAndLoad($row1->wikiLink); ?>

        <div class="container detail-univ">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4 left">
                    <img src="<?= $logo_img->image?>" alt="">
                </div>
                <div class="col-12 col-md-6 col-lg-8 right">
                    <h3><?= $row1->nama ?></h3>
                    <h5><?= $row1->cityName ?></h5>
                    <?php foreach($result as $row) : ?>
                    <h6>Terakreditasi <?= $row->akreditasi ?></h6>
                    <?php endforeach ?>
                    <p><?= $row1->abstract ?></p>
                    <a href="<?= $row1->website ?>" class="btn-web">Kunjungi Web</a>
                    <a href="jurusan_univ.php?q=<?= $param ?>" class="btn-jurusan">Lihat jurusan</a>
                </div>
            </div>
        </div>

        
        
    <?php endforeach ?>
    <?php include('layouts/footer.php')?>
</body>
</html>