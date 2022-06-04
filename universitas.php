<?php
    require 'vendor/autoload.php';

    \EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
    \EasyRdf\RdfNamespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
    \EasyRdf\RdfNamespace::set('dbo', 'http://dbpedia.org/ontology/');
    \EasyRdf\RdfNamespace::set('dbp', 'http://dbpedia.org/property/');
    \EasyRdf\RdfNamespace::set('dbr', 'http://dbpedia.org/resource/');
    \EasyRdf\RdfNamespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
    \EasyRdf\RdfNamespace::set('d', 'http://learningsparql.com/ns/data#');
    \EasyRdf\RdfNamespace::setDefault('og');

    $jena_endpoint ='http://localhost:3030/data3/query';
    $sparql_jena = new \EasyRdf\Sparql\Client($jena_endpoint);
    $sparql_query = '
        SELECT ?namaUniv ?namaUnivEng ?wikiLink
        WHERE
        {
          ?univ d:namaUniv ?namaUniv;
                d:namaUnivEng ?namaUnivEng;
                d:wikiLink ?wikiLink.
        }
        ORDER BY ?namaUniv';

    $result = $sparql_jena->query($sparql_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Universitas</title>
</head>
<?php include('layouts/nav.php')?>
<body>
    <div class="container">
        <div><h1 class="page-title">Universitas</h1></div>
        <div class="row univ-list">
            <?php foreach($result as $row):
                $logo_src = \EasyRdf\Graph::newAndLoad($row->wikiLink);  
                $logo_img = $logo_src->image; ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card">
                    <img src="<?= $logo_img ?>" alt="" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title"><a href="detail_univ.php?q=<?= $row->namaUnivEng ?>"><?= $row->namaUniv ?></a></h5>
                    </div>
                </div>
            </div>
            <?php endforeach ?>
        </div>
    </div>

    <?php include('layouts/footer.php')?>
</body>
</html>