<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" enctype="multipart/form-data" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">管理员设置</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require"> 用户名 </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input" name="user[user_name]"
                                           value="<?= $model['user_name'] ?>" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label form-require"> 手机号 </label>
                                <div class="am-u-sm-9">
                                    <input type="text" class="tpl-form-input" name="user[mobile]"
                                           value="<?= $model['mobile'] ?>" required>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label"> 登录密码 </label>
                                <div class="am-u-sm-9">
                                    <input type="password" class="tpl-form-input" name="user[password]"
                                           value="">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label"> 确认密码 </label>
                                <div class="am-u-sm-9">
                                    <input type="password" class="tpl-form-input" name="user[password_confirm]"
                                           value="">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">管理门店 </label>
                                <div class="am-u-sm-9">
                                    <select multiple data-am-selected name="user[shop_ids][]">
                                        <?php if (isset($shop_lists)): foreach ($shop_lists as $shop): ?>
                                      <option value="<?= $shop['id'] ?>" <?php if(in_array($shop['id'], $shop_id_arr)){echo 'selected';} ?>><?= $shop['shop_name'] ?></option>
                                     
                                      <?php endforeach; endif; ?>
                                    </select>
                                    <div class="help-block">
                                        <small>注：支持多选</small>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-form-label">角色 </label>
                                <div class="am-u-sm-9">
                                    <select multiple data-am-selected name="user[role_ids][]">
                                        <?php if (isset($role_lists)): foreach ($role_lists as $role): ?>
                                      <option value="<?= $role['id'] ?>" <?php if(in_array($role['id'], $role_id_arr)){echo 'selected';} ?>><?= $role['name'] ?></option>
                                     
                                      <?php endforeach; endif; ?>
                                    </select>
                                    <div class="help-block">
                                        <small>注：支持多选</small>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group am-form-success">
                                <label class="am-u-sm-3 am-form-label form-require">状态 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="user[enabled]" value="1" data-am-ucheck=""  <?= $model['enabled'] == 1 ? 'checked' : '' ?> class="am-ucheck-radio am-field-valid"><span class="am-ucheck-icons"><i class="am-icon-unchecked"></i><i class="am-icon-checked"></i></span>
                                        启用
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="user[enabled]" value="0" data-am-ucheck="" <?= $model['enabled'] == 0 ? 'checked' : '' ?> class="am-ucheck-radio am-field-valid"><span class="am-ucheck-icons"><i class="am-icon-unchecked"></i><i class="am-icon-checked"></i></span>
                                        禁用
                                    </label>
                                </div>
                            </div>
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
<script>
    $(function () {

        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm();

    });
</script>
