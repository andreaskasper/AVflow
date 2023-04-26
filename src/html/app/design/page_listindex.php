<html>
    <head>
        <title>Video CDN Files</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="https://library.goo1.de/fontawesome/6/css/all.min.css" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="row align-items-center p-3 px-md-4 mb-3 border-bottom box-shadow" style="background: #f0f0f0;">
            <div class="col">
                <h5 class="my-0 mr-md-auto font-weight-normal">AVflow</h5>
            </div>
            <div class="col-auto">
                <a class="btn btn-outline-primary" href="#">login</a>
            </div>
        </div>

        <section class="container">
            <table class="table table-striped w-100 border">
                <thead>
                    <tr>
                        <th>Filename</th>
                        <th>Hash</th>
                        <th>Size</th>
                        <th>Modified</th>
                    </tr>
                </thead>
                <tbody>
            <?php
                $json = json_decode(file_get_contents("/data/index.json"),true);
                foreach ($json as $row) {
                    echo('<tr>');
                    echo('<td>'.html($row["filename"]).'</td>');
                    echo('<td>'.html($row["md5"]).'</td>');
                    echo('<td>'.formatBytes($row["filesize"],1).'B</td>');
                    echo('<td>'.date("Y-m-d H:i:s", $row["modified"]).'</td>');
                    echo('<td>');
                    if (file_exists("/out/".$row["md5"].".1080p.mp4")) echo('<a href="/f/h/'.$row["md5"].'.1080p.mp4" TARGET="_blank"><i class="fa-regular fa-file-video"></i></a>');
                    if (file_exists("/out/".$row["md5"].".480p.mp4")) echo('<a href="/f/h/'.$row["md5"].'.480p.mp4" TARGET="_blank"><i class="fa-regular fa-file-video"></i></a>');
                    if (file_exists("/out/".$row["md5"].".240p.mp4")) echo('<a href="/f/h/'.$row["md5"].'.240p.mp4" TARGET="_blank"><i class="fa-regular fa-file-video"></i></a>');
                    echo('</td>');
                    echo('</tr>');
                }

function formatBytes($size, $precision = 2) : string {
    $base = log($size, 1024);
    $suffixes = array('', 'K', 'M', 'G', 'T');   
    return number_format(pow(1024, $base - floor($base)), $precision,",",".") .' '. $suffixes[floor($base)];
}
            ?>
                </tbody>
            </table>
        </section>
    </body>
</html>