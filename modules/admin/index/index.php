<?php

use app\modules\admin\models\Booked;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $room \app\models\Room */
?>
<p>
    <?php
    $this->registerCss('@web/css/room.css');
    $this->title = 'Панель';
    if (\Yii::$app->user->can(\app\models\User::ROLE_MANAGER)): ?>
        <?= Html::a('Создать бронь', ['booked/booked-room'], ['class' => 'btn btn-warning']) ?>
    <?php endif; ?>
</p>

<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading text-center">Статистика</div>
    <!-- Table -->
    <table class="table">
        <thead>
        <tr>
            <th>Свободно</th>
            <th>Занято</th>
            <th>Бронирований</th>
            <th>Клиентов</th>
            <th>Поселений (сегодня)</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?= $freeRoom ?></td>
            <td><?= $busyRoom ?></td>
            <td><?= $countBookedRooms ?></td>
            <td><?= $countClient ?></td>
            <td><?= $numberOfPeopleTodayBooked ?></td>
        </tr>
        </tbody>
    </table>
</div>

<?php $form = ActiveForm::begin() ?>
<div class="row">
    <div class="col-xs-12 col-sm-3">
        <?= $form->field($filtration, 'arrival_date')->widget(DatePicker::className(), [
            'type' => DatePicker::TYPE_COMPONENT_PREPEND,
            'convertFormat' => true,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-MM-dd',
                'startDate' => '0d',
                'todayHighlight' => true
            ]
        ])->label(false); ?>
    </div>
    <div class="col-xs-12 col-sm-3">
        <?= $form->field($filtration, 'date_eviction')->widget(DatePicker::className(), [
            'type' => DatePicker::TYPE_COMPONENT_PREPEND,
            'convertFormat' => true,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-MM-dd',
                'startDate' => '0d',
                'todayHighlight' => true
            ]
        ])->label(false); ?>
    </div>
    <div class="col-xs-12 col-sm-3">
        <?= Html::submitButton('Фильтровать', ['class' => 'btn btn-success']) ?>
       <a href="<?=Url::to(['/admin/index'])?>" class="btn btn-success"><i class="glyphicon glyphicon-refresh"></i></a>
    </div>
</div>

<?php $clientWait = null ?>
<?php ActiveForm::end() ?>
<div class="row">
    <?php if (empty($allRoomsForPeriod)):  ?>
    <?php
    /** @var  $booked Booked */
    foreach ($bookedRooms as $booked): ?>
        <?php if ($booked->wait_payment_client_id): ?>
            <div class="col-md-2"
                 title="дата бронирования с <?= $booked->arrival_date ?> по <?= $booked->date_eviction ?> ">
                <a href="<?= Url::toRoute(['booked/waiting-payment?clientId=' . $booked->client_id . '&roomId=' . $booked->room_id]) ?>"
                   style="background-color: orange" class="thumbnail">
                    <p style="color: white"
                       class="text-center"><?= $booked->room->number_room ?>
                    </p>
                    <p style="color: white"
                       class="text-center">
                        <?= $booked->room->type->title ?>
                    </p>
                </a>
            </div>
        <?php elseif (empty($booked->wait_payment_client_id)): ?>
            <div class="col-md-2"
                 title="дата бронирования с <?= $booked->arrival_date ?> по <?= $booked->date_eviction ?> ">
                <a href="<?= Url::toRoute(['booked/waiting-payment?clientId=' . $booked->client_id . '&roomId=' . $booked->room_id]) ?>"
                   style="background-color: darkred" class="thumbnail">
                    <p style="color: white"
                       class="text-center"><?= $booked->room->number_room ?>
                    </p>
                    <p style="color: white"
                       class="text-center">
                        <?= $booked->room->type->title ?>
                    </p>
                </a>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
    
    <?php foreach ($rooms as $room): ?>
        <div class="col-md-2">
            <a href="<?= Url::toRoute(['room/view', 'id' => $room->id]) ?>" style="background-color: green"
               class="thumbnail">
                <p title="свободный номер" style="color: white" class="text-center"><?= $room->number_room ?></p>
                <p style="color: white"
                   class="text-center">
                    <?= $room->type->title ?>
                </p>
            </a>
        </div>
    <?php endforeach; ?>
        <?php else: ?>

        <?php
        /** @var  $booked Booked */
        foreach ($allRoomsForPeriod as $booked): ?>
            <?php if (empty($booked->wait_payment_client_id)): ?>
            <div class="col-md-2"
                 title="дата бронирования с <?= $booked->arrival_date ?> по <?= $booked->date_eviction ?> ">
                <a href="<?= Url::toRoute(['booked/waiting-payment?clientId=' . $booked->client_id . '&roomId=' . $booked->room_id]) ?>"
                   style="background-color: darkred" class="thumbnail">
                    <p style="color: white"
                       class="text-center"><?= $booked->room->number_room ?>
                    </p>
                    <p style="color: white"
                       class="text-center">
                        <?= $booked->room->type->title ?>
                    </p>
                </a>
            </div>
            <?php elseif (!empty($booked->wait_payment_client_id)): ?>
            <div class="col-md-2"
                 title="дата бронирования с <?= $booked->arrival_date ?> по <?= $booked->date_eviction ?> ">
                <a href="<?= Url::toRoute(['booked/waiting-payment?clientId=' . $booked->client_id . '&roomId=' . $booked->room_id]) ?>"
                   style="background-color: orange" class="thumbnail">
                    <p style="color: white"
                       class="text-center"><?= $booked->room->number_room ?>
                    </p>
                    <p style="color: white"
                       class="text-center">
                        <?= $booked->room->type->title ?>
                    </p>
                </a>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

