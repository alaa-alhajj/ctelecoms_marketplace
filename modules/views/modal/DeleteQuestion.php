<div class="modal fade " id="DeleteQuestionModal" tabindex="-1" role="dialog" aria-labelledby="Delete Items">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="gridSystemModalLabel">Delete Items</h3>
            </div>
            <div class="modal-body">
                <p>Do you want to delete all items that is checked? </p>
            </div>
            <div class="modal-footer">
                <button type="button" id="NoDelete" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" id="YesDelete" class="btn btn-primary">Yes</button>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

<div class="modal fade SEOMODAL" id="SEOMODAL" tabindex="-1" role="dialog" aria-labelledby="Delete Items">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="gridSystemModalLabel">SEO</h3>
            </div>
            <div class="modal-body">
            
            </div>
            <div class="modal-footer">

            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

<div class="modal fade PaymentMODAL" id="PaymentMODAL" tabindex="-1" role="dialog" aria-labelledby="Delete Items">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="gridSystemModalLabel">Add Payment</h3>
            </div>
            <div class="modal-body">
            
            </div>
            <div class="modal-footer">

            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

<div class="modal fade ErrorModal" id="ErrorModal" tabindex="-1" role="dialog" aria-labelledby="Delete Items">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="gridSystemModalLabel">Warning</h3>
            </div>
            <div class="modal-body">
            
            </div>
            <div class="modal-footer">

            </div>
        </div><!-- /.modal-content -->
    </div>
</div>


<div class="modal fade" id="SearchModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Search Items</h4>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <input type="text" class="form-control form-search" name="SearchWord" placeholder="Search for:type,description,brand,capacity...etc" id="searchWord" >
                    <span class="input-group-btn">
                        <button class="btn btn-default" id="GoSearch" type="button">Go!</button>
                    </span>
                </div>
                <div id="resultSearch">
                </div>
            </div>           
        </div>
    </div>
</div>


<div class="modal fade TemplateModal" id="TemplateModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Message template</h4>
                  <p>Please Use  {body} tag to put your email in this template </p>
            </div>
          
            <div class="modal-body">
             
            </div>           
        </div>
    </div>
</div>