<div class="modal fade" id="WriteReview" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            </div>
            <div class="modal-body">
                <div class="omb_login">
                    <div class="row omb_row-sm-offset-3">
                        <div class="col-xs-12 col-sm-12">	
                            <form class="omb_loginForm"  autocomplete="off" method="POST"> 


                                <input type="hidden" name="attach" id="attach" value="">
                                <h4 class='color_ct'>Write a review</h4>

                                <div class="input-group">
                                    <input type="hidden" value="<?=$_SESSION['CUSTOMER_ID']?>" name="customer_id" id="customer_id">
                                    <input type="hidden" value="" name="product_id" id="product_sel">                                   
                                </div>                            
                                <span class="help-block"></span>
                                <div class="">
                                    <textarea class="form-control height sig form-control_black" name="review_text" id="review_text" placeholder="write your review"></textarea>
                                </div>
                                <span class="help-block"></span>
                                <button class="btn btn-lg btn-primary btn-block omb_btn" id="addReviewBtn" type="button" data-loading-text="<?= Process ?> ..."><?= submit ?></button>
                            </form>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="AlertModal" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 id='title_alert'><?=Alert?></h5>        
      </div>
      <div class="modal-body" id="Alert_Body">
      
      </div>
      
    </div>
  </div>
</div>