<div class="modal fade" id="addTicketModal" tabindex="-1" role="dialog"  aria-hidden="true">
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
                                <h4 class='color_ct'>Add Ticket</h4>
                                <div class="input-group">
                                    <input type="hidden" value="<?= $_SESSION['CUSTOMER_ID'] ?>" name="customer_id" id="customer_id">                                                              
                                </div>                                     
                                <div class="input-group">
                                    <input type="text" class="form-control " name="subject" id="subject" placeholder="<?= subject ?>" />
                                </div>
                                <span class="help-block"></span>
                                <div class="">
                                    <textarea class="form-control height sig form-control_black" name="text" id="text" placeholder="text"></textarea>
                                </div>
                                <span class="help-block"></span>
                               



                                
                                <span class="help-block"></span>





                                <div class="col-sm-12"><?= max_size ?></div>
                                   <span class="help-block"></span>
                                <button class="btn btn-lg btn-primary btn-block omb_btn" id="addTicketBtn" type="button" data-loading-text="<?= Process ?> ..."><?= submit ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
