<p><img src="http://vse-avtoservisy.ru/i/serv_top_zp.jpg" alt="" width="593"
        height="58"/></p>
<table border="0" width="593">
    <tbody>
    <tr>
        <td>

            <p class="h3"
               style="font-size: 15px; padding-top: 4px; padding-right: 4px; padding-bottom: 4px; padding-left: 0px; font-weight: normal; line-height: 19px; font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; text-align: left; margin: 0px;">
                <span
                    style="color: #ff0000;">На сайте Vse-Avtoservisy.RU новый запрос по <?= $brand ?> <?= $model ?></span>
            </p>

            <p
                style="font-size: 11px; color: #333333; clear: both; padding-top: 5px; padding-right: 0px; padding-bottom: 5px; padding-left: 5px; line-height: 15px; font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; text-align: left; margin: 0px;">

                <? /*Запрос был отправлен в сервисы, предлагающие: <?=$rubrics?><br><br>*/ ?>

                <span
                    style="font-size: 20px;">ID запроса: <b><?= $request_id ?></b></span><br><br>

                Чтобы просмотреть запрос и ответить на него перейдите по ссылке:<br><br>

                <a href="<?= urlencode($request_url) ?>"><?= $request_url ?></a><br><br>

                или скопируйте код ссылки и вставьте её в адресную строку
                браузера:<br><br>

                <?= $request_url ?><br><br>

                Обращаем Ваше внимание на то, что Ваш ответ на запрос должен
                появиться на сайте сразу же после того как
                Вы нажмете кнопку "Отправить". Если по какой-то причине Ваш
                ответ не появился в списке ответов или Вы не
                можете ответить из-за какой либо ошибки или над полем ответа не
                появляется название Вашего сервис и
                номер телефона, просьба сообщить об этом на почту или по
                телефону (495) 221-59-51 администраторам
                портала.</p>

            <p style="text-align: left;"><span
                    style="font-size: x-small; font-family: verdana, geneva;">С уважением, администрация сайта&nbsp;<a
                        href="http://www.vse-avtoservisy.ru">Vse-Avtoservisy.ru</a></span><br/><span
                    style="font-size: x-small; font-family: verdana, geneva;">Тел: &nbsp; &nbsp; (495) 221-59-51</span><br/><span
                    style="font-size: x-small; font-family: verdana, geneva;">E-mail: <a
                        href="mailto:info@vse-avtoservisy.ru">info@vse-avtoservisy.ru</a></span>
            </p>

        </td>
    </tr>
    </tbody>
</table>