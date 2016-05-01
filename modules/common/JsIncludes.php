<script src="../../includes/plugins/jQueryUI/jquery-ui.min.js" type="text/javascript"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script>
    var _PREF = '<?php echo _PREF ?>';
    var _SITE = '<?php echo _SITE ?>';
    var _MODULES_FOLDER = '<?php echo MODULES_FOLDER ?>';
</script>
<!-- Bootstrap 3.3.5 -->
<script src="../../includes/bootstrap/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->


<!-- Sparkline -->
<script src="../../includes/plugins/sparkline/jquery.sparkline.min.js"></script>

<!-- jvectormap -->
<script src="../../includes/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../../includes/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

<!-- jQuery Knob Chart -->
<script src="../../includes/plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->


<!-- datepicker -->
<script src="../../includes/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->

<!-- Slimscroll -->
<script src="../../includes/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../../includes/plugins/fastclick/fastclick.min.js"></script>
<!-- Icheck App -->
<script src="../../includes/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->

<!-- AdminLTE for demo purposes -->

<script src="../../includes/plugins/timepicker/bootstrap-timepicker.min.js" type="text/javascript"></script>



<script type="text/javascript" src="../../includes/plugins/nestable/jquery.nestable.js"></script>

<!-- AdminLTE for demo purposes -->

<script type="text/javascript" src="../../includes/plugins/tinymce/tinymce.min.js"></script>
<script src="../../includes/plugins/tinymce/jquery.tinymce.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../../includes/plugins/tagit/js/tag-it.min.js"></script>
<script src="../../includes/plugins/fontawesome-iconpicker/src/js/iconpicker.js" type="text/javascript"></script>
<script src="../../includes/plugins/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js" type="text/javascript"></script>
<script src="../../includes/plugins/choosen_select/chosen.jquery.min.js" type="text/javascript"></script>
<script src="../../includes/plugins/bootstrap-waitingfor-master/build/bootstrap-waitingfor.min.js" type="text/javascript"></script>
<script src="../../includes/plugins/sweet-alert/sweetalert.min.js" type="text/javascript"></script>
<link href="../../includes/plugins/sweet-alert/sweetalert.min.css" rel="stylesheet" type="text/css"/>
<script src="../../includes/plugins/html5lightbox/html5lightbox.js" type="text/javascript"></script>
<script src="../../includes/plugins/bootstrap-notification/bootstrap-notify.min.js" type="text/javascript"></script>

<!-- Tabs -->


<script>
    $(document).ready(function() {

        var visitorsData = {
            "US": 398, //USA
            "SA": 400, //Saudi Arabia
            "CA": 1000, //Canada
            "DE": 500, //Germany
            "FR": 760, //France
            "CN": 300, //China
            "AU": 700, //Australia
            "BR": 600, //Brazil
            "IN": 800, //India
            "GB": 320, //Great Britain
            "RU": 3000 //Russia
        };
        //World map by jvectormap
        $('#world-map').vectorMap({
            map: 'world_mill_en',
            backgroundColor: "transparent",
            regionStyle: {
                initial: {
                    fill: '#e4e4e4',
                    "fill-opacity": 1,
                    stroke: 'none',
                    "stroke-width": 0,
                    "stroke-opacity": 1
                }
            },
            series: {
                regions: [{
                        values: visitorsData,
                        scale: ["#e2d0ce", "#fa6b59"],
                        normalizeFunction: 'polynomial'
                    }]
            },
            onRegionLabelShow: function(e, el, code) {
                if (typeof visitorsData[code] != "undefined")
                    el.html(el.html() + ': ' + visitorsData[code] + ' new visitors');
            }
        });
    });


</script>

<script src="../../includes/File_Manager/assets/js/jquery.easing.js" type="text/javascript" ></script>
<script src="../../includes/File_Manager/assets/js/jquery.prettyPhoto-3.1.4-W3C.js" type="text/javascript"></script>

<script src="../../includes/File_Manager/assets/js/jquery.inview.js"   type="text/javascript"></script>
<script src="../../includes/File_Manager/assets/js/jquery.parallax-1.1.3.js" type="text/javascript" ></script>
<script src="../../includes/File_Manager/assets/js/jquery.localscroll-1.2.7-min.js" type="text/javascript" ></script>
<script src="../../includes/File_Manager/assets/js/jquery.scrollTo-1.4.2-min.js" type="text/javascript" ></script>
<script src="../../includes/File_Manager/assets/fancybox/jquery.fancybox-1.3.4_patch.js" type="text/javascript" ></script>

<script src="../../includes/File_Manager/assets/js/jquery.inview.js" type="text/javascript" ></script>
<script src="../../includes/File_Manager/assets/js/fileinput.js" type="text/javascript"></script>
<script src="../../includes/File_Manager/assets/js/jquery.fitvids.min.js" type="text/javascript"></script>

