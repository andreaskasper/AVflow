<html>
    <head>
        <title>Video CDN Files</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="https://library.goo1.de/fontawesome/6/css/all.min.css" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" crossorigin="anonymous">

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.prod.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
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
            <table id="datatable01" class="table table-striped w-100 border">
                <thead>
                    <tr>
                        <th>Filename</th>
                        <th>Hash</th>
                        <th>Size</th>
                        <th>Modified</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
            <?php
                $path = $m["path"];
                $g = explode("/", $path);
                foreach ($g as $a) if (substr($a,0,1) == ".") die("Nicht erlaubt");
                $dir = str_replace("//","/","/in".$path."/");
                $files = scandir($dir);
                //$json = json_decode(file_get_contents("/data/index.json"),true);
                foreach ($files as $file) {
                    if ($file == ".") continue;
                    if ($file == "..") {
                        if ($m["path"] == "/") continue;
                        echo('<tr>');
                        echo('<td><a href="/explorer'.htmlattr(removeLastFolder($m["path"])).'"><i class="fa-solid fa-folder-arrow-up"></i> folder up</a></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('</tr>');
                        continue;
                    }
                    if (substr($file,0,1) == ".") {
                        echo('<tr style="opacity: 0.5;">');
                        echo('<td>'.html($file).'</td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('</tr>');
                        continue;
                    }
                    $f = new File($dir.$file);
                    if (is_dir($f->fullname())) {
                        echo('<tr>');
                        echo('<td><a href="/explorer'.$path.$file.'/"><i class="fa-solid fa-folder"></i> '.html($file).'</a></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('</tr>');
                        continue;
                    }
                    echo('<tr>');
                    echo('<td>'.html($f->name()).'</td>');
                    echo('<td></td>');
                    echo('<td></td>');
                    echo('<td></td>');
                    echo('<td></td>');

                    /*echo('<td>'.html($row["md5"]).'</td>');
                    echo('<td>'.formatBytes($row["filesize"],1).'B</td>');
                    echo('<td>'.date("Y-m-d H:i:s", $row["modified"]).'</td>');
                    echo('<td>');
                    if (file_exists("/out/".$row["md5"].".1080p.mp4")) echo('<a href="/f/h/'.$row["md5"].'.1080p.mp4" TARGET="_blank"><i class="fa-regular fa-file-video"></i></a>');
                    if (file_exists("/out/".$row["md5"].".480p.mp4")) echo('<a href="/f/h/'.$row["md5"].'.480p.mp4" TARGET="_blank"><i class="fa-regular fa-file-video"></i></a>');
                    if (file_exists("/out/".$row["md5"].".240p.mp4")) echo('<a href="/f/h/'.$row["md5"].'.240p.mp4" TARGET="_blank"><i class="fa-regular fa-file-video"></i></a>');
                    echo('</td>');*/
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
        <script type="module">
            Vue.createApp({
                components: {
                    /*TodoItem*/
                },
                data() {
                    return {
                    groceryList: [
                        { id: 0, text: 'Vegetables' },
                        { id: 1, text: 'Cheese' },
                        { id: 2, text: 'Whatever else humans are supposed to eat' }
                    ]
                    }
                },
                mounted: function() {
                    this.$root.$el.classList.remove("d-none");
                    console.log("mounted vueapp2",this.me);
                },
                methods: {
                    roundact: function(method, atts, onsuccess, onerror) {
                        if (typeof atts == "undefined") atts = {};
                        atts["act"] = method;
                        $.post("?", atts, function(json) {
                            console.log("ACT result", json);
                        }, "json");
                    }
                }
                }).mount("#vueapp3");
                $(document).ready(function () {
                    $('#datatable01').DataTable();
                });
        </script>
    </body>
</html>


<?php
function removeLastFolder(string $path) : string {
    // Remove trailing slash, if present
    $path = rtrim($path, '/');

    // Explode the path by slashes
    $folders = explode('/', $path);

    // Pop the last folder from the array
    array_pop($folders);

    // Join the folders back into a path string
    $newPath = implode('/', $folders);

    return $newPath."/";
}