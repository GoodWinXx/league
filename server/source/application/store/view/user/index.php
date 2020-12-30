<link rel="stylesheet" type="text/css" href="assets/store/css/appnew.css">
<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-cf">用户列表</div>
                </div>
                <div class="widget-body am-fr">
                  
                    <div class="page_toolbar am-margin-bottom-xs am-cf">
                        <form id="form-search" class="toolbar-form" action="">
                            <input type="hidden" name="s" value="/store/user/index">
                            <div class="am-u-sm-12 am-u-md-3">
                                <div class="am-form-group">
                                    <div class="am-btn-toolbar">
                                        <div class="am-btn-group am-btn-group-xs">
                                        <a class="am-btn am-btn-default am-btn-success am-radius"
                                       href="<?= url('user/add') ?>">
                                        <span class="am-icon-plus"></span> 新增
                                    </a>
                                   
                                </div>
                                    </div>
                                </div>
                            </div>
                            <div class="am-u-sm-12 am-u-md-9">
                                <div class="am fr">
                                   
                                   
                                    <div class="am-form-group am-fl">
                                        <div class="am-input-group am-input-group-sm tpl-form-border-form">
                                            <input type="text" class="am-form-field" name="search" placeholder="用户昵称/手机号" value="<?php if(!empty($request_data['search'])){echo $request_data['search'];} ?>">
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
                                <th>用户ID</th>
                                <th>用户昵称</th>
                                <th>手机号</th>
                                <th>总余额</th>
                                <th>当前余额</th>
                                <!-- <th>剩余积分</th> -->
                                <th>注册时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!$list->isEmpty()): foreach ($list as $item): ?>
                                <tr>
                                    <td class="am-text-middle"><?= $item['user_id'] ?></td>
                                    <!-- <td class="am-text-middle">
                                        <a href="<?= $item['avatarUrl'] ?>" title="点击查看大图" target="_blank">
                                            <img src="<?= $item['avatarUrl'] ?>" width="72" height="72" alt="">
                                        </a>
                                    </td> -->
                                    <td class="am-text-middle"><?= $item['nickName'] ?></td>
                                    <td class="am-text-middle"><?= $item['mobile'] ?></td>
                                    <td class="am-text-middle"><?= $item['total_points'] ?: '0' ?></td>
                                    <td class="am-text-middle"><?= $item['current_points'] ?: '0' ?></td>
                                    <td class="am-text-middle"><?= $item['create_time'] ?></td>
                                    <td class="am-text-middle">
                                        <div class="tpl-table-black-operation">
                                            <a class="j-recharge tpl-table-black-operation-default" href="javascript:void(0);" data-id="<?= $item['user_id'] ?: '0' ?>" data-balance="<?= $item['current_points'] ?: '0' ?>" title="充值余额">
                                                    <i class="iconfont icon-qiandai"></i>
                                                    充值
                                                </a>
                                            <a href="<?= url('user/edit',
                                                ['user_id' => $item['user_id']]) ?>">
                                                <i class="am-icon-pencil"></i> 编辑
                                            </a>
                                            <a href="javascript:;" class="item-sms"
                                               data-id="<?= $item['user_id'] ?>">
                                                <i class="am-icon-arrow"></i> 短信
                                            </a>
                                            <a href="javascript:;" class="item-delete tpl-table-black-operation-del"
                                               data-id="<?= $item['user_id'] ?>">
                                                <i class="am-icon-trash"></i> 删除
                                            </a>
                                            
                                            <div class="j-opSelect operation-select am-dropdown">
                                                <button type="button" class="am-dropdown-toggle am-btn am-btn-sm am-btn-secondary">
                                                    <span>更多</span>
                                                    <span class="am-icon-caret-down"></span>
                                                </button>
                                                <ul class="am-dropdown-content" data-id="<?= $item['user_id'] ?>">
                                                    <li>
                                                        <a class="am-dropdown-item" target="_blank" href="index.php?s=/store/user/orders/user_id/<?= $item['user_id'] ?>">用户订单</a>
                                                        </li>
                                                    
                                                    <li>
                                                        <a class="am-dropdown-item" target="_blank" href="index.php?s=/store/recharge/index/user_id/<?= $item['user_id'] ?>">余额明细</a>
                                                        </li>
                                                         </ul>
                                            </div>
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
        // 删除元素
        var url = "<?= url('user/delete') ?>";
        $('.item-delete').delete('user_id', url);
        var url = "<?= url('user/sms') ?>";
        $('.item-sms').sms('user_id', url);
        /**
         * 账户充值
         */
        $('.j-recharge').on('click', function () {
            var data = $(this).data();
            $.showModal({
                title: '充值积分'
                , area: '460px'
                , content: template('tpl-recharge', data)
                , uCheck: true
                , success: function ($content) {
                    // $content.find('.j-tabs').tabs({noSwipe: 1});
                }
                , yes: function ($content) {
                    $content.find('form').myAjaxSubmit({
                        url: 'index.php?s=/store/user/recharge',
                        data: {user_id: data.id}
                    });
                    return true;
                }
            });
        });

        var $dropdown = $('.j-opSelect');
        $dropdown.dropdown();
        $dropdown.on('click', 'li a', function () {
            var $this = $(this);
            var id = $this.parent().parent().data('id');
            var type = $this.data('type');
            if (type === 'delete') {
                layer.confirm('删除后不可恢复，确定要删除吗？', function (index) {
                    $.post("index.php?s=/store/apps.dealer.user/delete", {dealer_id: id}, function (result) {
                        result.code === 1 ? $.show_success(result.msg, result.url)
                            : $.show_error(result.msg);
                    });
                    layer.close(index);
                });
            }
            $dropdown.dropdown('close');
        });
    });
</script>
<script id="tpl-recharge" type="text/template">
    <div class="am-padding-xs am-padding-top-sm">
        <form class="am-form tpl-form-line-form" method="post" action="">
            <div class="j-tabs am-tabs">

                
                <div class="am-tabs-bd am-padding-xs">
                    <div class="am-tab-panel am-padding-0 am-active" id="tab1">
                        <div class="am-form-group">
                            <label class="am-u-sm-3 am-form-label">
                                当前余额
                            </label>
                            <div class="am-u-sm-8 am-u-end">
                                <div class="am-form--static">{{ balance }}</div>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label class="am-u-sm-3 am-form-label">
                                充值方式
                            </label>
                            <div class="am-u-sm-8 am-u-end">
                                <label class="am-radio-inline">
                                    <input type="radio" name="recharge[mode]"
                                           value="inc" data-am-ucheck checked>
                                    增加
                                </label>
                                <label class="am-radio-inline">
                                    <input type="radio" name="recharge[mode]" value="dec" data-am-ucheck>
                                    减少
                                </label>
                                
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label class="am-u-sm-3 am-form-label">
                                变更余额
                            </label>
                            <div class="am-u-sm-8 am-u-end">
                                <input type="number" min="0" class="tpl-form-input"
                                       placeholder="请输入要变更的余额" name="recharge[money]" value="" required>
                            </div>
                        </div>
                        <div class="am-form-group">
                            <label class="am-u-sm-3 am-form-label">
                                管理员备注
                            </label>
                            <div class="am-u-sm-8 am-u-end">
                                <textarea rows="2" name="recharge[remark]" placeholder="请输入管理员备注"
                                          class="am-field-valid"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</script>

