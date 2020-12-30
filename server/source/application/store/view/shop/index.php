<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title a m-cf">门店列表</div>
                </div>
                <div class="widget-body am-fr">
                    <!-- 工具栏 -->
                    <div class="page_toolbar am-margin-bottom-xs am-cf">
                        <div class="am-form-group">
                            <div class="am-btn-group am-btn-group-xs">
                                    <a class="am-btn am-btn-default am-btn-success" href="index.php?s=/store/shop/add">
                                        <span class="am-icon-plus"></span> 新增
                                    </a>
                                </div>
                                                    </div>
                    </div>
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <table width="100%" class="am-table am-table-compact am-table-striped
                         tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>门店ID</th>
                                <th>门店名称</th>
                                <th>门店logo</th>
                                <th>营业时间</th>
                                <th>联系人</th>
                                <th>联系电话</th>
                                <th>门店地址</th>
                                <th>门店状态</th>
                                <!-- <th>创建时间</th> -->
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                 <tr>
                                    <td class="am-text-middle"><?= $item['id'] ?></td>
                                    <td class="am-text-middle"><?= $item['shop_name'] ?></td>
                                    <td class="am-text-middle">
                                        <a href="<?= $item['logo'] ?>"
                                           title="点击查看大图" target="_blank">
                                            <img src="<?= $item['logo'] ?>"
                                                 width="50" height="50" alt="门店logo">
                                        </a>
                                    </td>
                                    <td class="am-text-middle"><?= $item['shop_hours'] ?></td>
                                    <td class="am-text-middle"><?= $item['linkman'] ?></td>
                                    <td class="am-text-middle"><?= $item['mobile'] ?></td>
                                    <td class="am-text-middle"><?= $item['address']?></td>
                                    <td class="am-text-middle"><?= $item['enabled_text']?></td>
                                    
                                    <td class="am-text-middle">
                                        <div class="tpl-table-black-operation">
                                           
                                            <a href="<?= url('shop/edit',
                                                ['shop_id' => $item['id']]) ?>">
                                                <i class="am-icon-pencil"></i> 编辑
                                            </a>
                                            <a href="javascript:;" class="item-delete tpl-table-black-operation-del"
                                               data-id="<?= $item['id'] ?>">
                                                <i class="am-icon-trash"></i> 删除
                                            </a>
                                           
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="8" class="am-text-center">暂无记录</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="am-u-lg-12 am-cf">
                        <div class="am-fr"> </div>
                        <div class="am-fr pagination-total am-margin-right">
                            <div class="am-vertical-align-middle">总记录：0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {

        // 删除元素
        var url = "index.php?s=/store/shop/delete";
        $('.item-delete').delete('shop_id', url, '删除后不可恢复，确定要删除吗？');

    });
</script>