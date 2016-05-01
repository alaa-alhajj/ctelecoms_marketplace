<?php
include 'config.php';
include '../../common/header.php';

$archive_id=$_REQUEST['archive_id'];
$resArr=$mailList->getReportsInfo($archive_id);
//print_r($resArr);

if($resArr['recipient_num'] != 0){
    $deliveredPer=(100*$resArr['delivered_num'])/$resArr['recipient_num'];
    $recipientPer=(100*$resArr['recipient_num'])/$resArr['recipient_num'];
    $openedPer=(100*$resArr['opened_num'])/$resArr['recipient_num'];
}else{
    $deliveredPer=0;
    $recipientPer=0;
    $openedPer=0;
}

//print_r($resArr);
?>

        <!-- Main content -->
        <section class="content2">
		
	    <div class="box box-default">
                <div class="box-header with-border">
                  <h3 class="box-title"></h3>
                
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-8">
                      <div class="chart-responsive">
                        <canvas id="pieChart" height="150"></canvas>
                      </div><!-- ./chart-responsive -->
                    </div><!-- /.col -->
                    <div class="col-md-4">
                      <ul class="chart-legend clearfix">
                        <li><i class="fa fa-circle-o text-red"></i> Number of Delivered Messages  </li>
                        <li><i class="fa fa-circle-o text-green"></i> Number of Recipient Messages </li>
                        <li><i class="fa fa-circle-o text-yellow"></i> Number of Opened Messages </li>
                      </ul>
                    </div><!-- /.col -->
                  </div><!-- /.row -->
                </div><!-- /.box-body -->
                <div class="box-footer no-padding">
                  <ul class="nav nav-pills nav-stacked">
                    <li><a href="#" data-toggle="modal" data-target="#deliveredMsgRep" >Delivered Messages <span class="pull-right text-red"><i class="fa fa-angle-up"></i><?=$deliveredPer?>%</span></a></li>
                    <li><a href="#" data-toggle="modal" data-target="#recipientMsgRep">Recipient Messages <span class="pull-right text-green"><i class="fa fa-angle-up"></i><?=$recipientPer?>%</span></a></li>
                    <li><a href="#" data-toggle="modal" data-target="#openedMsgRep">Opened Messages <span class="pull-right text-yellow"><i class="fa fa-angle-up"></i><?=$openedPer?>%</span></a></li>
                  </ul>
                </div><!-- /.footer -->
              </div><!-- /.box -->
          
        </section><!-- /.content -->

        <script>
            $(function () {
            'use strict';
            //---------------------------
            //- END MONTHLY SALES CHART -
            //---------------------------

            //-------------
            //- PIE CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
            var pieChart = new Chart(pieChartCanvas);
            var PieData = [
              {
                value: <?=$resArr['delivered_num']?>,
                color: "#f56954",
                highlight: "#f56954",
                label: "Delivered Messages"
              },
              {
                value: <?=$resArr['recipient_num']?>,
                color: "#00a65a",
                highlight: "#00a65a",
                label: "Recipient Messages"
              },
              {
                value: <?=$resArr['opened_num']?>,
                color: "#f39c12",
                highlight: "#f39c12",
                label: "Opened Messages"
              }
            ];
            var pieOptions = {
              //Boolean - Whether we should show a stroke on each segment
              segmentShowStroke: true,
              //String - The colour of each segment stroke
              segmentStrokeColor: "#fff",
              //Number - The width of each segment stroke
              segmentStrokeWidth: 1,
              //Number - The percentage of the chart that we cut out of the middle
              percentageInnerCutout: 50, // This is 0 for Pie charts
              //Number - Amount of animation steps
              animationSteps: 100,
              //String - Animation easing effect
              animationEasing: "easeOutBounce",
              //Boolean - Whether we animate the rotation of the Doughnut
              animateRotate: true,
              //Boolean - Whether we animate scaling the Doughnut from the centre
              animateScale: false,
              //Boolean - whether to make the chart responsive to window resizing
              responsive: true,
              // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
              maintainAspectRatio: false,
              //String - A legend template
              legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
              //String - A tooltip template
              tooltipTemplate: "<%=value %> <%=label%> "
            };
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            pieChart.Doughnut(PieData, pieOptions);
            //-----------------
            //- END PIE CHART -
            //-----------------
            });
        </script>
               
<!-- Modal -->
<div class="modal fade" id="deliveredMsgRep" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Delivered Messages</h4>
      </div>
      <div class="modal-body">
				
		<div class='temp-doc'>
			<div >This is List of Users who get message:</div>
			<br/>
                        
                        <?php
                        $delivered_ids=$resArr['delivered_ids'];
                        $users_Info=$mailList->getListOfUsers($delivered_ids);
                        print_r($users_Info);
                        ?>

		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
      </div>
    </div>
  </div>
</div>  

<div class="modal fade" id="recipientMsgRep" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Recipient Messages</h4>
      </div>
      <div class="modal-body">
				
		<div class='temp-doc'>
			<div >This is List of Users who recipient message:</div>
			<br/>
                        
                        <?php
                        echo $recipient_ids=$resArr['recipient_ids'];
                        $users_Info=$mailList->getListOfUsers($recipient_ids);
                        print_r($users_Info);
                        ?>

		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
      </div>
    </div>
  </div>
</div>  

<div class="modal fade" id="openedMsgRep" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Opened Messages</h4>
      </div>
      <div class="modal-body">
				
		<div class='temp-doc'>
			<div >This is List of Users who open message:</div>
			<br/>
                        
                        <?php
                        $opened_ids=$resArr['opened_ids'];
                        $users_Info=$mailList->getListOfUsers($opened_ids);
                        print_r($users_Info);
                        ?>

		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
      </div>
    </div>
  </div>
</div>  
<?php
include_once '../../common/footer.php';
?>
