<?php

$path = $m["path"];
$g = explode("/", $path);
foreach ($g as $a) if (substr($a,0,1) == ".") die("Nicht erlaubt");
$dir = new Verzeichnis(str_replace("//","/","/in".$path."/"));


if (!empty($_REQUEST["act"])) {
    switch ($_REQUEST["act"]) {
        case "createsub":
            if(empty($_POST["name"])) die("No Foldername");
            $dir2 = $dir->createsub($_POST["name"]);
            die(json_encode(array("success" => $dir2->exists())));
        case "upload":
            copy($_FILES["file"]["tmp_name"], "/tmp/".$_POST["dzuuid"]."_".$_POST["dzchunkindex"]);
            if ($_POST["dzchunkindex"] == $_POST["dztotalchunkcount"] -1) {
                $file = new \File($dir->path().$_FILES["file"]["name"]);
                if ($file->exists()) $file->delete();
                for ($i = 0; $i < $_POST["dztotalchunkcount"]; $i++) {
                    file_put_contents($file->fullname(), file_get_contents("/tmp/".$_POST["dzuuid"]."_".$i) , FILE_APPEND);
                }
            }
            die(json_encode(array("success" => true)));
            exit();
    }



}


?><html>
    <head>
        <title>Video CDN Files</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="https://library.goo1.de/fontawesome/6/css/all.min.css" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" crossorigin="anonymous">
        <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.prod.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>

        <link rel="icon" type="image/png" href="//<?=$_SERVER["HTTP_HOST"]; ?>/skins/default/favicon_128.png" />
        <style>
            table a { color: inherit; text-decoration: none; }
        </style>

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

        <section id="vueapp3" class="container"><div class="d-none">
            <div class="px-2 py-1 mb-3 border" style="background: #f0f0f0;"><button class="btn btn-sm btn-outline-secondary" @click="showmodalnewfolder();" type="button"><i class="fa-regular fa-folder-plus"></i> new folder</button></div>
            <table id="datatable01" class="table table-striped w-100 border">
                <thead>
                    <tr>
                        <th>Filename</th>
                        <th>Hash</th>
                        <th>Size</th>
                        <th>Modified</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
            <?php
                $files = scandir($dir->path());
                $_ENV["indexer_json"] = json_decode(file_get_contents("/data/index.json"),true);
                //print_r($json);
                foreach ($files as $file) {
                    if ($file == ".") continue;
                    if ($file == "..") {
                        if ($m["path"] == "/") continue;
                        echo('<tr>');
                        echo('<td data-order="'.$file.'"><a href="/explorer'.htmlattr(removeLastFolder($m["path"])).'"><i class="fa-solid fa-folder-arrow-up"></i> folder up</a></td>');
                        echo('<td></td>');
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
                        echo('<td></td>');
                        echo('</tr>');
                        continue;
                    }
                    $f = new File($dir->path().$file);
                    if (is_dir($f->fullname())) {
                        $dir2 = new Verzeichnis($f->fullname());
                        echo('<tr>');
                        echo('<td data-order="'.$file.'"><a href="/explorer'.$path.$file.'/"><i class="fa-solid fa-folder"></i> '.html($file).'</a></td>');
                        echo('<td></td>');
                        echo('<td></td>');
                        echo('<td>'.$dir2->modified()->format("Y-m-d H:i:s").'</td>');
                        echo('<td></td>');
                        echo('<td>');
                        echo('<div class="btn-group">
                            <button type="button" class="btn btn-sm btn-link" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-regular fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><button class="dropdown-item" type="button"><i class="fa-solid fa-trash-can"></i> delete</button></li>
                            </ul>
                        </div>');
                    echo('</td>');
                        echo('</tr>');
                        continue;
                    }
                    $md5 = hashbyfile($f);
                    echo('<tr>');
                    echo('<td data-order="'.$file.'">');
                    switch (strtolower($f->extension())) {
                        case "mov": echo('<i class="fa-regular fa-file-video"></i> '); break;
                        case "mp4": echo('<i class="fa-regular fa-file-video"></i> '); break;
                        default:
                            echo('<i class="fa-regular fa-file"></i> '); break;
                    }
                    echo(html($f->name()).'</td>');
                    if (empty($md5)) echo('<td class="text-center"><i class="fa-solid fa-hourglass"></i></td>');
                    else echo('<td>'.html($md5).'</td>');
                    echo('<td data-order="'.$f->size().'" style="text-align: right;">'.formatBytes($f->size(),1).'</td>');
                    echo('<td>'.html($f->modified()->format("Y-m-d H:i:s")).'</td>');
                    echo('<td>');
                    if (!empty($md5)) {
                    $trans = array();
                    $m2 = glob('/out/'.$md5.'*');
                        foreach ($m2 as $a) {
                            if (!preg_match("@^/out/[0-9a-f]+(?P<ext>.*)@", $a, $m)) continue;
                            $trans[] = $m;
                            //echo('<a href="/f/h/'.substr($a,5,999).'" class="pr-1" TITLE="'.$m["ext"].'"><i class="fa-regular fa-file-arrow-down"></i></a>');
                        }
                    }
                    if (count($trans) > 0) {
                    echo('<div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" >Transcoded</button>
                    <ul class="dropdown-menu dropdown-menu-end">');
                     foreach ($trans as $a) echo('<li><a href="/f/h/'.substr($a[0],5,999).'" TITLE="'.$a["ext"].'." class="dropdown-item" TARGET="_blank">'.$a["ext"].'</a></li>'); 
                    echo('</ul>
                  </div>');
                    }
                    echo('</td>');
                    echo('<td>');
                    echo('<div class="btn-group">
                        <button type="button" class="btn btn-sm btn-link" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-regular fa-ellipsis-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item" type="button"><i class="fa-solid fa-trash-can"></i> delete</button></li>
                        </ul>
                    </div>');
                    echo('</td>');

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
            ?>
                </tbody>
            </table>

<?php if (true or $dir->is_writeable()) { ?>
<div class="dropzone mt-4"><div id="fileupload" style="width: 100%;">
    <div class="dz-message" data-dz-message><div class="text-center"><i class="fa-solid fa-cloud-arrow-up" style="font-size:3rem;"></i></div><div>click here or drag a file to upload</div></div>
</div></div>

<?php } ?>

<div class="modal fade" id="ModalNewFolder" ref="ModalNewFolder" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel"><i class="fa-regular fa-folder-plus"></i> Create new folder in directory</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <INPUT type="text" class="form-control" ref="FldNewFolderName" value="NewFolder"/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" @click="btncreatefolder();" class="btn btn-primary">Create Folder</button>
      </div>
    </div>
  </div>
</div>
            
            
        </div></section>
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
                    showmodalnewfolder: function() {
                        console.log("Modal show new Folder");
                        $("#ModalNewFolder").modal("show");
                    },
                    btncreatefolder: function() {

                        var name = this.$refs.FldNewFolderName.value;
                        console.log("Create New Folder", name);
                        this.roundact("createsub",{"name": name}, function() {
                            document.location.href=document.location.href;
                        });
                    },
                    roundact: function(method, atts, onsuccess, onerror) {
                        if (typeof atts == "undefined") atts = {};
                        atts["act"] = method;
                        $.post("?", atts, function(json) {
                            console.log("ACT result", json);
                            if (typeof(onsuccess) !== "undefined") onsuccess(json);
                        }, "json");
                    }
                }
                }).mount("#vueapp3");
                $(document).ready(function () {
                    $('#datatable01').DataTable({ "pageLength": 100 });
                    $("div#fileupload").dropzone({ url: "?act=upload", "maxFilesize": 99999, "chunking": true, "retryChunks": true });
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

function hashbyfile(File $file) : ?string {
    $id = $file->fullname_as_id();
    foreach ($_ENV["indexer_json"] as $row) {
        if ($row["filename"] == $id) return $row["md5"] ?? null;
    }
    return null;
}

function formatBytes($size, $precision = 2) : string {
    $base = log($size, 1024);
    $suffixes = array('', 'K', 'M', 'G', 'T');   
    return number_format(pow(1024, $base - floor($base)), $precision,",",".") .' '. $suffixes[floor($base)];
}