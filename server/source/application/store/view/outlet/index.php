<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" method="post" novalidate="novalidate">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">我的门店</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 门店名称 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="shop[shop_name]" value="<?= $model['shop_name'] ?>" placeholder="请输入门店名称" required="">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">所属地区 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <select name="shop[region_id]"
                                            data-am-selected="{searchBox: 1, btnSize: 'sm'}">
                                        <option value="0">地区</option>
                                        <?php if (!empty($region)): foreach ($region as $val): ?>
                                            <option value="<?= $val['id'] ?>" <?= $model['region_id'] == $val['id'] ? 'selected' : '' ?>>
                                                <?= $val['name'] ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label">门店logo </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <div class="am-form-file">
                                        <button type="button"
                                                class="upload-file am-btn am-btn-secondary am-radius">
                                            <i class="am-icon-cloud-upload"></i> 选择图片
                                        </button>
                                        <div class="uploader-list am-cf">
                                            <?php if ($model['logo']): ?>
                                                <div class="file-item">
                                                    <img src="<?= $model['logo'] ?>">
                                                    <input type="hidden" name="shop[logo_id]"
                                                           value="<?= $model['logo_id'] ?>">
                                                    <i class="iconfont icon-shanchu file-item-delete"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="help-block am-margin-top-sm">
                                        <small>尺寸150x150像素以上，大小2M以下</small>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">门店图片 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <div class="am-form-file">
                                        <button type="button"
                                                class="upload-shops am-btn am-btn-secondary am-radius">
                                            <i class="am-icon-cloud-upload"></i> 选择图片
                                        </button>
                                        <div class="uploader-list am-cf">
                                            <?php foreach ($banners as $key => $item): ?>
                                                <div class="file-item">
                                                    <img src="<?= $item['img_url'] ?>">
                                                    <input type="hidden" name="shop[images][]"
                                                           value="<?= $item['image_id'] ?>">
                                                    <i class="iconfont icon-shanchu file-item-delete"></i>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="help-block am-margin-top-sm">
                                        <small>尺寸750x750像素以上，大小2M以下 (可拖拽图片调整显示顺序 )</small>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group am-padding-top">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 联系人 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="shop[linkman]" value="<?= $model['linkman'] ?>" placeholder="请输入门店联系人" required="">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 联系电话 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="shop[mobile]" value="<?= $model['mobile'] ?>" placeholder="请输入门店联系电话" required="">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 营业时间 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="shop[shop_hours]" placeholder="请输入门店营业时间" value="<?= $model['shop_hours'] ?>" required="">
                                    <small>例如：8:30-17:30</small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 详细地址 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="shop[address]" placeholder="请输入详细地址" value="<?= $model['address'] ?>" required="">
                                </div>
                            </div>

                            <div class="am-form-group am-padding-top">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label"> 门店简介 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <textarea class="am-field-valid" rows="5" placeholder="请输入门店简介" name="shop[summary]"><?= $model['summary'] ?></textarea>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">门店排序 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="number" class="tpl-form-input" name="shop[sort]" value="<?= $model['sort'] ?>" required="" pattern="^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$">
                                    <small>数字越小越靠前</small>
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require"> 门店状态 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <label class="am-radio-inline">
                                        <input type="radio" name="shop[enabled]" value="1" data-am-ucheck="" <?= $model['enabled'] == 1 ? 'checked' : '' ?> class="am-ucheck-radio"><span class="am-ucheck-icons"><i class="am-icon-unchecked"></i><i class="am-icon-checked"></i></span>
                                        启用
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" name="shop[enabled]" value="0" <?= $model['enabled'] == 0 ? 'checked' : '' ?> data-am-ucheck="" class="am-ucheck-radio"><span class="am-ucheck-icons"><i class="am-icon-unchecked"></i><i class="am-icon-checked"></i></span>
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

<!-- 图片文件列表模板 -->
{{include file="layouts/_template/tpl_file_item" /}}

<!-- 文件库弹窗 -->
{{include file="layouts/_template/file_library" /}}

<script>
    $(function () {

        // 选择图片
        $('.upload-file').selectImages({
            name: 'shop[logo_id]'
        });
        $('.upload-shops').selectImages({
            name: 'shop[images][]'
            , multiple: true
        });
        /**
         * 表单验证提交
         * @type {*}
         */
        $('#my-form').superForm();

    });
</script>