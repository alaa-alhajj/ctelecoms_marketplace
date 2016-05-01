<?php
include 'config.php';
include '../../common/header.php';
?>




<link href="dist/grideditor.css" rel="stylesheet" type="text/css"/>
<script src="dist/jquery.grideditor.js"></script>

<script>
    var currentColSelected;
    var fie = "Fie";
    $(function() {
        $('#myGrid').gridEditor({
            new_row_layouts: [[12], [6, 6], [9, 3], [3, 3, 3, 3], [4, 4, 4]],
            content_types: ['tinymce']
        });

        // Get resulting html
        var html = $('#myGrid').gridEditor('getHtml');

        function openFancyBox(elm) {
            elm.fancybox({
                'width': 900,
                'height': 600,
                'type': 'iframe',
                'autoScale': false, onClosed: function(e) {
                    $('#' + fie).trigger('change');
                }
            });
        }

        $("body").on("change", '#' + fie, function() {
            $val = $('#' + fie).val();

            if ($val != "" && $val !== undefined)
            {
                $valArray = $val.split("/");
                $valId = $valArray[$valArray.length - 1];
                if ($valId != "" && $valId !== undefined) {
                    currentColSelected.children(".ge-content").prepend($valId);
                }
            }
        });
        $("body").on("click", '.ge-add-image', function() {
            openFancyBox($(this));


            return false;
        });



    });
</script>
<input type="hidden" id="Fie" class="form-control" />
<div class="container" style="width:100%">
    <div id="myGrid">

        <div class="row">
            <div class="col-md-12">
                <h1>Lorem ipsum dolor sit amet, consectetur</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit exercitationem eaque aperiam rem quia quibusdam dolor ducimus quo similique eos pariatur nostrum aliquam nam eius, doloremque quis voluptatum unde. Porro voluptates aspernatur voluptate ipsam, magni vero. Accusamus, iusto tempore id!</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quae laboriosam, excepturi quas.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 dasdsa" id="eeer">
                <h2>Lorem ipsum dolor</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea facilis vel aliquam aspernatur dolor placeat totam saepe perferendis. Odio quia vel sed eveniet cupiditate, illum doloremque sint veniam eum? Corporis?</p>
            </div>
            <div class="col-md-4">
                <h2>Pariatur reprehenderit</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illo adipisci ipsa, consequuntur cum, sunt dolores veniam. Enim inventore in dolore deserunt vitae sequi nemo!</p>
            </div>
            <div class="col-md-4">
                <h2>Pariatur reprehenderit</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ea excepturi ducimus numquam aut error corporis aspernatur ipsum quos eius culpa!</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <h2>Lorem ipsum dolor.</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Porro distinctio atque molestiae optio, consequuntur? Iusto ratione cumque dolor aut dolore!</p>

                <div class="row">
                    <div class="col-md-6">
                        <hr>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis facilis molestias voluptatum laudantium fuga ratione tempora rem dolor dicta rerum vero ut, suscipit ex qui amet quam vel cupiditate quaerat minus assumenda reiciendis, similique omnis delectus! Autem, repudiandae cumque eaque?</p>
                    </div>
                    <div class="col-md-6">
                        <hr>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eveniet molestiae quaerat illum, consequuntur iusto aspernatur quam provident? Possimus!</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <h2>Lusto ratione</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quis fugit quasi officiis id laudantium error aut ut aperiam dicta saepe non vel, cupiditate illum ipsam velit deleniti natus incidunt impedit molestias dolore quos dolores enim. Aliquid ipsam eaque consequuntur quaerat, suscipit a in. Praesentium repudiandae quibusdam recusandae sequi eligendi quos, dignissimos, officiis officia minima necessitatibus eaque consequatur in id adipisci qui minus voluptatum quae debitis, quas maxime iure. Tempore vero unde quia reiciendis ad beatae voluptate omnis, ipsa expedita ab, quasi, neque. Doloribus, pariatur. Aut hic voluptate.</p>
            </div>
        </div>

    </div> <!-- /#myGrid -->
</div> <!-- /.container -->

</body>
</html>
<?php include_once '../../common/footer.php'; ?>