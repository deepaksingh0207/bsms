<?php ?>
<section id="content">
    <div class="container clearfix">
        <div class="row my-3">
            <div class="col-lg-2">
                <div class="sidebar">
                    <div class="sidebar-widgets-wrap">
                        <div class="widget widget_links clearfix">
                            <h4>Categories</h4>
                            <ul>
                                <li><a href="#">Junction Box</a></li>
                                <li><a href="#">Bezel</a></li>
                                <li><a href="#">Compressor</a></li>
                                <li><a href="#">Facia</a></li>
                                <li><a href="#">Frame</a></li>
                                <li><a href="#">Hinge</a></li>
                                <li><a href="#">WIP Forging Machined</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <br>
                    <a href="/bsms/dealer/addproduct/buyer">
                        <img class="login" src="/bsms/img/button/1.png" style="width: 15vw;"></a>
                    <a href="/bsms/dealer/addproduct/seller">
                        <img class="login" src="/bsms/img/button/5.png" style="width: 15vw;"></a>
            </div>
            <div class="col-lg-10">
                <h3>Request for Quotation</h3>
                <?= $this->Flash->render('auth') ?>
                <?= $this->Form->create(null, array('type' => 'file')) ?>
                <div class="card">
                    <div class="card-body" id="mulform">
                        <div class="row" id="RFQ0">
                            <div class="col-12">
                                <h5><b>RFQ. 1
                                        <hr>
                                    </b></h5>
                            </div>
                            <div class="col-4">
                                <label for="product">Product :</label>
                                <input list="0-products" name="0[product]" aria-required="true" required="required"
                                    id="product" class="form-control product" data-id="0">
                                <datalist id="0-products"></datalist>
                            </div>
                            <div class="col-4" id="0-others" style="display: none;"></div>
                            <div class="col-4">
                                <?= $this->Form->control('0.product_sub_category_id', array('required' => true, 'type' => 'text','options' => array(), 'empty' => 'Select', 'id' => 'product_sub_category_id', 'class' => 'form-control','label' => 'Sub Category')); ?>
                            </div>
                            <div class="col-4">
                                <?= $this->Form->control('0.part_name', ['required' => true, 'class' => 'form-control','maxlength' => 100]); ?>
                            </div>
                            <div class="col-4">
                                <?= $this->Form->control('0.qty', ['required' => true, 'class' => 'form-control', 'type' => 'number' ]); ?>
                            </div>
                            <div class="col-4">
                                <?= $this->Form->control('0.uom_code', array('required' => true, 'class' => 'form-control','type' => 'select','options' => $uoms,'empty' => 'Select', 'id' => 'uom', 'label' =>'UOM')); ?>
                            </div>
                            <div class="col-4">
                                <?= $this->Form->control('0.make', ['required' => true, 'class' => 'form-control','maxlength' => 100]); ?>
                            </div>
                            <div class="col-4">
                                <?= $this->Form->control('0.remarks', ['type' => 'textarea', 'class' => 'form-control','required' => true, 'escape' => false, 'rows' => '1', 'cols' => '5', 'maxlength' => 200]); ?>
                            </div>
                            <div class="col-4">
                                <label>Attachment</label>
                                <?= $this->Form->control('0.files[]', ['type' => 'file', 'class' => 'form-control','multiple' => 'multiple', 'label' => false]); ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button label="Login" class="button button-rounded button-reveal button-large button-purple"
                            type="button" onclick="addform()">
                            <i class="icon-line-plus"></i>
                            <span>ADD Form</span>
                        </button>
                        <button label="Login"
                            class="button button-rounded button-reveal button-large button-yellow button-light text-end"
                            type="submit">
                            <i class="icon-line-save"></i>
                            <span>SAVE RFQ</span>
                        </button>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</section>
