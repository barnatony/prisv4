<!-- Page Content -->
<div class="content content-boxed">
    <!-- Header Tiles -->
    <div class="row push-50-t">
        <div class="block">
            <div class="block-header text-right">
                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-normal">ADD</button>
            </div>
            <div class="content">
                <div class="row push-20">
                    <div class="event-calender"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Page Content -->

<!-- Modal Content -->
<div class="modal in" id="modal-normal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="block">
                <div class="block-header bg-flat">
                    <ul class="block-options">
                        <li>
                            <button data-dismiss="modal" type="button"><i class="si si-close text-white"></i></button>
                        </li>
                    </ul>
                    <h3 class="block-title text-white">Add Event</h3>
                </div>
                <div class="block-content">
                    <form class="form-horizontal" method="post" action="<?php echo myUrl('calendar/create')?>" id="add-event">
           
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material form-material-primary">
                                    <select class="form-control" id="category" name="category" size="1">
                                        <option value="">Select Category</option>
                                        <option value="HOLIDAY">Holiday</option>
                                        <option value="DUE">Due Day</option>
                                        <option value="EVENT">Event</option>
                                    </select>
                                    <label for="category">Category</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material form-material-primary floating">
                                    <input class="form-control" id="title" name="title" type="text">
                                    <label for="title">Title</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="js-datetimepicker form-material form-material-primary input-group date">
                                    <input class="form-control" id="start" name="start" placeholder="Choose a date.." data-date-format="DD/MM/YYYY" type="text">
                                    <label for="start">Start</label>
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="js-datetimepicker form-material form-material-primary input-group date">
                                    <input class="form-control" id="end" name="end" placeholder="Choose a date.." data-date-format="DD/MM/YYYY" type="text">
                                    <label for="end">End</label>
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <label class="css-input css-radio css-radio-sm css-radio-primary push-10-r">
                                    <input name="branches" value="all" type="radio"><span></span>All
                                </label>
                                <label class="css-input css-radio css-radio-sm css-radio-primary">
                                    <input name="branches" value="" type="radio"><span></span> Select Branches
                                </label>
                            </div>
                        </div>
                        <div class="form-group branch hide">
                            <div class="col-xs-12">
                                <div class="form-material form-material-primary">
                                    <select class="form-control" id="branch" name="branch[]" multiple="multiple">
                                        <?php foreach($branches as $branch){?>
                                        <option value="<?php echo $branch['branch_id']?>">
                                                <?php echo $branch['branch_name']?>
                                            </option>
                                            <?php }?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12  err-notification"></div>
                            <div class="col-xs-12 text-right">
                                <button class="btn btn-sm btn-info " type="submit">Create</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Content -->