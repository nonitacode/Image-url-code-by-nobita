<?php
// Imgur Image Uploader
// by BIZ FACTORY
// License: MIT License

//CONFIGURATION
define('imgur_client_id', 'b54cf411367fb00'); // your imgur client id, you can replace with your own, get it here: https://api.imgur.com/
define('timeout_request', '30'); //time out request (second)
if (!empty($_FILES) and isset($_GET['upload']) == true) {
    //get file
    $temp_file = $_FILES['file']['tmp_name'];

    //read file
    $handle = fopen($temp_file, 'r');
    $get_data = fread($handle, filesize($temp_file));

    //convert image to base64
    $imageb64 = array('image' => base64_encode($get_data));

    //intialize curl
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
    curl_setopt($curl, CURLOPT_TIMEOUT, timeout_request);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . imgur_client_id));
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $imageb64);

    //run curl
    $data_output = curl_exec($curl);
    curl_close($curl);

    //output
    $load_data = json_decode($data_output, true);
    $output = $load_data['data']['link'];

    if ($output != '') {
?>
        <div class="image__preview-wrapper">
            <p style="color:#fff">click the outside to exit</p>
            <img src="<?php echo $output; ?>" alt="">
        </div>
        <div style="text-align: left;"><br>
            <h5 style="margin-top:0;font-size:16px"><b>YOUR IMGUR LINK</h5></b>
            <h4 class="imgur_url_sel"><?php echo $output; ?></h4>
            <button class="copylink mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--colored">
                COPY
            </button>
            <button class="preview mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--colored">
                Preview
            </button>
        </div>
        <script>
            function handleCopyTextFromParagraph() {
                const cb = navigator.clipboard;
                const paragraph = document.querySelector('.imgur_url_sel');
                cb.writeText(paragraph.innerText).then(() => alert('Đã copy :v - Copied'));
            }
            $('.copylink').click(function() {
                handleCopyTextFromParagraph()
            })
            $('.preview').click(function() {
                $('.image__preview-wrapper').css('animation', 'fade 0.3s');
                $('.image__preview-wrapper img').css('animation', 'open 0.3s');
                $('.image__preview-wrapper').css('display', 'block');
            })
            $('.image__preview-wrapper').click(function() {
                $('.image__preview-wrapper').css('animation', 'fadecl 0.3s');
                $('.image__preview-wrapper img').css('animation', 'close 0.3s');
                setTimeout(function() {
                    $('.image__preview-wrapper').css('display', 'none');
                }, 280);
            })
        </script>
<?php
    } else {
        echo "Fatal Error: " . $load_data['data']['error'];
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imgur Uploader - MK DEVS</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Google+Sans">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        * {
            font-family: Google Sans !important;
            margin: 0;
            padding: 0;
        }

        .material-icons {
            font-family: Material Icons !important;
        }

        body {
            background-image: url(https://i.ibb.co/pP5zHzZ/45-452975-full-hd-1080p-cb-background.jpg);
            background-position: top center;
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
        }

        .mdl-layout__header {
            background: rgb(244, 244, 244, .7);
            -webkit-backdrop-filter: blur(20px) saturate(1.5) brightness(1.2);
            backdrop-filter: blur(20px) saturate(1.5) brightness(1.2);
        }

        .wrapper {
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            padding: 1rem;
            background: rgb(244, 244, 244, .7);
        }

        @media only screen and (max-width:700px) {
            .wrapper {
                width: 98%;
            }
        }

        .dropzone {
            background: rgb(244, 244, 244, .7);
        }

        .image__preview-wrapper {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: 999999;
            top: 0;
            left: 0;
            display: none;
            background: rgb(0, 0, 0, .7);
            -webkit-backdrop-filter: blur(20px) saturate(1.5) brightness(1.2);
            backdrop-filter: blur(20px) saturate(1.5) brightness(1.2);
        }

        .image__preview-wrapper img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 50%;
        }

        footer {
            background: rgb(244, 244, 244, .7);
            width: 100%;
            padding: 2rem;
            margin-top: 1rem;
        }

        @keyframes open {
            from {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        @keyframes close {
            to {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.9);
            }

            from {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        @keyframes fade {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadecl {
            to {
                opacity: 0;
            }

            from {
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div class="mdl-layout mdl-layout--fixed-header mdl-js-layout ">
        <header class="mdl-layout__header mdl-layout__header--scroll mdl-color-text--grey-800">
            <div class="mdl-layout__header-row">
                <span class="mdl-layout-title">Imgur Images Uploader</span>
                <div class="mdl-layout-spacer"></div>
                <a style="color:#000;margin:5px;font-size:45px" href="https://t.me/cdxmk" target="_blank">
                    <span style="font-size: 28px;" class="material-icons">facebook</span>
                </a>
            </div>
        </header>
        <div class="wrapper mdl-shadow--4dp content mdl-color-text--grey-800 mdl-cell mdl-cell--8-col">
            <div style="text-align:center">
                <h5>UPLOAD YOUR IMAGE</h5>
            </div>
            <form action="?upload" class="dropzone" id="DropzoneFrom"></form><br>
  <div align="center"> <button class="upload_file mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--colored">
                Upload now!
            </button></div>
            <div class="output"></div>
        </div>
        <footer>
        <div align="center">
               Made by : <a href="https://t.me/cdxmk" target="_blank">MK DEVS</a></p>
            <p>Unlimited Free Image Hosting </p></div>
        </footer>
    </div>
</body>
<script>
    var load_drz = new Dropzone("form#DropzoneFrom", {
        autoProcessQueue: false,
        maxFilesize: 5,
        parallelUploads: 1,
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        init: function() {
            var submitButton = document.querySelector('.upload_file');
            myDropzone = this;
            submitButton.addEventListener("click", function() {
                myDropzone.processQueue();
            });
        },
        success: function(file, response) {
            $('.output').html(response);
        },
    });
</script>

</html>