<script>
    var form_ID = [0]
    var category = [<?php foreach($products as $prd): ?> '<?= h($prd) ?>',<?php endforeach; ?> "Others"]

    function addform() {
        var id = form_ID[form_ID.length - 1] + 1;
        addrow(id);
        form_ID.push(id);
        category_datalist(id);
    }

    function addrow(id) {
        $("#mulform").append(`<div class="row" id="RFQ` + (id + 1) + `">
                            <div class="col-12">
                                <h5><b><br>RFQ. `+ (id + 1) + `<hr></b></h5>
                            </div>
                            <div class="col-4">
                                <label for="`+ id + `-product">Product :</label>
                                <input list="`+ id + `-products" name="` + id + `[product]" aria-required="true" required="required" id="` + id + `-product" class="form-control product" data-id="` + id + `"><datalist id="` + id + `-products"></datalist>
                            </div>
                            <div class="col-4" id="`+ id + `-others" style="display: none;"></div>
                            <div class="col-4">
                                <div class="input text required"><label for="`+ id + `-product_sub_category_id">Sub Category</label><input type="text" name="` + id + `[product_sub_category_id]" required="required" options="" empty="Select" id="product_sub_category_id" class="form-control" aria-required="true"></div></div>
                            <div class="col-4">
                                <div class="input text required"><label for="`+ id + `-part-name">Part Name</label><input type="text" name="` + id + `[part_name]" required="required" class="form-control" maxlength="1` + id + `` + id + `" id="` + id + `-part-name" aria-required="true"></div>                            </div>
                            <div class="col-4">
                                <div class="input number required"><label for="`+ id + `-qty">Qty</label><input type="number" name="` + id + `[qty]" required="required" class="form-control" id="` + id + `-qty" aria-required="true"></div>                            </div>
                            <div class="col-4">
                                <div class="input select required"><label for="uom">UOM</label><select name="`+ id + `[uom_code]" required="required" class="form-control" id="uom"><option value="">Select</option><option value="1">BAGS</option><option value="2">BALE</option><option value="3">BUNDLES</option><option value="4">BUCKLES</option><option value="5">BILLION OF UNITS</option><option value="6">BOX</option><option value="7">BOTTLES</option><option value="8">BUNCHES</option><option value="9">CANS</option><option value="1` + id + `">CUBIC CENTIMETERS</option><option value="11">CENTIMETERS&nbsp;</option><option value="12">CUBIC METERS</option><option value="13">CARTONS</option><option value="14">DOZENS&nbsp;</option><option value="15">DRUMS</option><option value="16">GREAT GROSS</option><option value="17">GRAMMES</option><option value="18">GROSS</option><option value="19">GROSS YARDS</option><option value="2` + id + `">KILOGRAMS</option><option value="21">KILOLITRE</option><option value="22">KILOMETRE</option><option value="23">LITRES</option><option value="24">MILLI LITRES</option><option value="25">MILILITRE</option><option value="26">METERS</option><option value="27">METRIC TON</option><option value="28">NUMBERS</option><option value="29">OTHERS</option><option value="3` + id + `">PACKS</option><option value="31">PIECES</option><option value="32">PAIRS</option><option value="33">QUINTAL</option><option value="34">ROLLS</option><option value="35">SETS</option><option value="36">SQUARE FEET</option><option value="37">SQUARE METERS</option><option value="38">SQUARE YARDS</option><option value="39">TABLETS</option><option value="4` + id + `">TEN GROSS</option><option value="41">THOUSANDS</option><option value="42">TONNES</option><option value="43">TUBES</option><option value="44">US GALLONS</option><option value="45">UNITS</option><option value="46">YARDS</option></select></div>                            </div>
                            <div class="col-4">
                                <div class="input text required"><label for="`+ id + `-make">Make</label><input type="text" name="` + id + `[make]" required="required" class="form-control" maxlength="1` + id + `` + id + `" id="` + id + `-make" aria-required="true"></div>                            </div>
                            <div class="col-4">
                                <div class="input textarea required"><label for="`+ id + `-remarks">Remarks</label><textarea name="` + id + `[remarks]" class="form-control" required="required" rows="1" cols="5" maxlength="2` + id + `` + id + `" id="` + id + `-remarks" aria-required="true"></textarea></div>                            </div>
                            <div class="col-4">
                                <label>Attachment</label>
                                <div class="input file"><input type="file" name="`+ id + `[files][]" class="form-control" multiple="multiple" id="` + id + `-files"></div></div></div>
                        `);
    };

    function category_datalist(id) {
        for (var item in category) { $("#" + id + "-products").append(`<option value="` + category[item] + `"></option>`); }
    }

    category_datalist(0)
    $(document).on("change", ".product", function () {
        var value = $(this).val();
        var id = $(this).data('id');
        if (value == "Others") {
            $("#" + id + "-others").prepend(`<div class="input text required"><label for="` + id + `-other">Name Category</label><input type="text" name="` + id + `[other]" required="required" options="" empty="Select" id="other_id" class="form-control" aria-required="true"></div>`).show();
        } else { $("$" + id + "-other").empty().show(); }
    });

</script>