
<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal">
     Documentation
   </button>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Template generator documentation</h4>
      </div>
      <div class="modal-body">
				
		<div class='temp-doc'>
			<div >To add field to template i.e. $$template_field_name$$</div>
			<div >To add settings field to template i.e. @@template_settings_field_name@@</div>
			<div>Do not use space in fields and settings names</div>
			<br/>
			<div>
				<b>List of booked tags and their use:</b><br/>
				##template_items## => <font color='bababa'> This symbol should be added in main_html field to tell the system where do you want include the final result of item_html</font><br/>
				##main_page_title## => <font color='bababa'> The main page title which is defined in site page</font><br/>
				##template_filters## => <font color='bababa'> This symbol should be added in main_html field to tell the system the place of filters. Note: if you don't add this symbol then filters will not be displayed in your template</font><br/>
				##sub_link## => <font color='bababa'> Sub item like i.e. news details link in list all news page</font><br/>
				##site_pref## => <font color='bababa'> Site main path i.e. when you write photo path this tag must be added at the end of path</font><br/>
			</div>
			<br/>
			<div>
				<b>Widget management:</b><br/>
				
				
				<b>Simple multi widget call:</b><br/>
				##wid_start## => <font color='bababa'> widget wrapper start</font><br/>
				##ws6## => <font color='bababa'>widget start </font><br/>
				##wid_id_start##6##wid_id_end## => <font color='bababa'> define widget id i.e. here the widget id is 6</font><br/>
				##we6## => <font color='bababa'> widget end</font><br/>
				##wid_end## => <font color='bababa'> widget wrapper end</font><br/>
				
				##wid_start## => <font color='bababa'> widget wrapper start</font><br/>
				##ws4## => <font color='bababa'>widget start </font><br/>
				##wid_id_start##4##wid_id_end## => <font color='bababa'> define widget id i.e. here the widget id is 4</font><br/>
				##we4## => <font color='bababa'> widget end</font><br/>
				##wid_end## => <font color='bababa'> widget wrapper end</font><br/>
				Note: you need to include widget id with multi-widget call at the start and end of widget</br>
				
				<b>Simple main widget call:</b><br/>
				##wid_start## => <font color='bababa'> widget wrapper start</font><br/>
				##wid_id_start##72##wid_id_end## => <font color='bababa'> define widget id i.e. here the widget id is 72</font><br/>
				##wid_end## => <font color='bababa'> widget wrapper end</font><br/>
				
				<b>Simple main widget call with condition (simple):</b><br/>
				##wid_start## => <font color='bababa'> widget wrapper start</font><br/>
				##wid_id_start##72##wid_id_end## => <font color='bababa'> define widget id i.e. here the widget id is 72</font><br/>
				##wid_con_start##id=1##wid_con_end## => <font color='bababa'> Here the condition is where id = 1</font><br/>
				##wid_end## => <font color='bababa'> widget wrapper end</font><br/>
				
				
				<b>Simple main widget call with condition (Advanced):</b><br/>
				##wid_start## => <font color='bababa'> widget wrapper start</font><br/>
				##wid_id_start##72##wid_id_end## => <font color='bababa'> define widget id i.e. here the widget id is 72</font><br/>
				##wid_con_start##id in (##gallery_ids##)##wid_con_end## => <font color='bababa'> Here the condition is where id in gallery_id (gallery_id is a template field connected with module field)</font><br/>
				##wid_end## => <font color='bababa'> widget wrapper end</font><br/>
				
				
				
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>