<link rel="stylesheet" type="text/css" href="assets/store/css/appnew.css">
<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">用户订单</div>
                </div>
                <div class="widget-body am-fr">
                   <div class="page_toolbar am-margin-bottom-xs am-cf">
                        <form id="form-search" class="toolbar-form" action="">
                            <input type="hidden" name="s" value="/store/user/orders/user_id/<?= $user_id ?>">
                            <input type="hidden" name="dataType" value="all">
                            <div class="am-u-sm-12 am-u-md-3">
                                <div class="am-form-group">
                                    <div class="am-btn-toolbar">
                                        <div class="am-btn-group am-btn-group-xs">
                                        <a class="j-export am-btn am-btn-success am-radius" href="javascript:void(0);">
                                                    <i class="iconfont icon-daochu am-margin-right-xs"></i>导出
                                                </a>
                                   
                                </div>
                                    </div>
                                </div>
                            </div>
                            <div class="am-u-sm-12 am-u-md-9">
                                <div class="am fr">
                                   
                                    <div class="am-form-group tpl-form-border-form am-fl">
                                        <input type="text" name="start_time" class="am-form-field" value="<?= $search_param['start_time'] ? $search_param['start_time'] : '' ?>" placeholder="请选择起始日期" data-am-datepicker="">
                                    </div>
                                    <div class="am-form-group tpl-form-border-form am-fl">
                                        <input type="text" name="end_time" class="am-form-field" value="<?= $search_param['end_time'] ? $search_param['end_time'] : '' ?>" placeholder="请选择截止日期" data-am-datepicker="">
                                    </div>
                                    <div class="am-form-group am-fl">
                                        <div class="am-input-group am-input-group-sm tpl-form-border-form">
                                            <input type="text" class="am-form-field" name="search" placeholder="订单号" value="<?= $search_param['search'] ? $search_param['search'] : '' ?>">
                                            <div class="am-input-group-btn">
                                                <button class="am-btn am-btn-default am-icon-search" type="submit"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <table width="100%" class="am-table am-table-compact am-table-striped
                         tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>订单号</th>
                                <th>用户名</th>
                                <th>消费门店</th>
                                <th>消费积分</th>
                                <th>时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['order_sn'] ?></td>
                                    <td class="am-text-middle"><?= $item['real_name'] ?></td>
                                    
                                    <td class="am-text-middle"><?= $item['shop_name'] ?></td>
                                    <td class="am-text-middle"><?= $item['points'] ?></td>
                                    
                                    <td class="am-text-middle"><?= $item['create_time'] ?></td>
                                    
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
                        <div class="am-fr"><?= $list->render() ?> </div>
                        <div class="am-fr pagination-total am-margin-right">
                            <div class="am-vertical-align-middle">总记录：<?= $list->total() ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    $(function () {

        /**
         * 订单导出
         */
        $('.j-export').click(function () {
            var data = {};
            var formData = $('#form-search').serializeArray();
            $.each(formData, function () {
                this.name !== 's' && (data[this.name] = this.value);
            });
            window.location = "index.php?s=/store/data/listsExport" + '&' + $.urlEncode(data);
        });

    });

</script>


