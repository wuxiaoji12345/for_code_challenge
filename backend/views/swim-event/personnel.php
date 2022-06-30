<?php

use yii\helpers\Html;
use backend\assets\AppAsset;
use common\models\MatchSchedule;

$this->title = '赛前人员安排';
$this->params['breadcrumbs'][] = $this->title;


$must = [];
$unmust = [];
$data = $model->infos;
$data = json_decode($data,true);
if($data){
    foreach($data as $k=>$v){
        if($v['key'] > 0){
            $must[$v['key']] = $v;
        }else{
            $unmust[] = $v;
        }
    }
}

?>

<div class="panel panel-<?= AppAsset::BOX_CLASS ?>">
    <div class="panel-heading <?= AppAsset::BOX_BORDER ?>">
        <h3 class="panel-title"><?= $this->title ?></h3>
    </div>
    <div class="panel-body">
        <div class="personnel-form">

            <div class="row row-enabled">
                <?php foreach($model->mustList as $k=>$v){ ?>
                <div class="person " data-id="<?= $k ?>">
                    <div class="col-md-6 no-right">
                        <input type="text" class="form-control" name="job-name" value="<?= $v ?>" disabled>
                    </div>
                    <div class="col-md-6 no-left">
                        <select name="job-value" class="form-control">
                            <option value="0">请选择人员</option>
                            <?php foreach($model->userList as $key=>$val){ ?>
                                <?php if(array_key_exists($k, $must) && $must[$k]['value'] == $key){ ?>
                                    <option value="<?= $key ?>" selected><?= $val ?></option>
                                <?php }else{ ?>
                                    <option value="<?= $key ?>"><?= $val ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <?php } ?>

                <?php if($unmust){ ?>
                    <?php foreach($unmust as $key=>$val){ ?>
                        <div class="person">
                            <div class="col-md-6 no-right">
                                <input type="text" name="job-name" class="form-control" placeholder="岗位..." value="<?= $val['name'] ?>">
                            </div>
                            <div class="col-md-6 no-left">
                                <div class="input-group">
                                    <select name="job-value" class="form-control">
                                        <option value="0">请选择人员</option>
                                        <?php foreach($model->userList as $k=>$v){ ?>
                                            <?php if($val['value'] == $k){ ?>
                                                <option value="<?= $k ?>" selected><?= $v ?></option>
                                            <?php }else{ ?>
                                                <option value="<?= $k ?>"><?= $v ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                            <span class="input-group-btn">
                                 <button class="btn btn-danger remove" type="button">移除</button>
                            </span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>

            <ul class="dropdown-menu display-show">
                <?php foreach($model->personList as $k=>$v){ ?>
                    <li><a href="#" class="show"><?= $v ?></a></li>
                <?php } ?>
            </ul>

        </div>
        <div class="box-footer row text-center">
            <?= Html::Button('新增人员', ['class' => 'btn btn-default  pull-left create']) ?>
            <?= Html::Button('保存', ['class' => 'btn btn-success save']) ?>
        </div>

    </div>
</div>

<style>
    .no-right{
        padding-right:0;
    }
    .no-left{
        padding-left:0;
    }
    .display-show{
        left:15px;
        margin:0;
        padding:0;
    }
    .person{
        width:50%;
        float: left;
        margin-bottom:10px;
    }

</style>


<?php $this->beginBlock('js')?>

    var data = [];
    var line = '<?= $line ?>';
    var menu = '<?= $menu ?>';
    var mid = <?= $mid ?>

    <!-- 移除 -->
    $('.row-enabled').on('click','.remove',function(){
        var person = $(this).parents('.person').remove();
    });

    <!-- 点击输入框时出现提示岗位的列表 -->
    $('.row-enabled').on('click','input[name="job-name"]',function(e){
        $('.display-show').remove();
        $(this).after(menu);
        $('.display-show').show();

        $(document).one("click",function(){
            $('.display-show').remove();
        });
        e.stopPropagation();//阻止事件向上冒泡
    });

    <!-- 输入框输入时隐藏掉提示岗位的列表 -->
    $('.row-enabled').on('input propertychange','input[name="job-name"]',function(){
        $('.display-show').remove();
    });

    <!-- 点击提示的岗位时将岗位填入输入框 -->

    $(document).on("click",".display-show a",function(e){

        var parent = $(this).parents('.person');
        parent.find('input[name="job-name"]').val($(this).html());
        $('.display-show').remove();

        e.stopPropagation();//阻止事件向上冒泡

    })
<!---->
<!---->
<!--    $('.display-show a').on('click',function(e){-->
<!---->
<!--        console.log(222);-->
<!---->
<!---->
<!--    });-->

    <!-- 新增人员 -->
    $('.create').on('click',function(){
        $('.row-enabled').append(line);
    });
    <!-- 保存 -->
    $('.save').on('click',function(){
        var person = $('.personnel-form .person');
        person.each(function(i,item){
            var name = $(this).find('input[name="job-name"]').val();
            var val = $(this).find('select[name="job-value"]').val();
            if(name.length == 0 || $.trim(name).length == 0 || val == 0){
                alert('岗位或者人员不能为空');
                return false;
            }
            if($(this).attr('data-id') > 0){
                data[i] = {name:name,value:val,key:$(this).attr('data-id')}
            }else{
                data[i] = {name:name,value:val,key:'0'}
            }
        });
        if(data.length != person.length){
            return false;
        }

        $.ajax({
            url:'personnel-do',
            type:'get',
            data:{'data':data,'mid':mid},
            success:function(data){
                if(data.status == 200){
                    alert("保存成功");
                    window.location.reload();
<!--                    window.location.href = 'index';-->
                }
            }
        });

    });

<?php $this->endBlock()?>
<?php $this->registerJs($this->blocks['js'])?>
