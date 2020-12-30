<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" enctype="multipart/form-data" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">编辑用户</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">用户昵称 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="user[nickName]"
                                           value="<?= $model['nickName'] ?>" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">真实姓名 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="user[real_name]"
                                           value="<?= $model['real_name'] ?>" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">手机号 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="user[mobile]"
                                           value="<?= $model['mobile'] ?>" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">密码 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="password" class="tpl-form-input" name="user[password]"
                                           value="" required>
                                </div>
                            </div>
                            
                            <div class="am-form-group am-form-success">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">是否启用 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="user[enabled]" value="1" data-am-ucheck=""  <?= $model['enabled'] == 1 ? 'checked' : '' ?> class="am-ucheck-radio am-field-valid"><span class="am-ucheck-icons"><i class="am-icon-unchecked"></i><i class="am-icon-checked"></i></span>
                                        是
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="user[enabled]" value="0" data-am-ucheck="" <?= $model['enabled'] == 0 ? 'checked' : '' ?> class="am-ucheck-radio am-field-valid"><span class="am-ucheck-icons"><i class="am-icon-unchecked"></i><i class="am-icon-checked"></i></span>
                                        否
                                    </label>
                                </div>
                            </div>
                            <!-- <div class="am-form-group am-form-success">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">发送短信 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="user[is_send]" value="1" data-am-ucheck=""  <?= $model['is_send'] == 1 ? 'checked' : '' ?> class="am-ucheck-radio am-field-valid"><span class="am-ucheck-icons"><i class="am-icon-unchecked"></i><i class="am-icon-checked"></i></span>
                                        是
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="user[is_send]" value="0" data-am-ucheck="" <?= $model['is_send'] == 0 ? 'checked' : '' ?> class="am-ucheck-radio am-field-valid"><span class="am-ucheck-icons"><i class="am-icon-unchecked"></i><i class="am-icon-checked"></i></span>
                                        否
                                    </label>
                                </div>
                            </div> -->
                            <div class="am-form-group">
                                <div class="am-u-sm-9 am-u-sm-push-3 am-margin-top-lg">
                                    <button type="submit" class="j-submit am-btn am-btn-secondary">提交
                                    </button>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 图片文件列表模板 -->
{{include file="layouts/_template/tpl_file_item" /}}

<!-- 文件库弹窗 -->
{{include file="layouts/_template/file_library" /}}

<script>
    $(function () {

        // 选择图片
        $('.upload-file').selectImages({
            name: 'category[image_id]'
        });

        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm();

    });
</script>
