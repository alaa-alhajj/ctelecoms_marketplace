<div class="modal fade" id="CloseTicket" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            </div>
            <div class="modal-body">
                <div class="omb_login">
                    <div class="row omb_row-sm-offset-3">
                        <div class="col-xs-12 col-sm-12">	
                            <form class="omb_loginForm" action="" autocomplete="off" method="POST">



                                <h4 class='color_ct'><?= CloseTicket ?></h4>

                                <h4>Knowledge</h4>
                                <input id="knowledg" class="rating" min="0" max="5" step="1" data-size="xs"
                                       data-symbol="&#xf005;" data-glyphicon="false" data-rating-class="rating-fa" >
                                <h4>Friendlyness</h4>
                                <input id="friend" class="rating" min="0" max="5" step="1" data-size="xs"
                                       data-symbol="&#xf005;" data-glyphicon="false" data-rating-class="rating-fa">
                                <h4>Responsevenness</h4>
                                <input id="response" class="rating" min="0" max="5" step="1" data-size="xs"
                                       data-symbol="&#xf005;" data-glyphicon="false" data-rating-class="rating-fa">
                                <h4>Overall</h4>
                                <input id="overall" class="rating" min="0" max="5" step="1" data-size="xs"
                                       data-symbol="&#xf005;" data-glyphicon="false" data-rating-class="rating-fa">





                                <span class="help-block"></span>
                                <span class="help-block"></span>
                                <div class="">
                                    <textarea class="form-control height sig form-control_black" name="comment" id="comment" placeholder="Comment"></textarea>
                                </div>
                                <span class="help-block"></span>
                                <button class="btn btn-lg btn-primary btn-block omb_btn" id="closeTicketBtn" type="button" data-loading-text="<?= Process ?> ..."><?= submit ?></button>
                            </form>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>