<?php
    require 'vendor/autoload.php';
    \EasyRdf\RdfNamespace::set('d', 'http://learningsparql.com/ns/data#');
    
    $jena_endpoint ='http://localhost:3030/data3/query';
    $sparql_jena = new \EasyRdf\Sparql\Client($jena_endpoint);
    if(isset($_POST['keywordbutton'])){
        $param = $_POST['keyword'];
        $sparql_query = "
        SELECT ?namaJurusan ?akreditasi ?namaFakultas ?namaUniv ?jenjang ?namaUnivEng
        WHERE
        {
          ?j d:jurusan ?jurusan;
             d:akreditasi ?akreditasi;
             d:jenjang ?jenjang;
             d:fakultas ?fakultas;
             d:univ ?univ.
          ?jurusan d:namaJurusan ?namaJurusan.
          ?fakultas d:namaFakultas ?namaFakultas.
          ?univ d:namaUniv ?namaUniv;
                d:namaUnivEng ?namaUnivEng.
          FILTER (REGEX(str(?namaJurusan), '".$param."' ,'i') || REGEX(str(?namaFakultas), '".$param."' ,'i') || REGEX(str(?namaUniv), '".$param."' ,'i'))
        }
        ORDER BY ?namaJurusan ?namaUniv";

        $result = $sparql_jena->query($sparql_query);

        $sparql_query_count = "
        SELECT (COUNT(*) as ?count)
        WHERE
        {
          ?j d:jurusan ?jurusan;
             d:fakultas ?fakultas;
             d:univ ?univ.
          ?jurusan d:namaJurusan ?namaJurusan.
          ?fakultas d:namaFakultas ?namaFakultas.
          ?univ d:namaUniv ?namaUniv.
          FILTER (REGEX(str(?namaJurusan), '".$param."' ,'i') || REGEX(str(?namaFakultas), '".$param."' ,'i') || REGEX(str(?namaUniv), '".$param."' ,'i'))
        }";
        $row_count = $sparql_jena->query($sparql_query_count);
        // SELECT (COUNT($result) as ?count)";

        // $result_row = $sparql_jena->query($sparql_query_count);

    }
    // $param = $_GET['keyword'];
    
?>

<!DOCTYPE html>
<html>
    <head>
        <title>CariJurusan!</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    </head>
    <body class="landing">
        <nav class="fixed-top bgcolor">
            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <i class="fas fa-bars" style="color:white"></i>
            </label>
            <a href="index.php"><label class="logo">CariJurusan!</label></a>
            <ul>
                <li><a href="jurusan.php">Jurusan</a></li>
                <li><a href="universitas.php">Universitas</a></li>
            </ul>
        </nav>
        <section>
            <div class="top">
                <h1>CariJurusan!</h1>
                <div class="search-box form">
                    <form action="#hasil" method="post">
                        <!-- <div class="form-group"> -->
                            <input class="search-txt" type="text" name="keyword" placeholder="Ketik untuk mencari">
                        <!-- </div>
                        <div class="text-center"> -->
                            <button type="submit" class="search-btn" name="keywordbutton" value="Cari"><i class="fas fa-search" style="color:white"></i></button>
                        <!-- </div> -->
                    </form>
                </div>
            </div>
        </section>

        <section id="hasil">
            <div class="container" data-aos="fade-up">
            <?php if(isset($_POST['keywordbutton'])){ ?>
                <div class="hasil-title">
                    <h2>Hasil Pencarian</h2>
                </div>
                <div class="row">
                    <div class="desc">
                        <?php
                            echo '<p>Hasil pencarian untuk ['.$param.']</p>';
                            foreach ($row_count as $count):
                                $data_count = $count->count;
                                if((string)$data_count == 0){
                                    echo '<p>Tidak ada hasil</p>';
                                } else {
                                    echo '<p>Total Pencarian : '.$count->count.'</p>';
                        ?>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped mx-auto w-auto">
                            <tr>
                                <th>No</th>
                                <th>Jurusan</th>
                                <th>Jenjang</th>
                                <th>Akreditasi</th>
                                <th>Fakultas</th>
                                <th>Universitas</th>
                            </tr>
                            <?php
                                $count = 0;
                                foreach ($result as $row):
                            ?>
                            <tr style="text-align:center">
                                <td><?= $count += 1 ?></td>
                                <td><?= $row->namaJurusan ?></td>
                                <td><?= $row->jenjang ?></td>
                                <td><?= $row->akreditasi ?></td>
                                <td><?= $row->namaFakultas ?></td>
                                <td><a href="detail_univ.php?q=<?= $row->namaUnivEng ?>"><?= $row->namaUniv ?></a></td>
                            </tr>
                            <?php 
                                endforeach; }?>
                            <?php endforeach; }?>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        
        <?php include('layouts/footer.php') ?>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script>
            $(window).scroll(function(){
                $('nav').toggleClass('scrolled',$(this).scrollTop()>50);
            });
        </script>
    </body>
</html>
