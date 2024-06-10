<!doctype html>
<html lang="<?php echo getLang(); ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo $ULang->t("Служба поддержки"); ?> - <?php echo $settings["site_name"]; ?></title>

    <?php include $config["template_path"] . "/head.tpl"; ?>

  </head>

  <body data-prefix="<?php echo $config["urlPrefix"]; ?>" data-template="<?php echo $config["template_folder"]; ?>" >
    
    <?php include $config["template_path"] . "/header.tpl"; ?>

    <div class="container" >
       
       <nav aria-label="breadcrumb" class="mt10" >
 
          <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">

            <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
              <a itemprop="item" href="<?php echo $config["urlPath"]; ?>">
              <span itemprop="name"><?php echo $ULang->t("Главная"); ?></span>
              </a>
              <meta itemprop="position" content="1">
            </li>

            <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
              <span itemprop="name"><?php echo $ULang->t("Служба поддержки"); ?></span>
              <meta itemprop="position" content="2">
            </li>                 
          </ol>

        </nav>
          
        <div class="row" >
            <div class="col-lg-12" >
              <h2 class="mb30" ><?php echo $ULang->t("Вопрос в службу поддержки"); ?></h2>
            </div>
            <div class="col-lg-3" >
              <div class="page-menu-list" >
                 <?php
                    echo $Main->pageMenu($data);
                 ?>
              </div>
            </div>
            <div class="col-lg-9" >

              <div class="bg-container" >

                 <h5 class="mb25" > <strong><?php echo $ULang->t("Часто задаваемые вопросы"); ?></strong> </h5>

                 <div class="feedback-answers-questions" >
                    <p><?php echo $ULang->t("Как быстрей всего продать товар?"); ?> <i class="las la-angle-down"></i></p>
                    <div>
                      <?php echo $ULang->t("Выделить свое объявление из общей массы очень просто, для это вы можете подключить к своему объявлению платную услугу или пакет услуг"); ?> <a href="<?php echo _link("promo/services"); ?>"><?php echo $ULang->t("Турбо продажа"); ?></a> <?php echo $ULang->t("ваше объявление будет поднято в ленте и выделено маркером, вы точно не пропустите своего покупателя!"); ?>
                    </div>
                 </div>
                 <div class="feedback-answers-questions" >
                    <p><?php echo $ULang->t("Моё объявление заблокировано"); ?> <i class="las la-angle-down"></i></p>
                    <div>
                      <?php echo $ULang->t("Если ваше объявление заблокировано, то попробуйте поискать причину в"); ?> <a href="<?php echo _link("rules"); ?>"><?php echo $ULang->t("Правилах Сервиса"); ?></a>. <?php echo $ULang->t("Найдя причину, устраните нарушение правил и подайте объявление заново."); ?>
                    </div>
                 </div>
                 <div class="feedback-answers-questions" >
                    <p><?php echo $ULang->t("Мой аккаунт заблокирован администрацией"); ?> <i class="las la-angle-down"></i></p>
                    <div>
                      <?php echo $ULang->t("Чаще всего блокировка аккаунта происходит потому, что поведение пользователя не соответствует"); ?> <a href="<?php echo _link("rules"); ?>"><?php echo $ULang->t("Правилам сервиса"); ?></a>, <?php echo $ULang->t("например:"); ?>
                      <ul class="mt10" >
                         <li><?php echo $ULang->t("Неоднократное размещение объявлений, заблокированных модератором. Например, содержащих запрещенные законом РФ и/или Правилами пользования сервисом товары/услуги или содержащие ссылки на сторонние сайты;"); ?></li>
                         <li><?php echo $ULang->t("Публикация объявлений или рассылка сообщений с использованием стороннего программного обеспечения или автоматизированных скриптов;"); ?></li>
                         <li><?php echo $ULang->t("Использование в фото, имени или фамилии пользователя информации, запрещенной законом РФ и/или Правилами пользования сервисом (нецензурные выражения, контактные данные);"); ?></li>
                         <li><?php echo $ULang->t("Пользователь обманным путем пытался получить данные банковской карты других пользователей;"); ?></li>
                         <li><?php echo $ULang->t("Пользователь не отправил товар после получения предоплаты или не оплатил товара после его получения."); ?></li>
                      </ul>
                      <p><?php echo $ULang->t("Если администрация"); ?> <?php echo $settings["site_name"]; ?> <?php echo $ULang->t("заблокировала ваш аккаунт, а вы уверены, что следовали Правилам сервиса, напишите нам."); ?></p>
                    </div>
                 </div>
                 <?php if( $settings["secure_status"] ){ ?>
                 <div class="feedback-answers-questions" >
                    <p><?php echo $ULang->t("Почему для моего товара недоступна безопасная сделка?"); ?> <i class="las la-angle-down"></i></p>
                    <div>
                      <?php echo $ULang->t("На текущий момент сервис Безопасная сделка доступен в объявлениях со стоимостью товаров от"); ?> <?php echo $Main->price($data['payment']["secure_min_amount_payment"]); ?> <?php echo $ULang->t("до"); ?> <?php echo $Main->price($data['payment']["secure_max_amount_payment"]); ?> <?php echo $ULang->t("в категориях"); ?>
                      <?php
                       echo implode(",", $data["secure_category"]);
                      ?>
                      .
                      <br><br>
                      <?php echo $ULang->t("Если вы не видите у своего объявления значок безопасная сделка, вероятнее всего ваш товар находится в другой категории, либо не соответствует ценовому диапазону."); ?> 
                      <br><br>
                      <p><?php echo $ULang->t("Если ваш товар удовлетворяет критериям, но вы не видите значок безопасной сделки, напишите нам."); ?></p> 
                    </div>
                 </div>
                 
                 <div class="feedback-answers-questions" >
                    <p><?php echo $ULang->t("В течение какого времени осуществляется возврат средств по Безопасной сделке?"); ?> <i class="las la-angle-down"></i></p>
                    <div>
                      <?php echo $ULang->t("Мы осуществляем возврат средств в тот момент, когда сделка была отменена. Как правило, средства за товар возвращаются день в день в случае отмены сделки в день ее создания и в течение 1-3х рабочих дней, если сделка была отменена позднее. Обратите внимание, смс о возврате не всегда приходит от банка, можно проверить поступление средств на основании выписки по счету, либо на основании текущего баланса счета."); ?>
                    </div>
                 </div>
                 <?php } ?>


                 <h5 class="mb30 mt40" > <strong><?php echo $ULang->t("Не нашли ответ? Напишите нам!"); ?></strong> </h5>

                 <div class="feedback-form" >

                   <div class="feedback-success" >
                     <strong><?php echo $ULang->t("Спасибо, ваше обращение принято!"); ?></strong><br>
                     <?php echo $ULang->t("Специалист"); ?> <?php echo $settings["site_name"]; ?> <?php echo $ULang->t("ответит вам на указанный E-mail."); ?>
                   </div>

                   <form class="feedback" >

                     <div class="row" >
                        <div class="col-lg-4" >
                           <label><?php echo $ULang->t("Выберите тему обращения"); ?></label>
                        </div>
                        <div class="col-lg-8" >
                           <select class="form-control" name="subject" >
                              <option value="Не выбрано" ><?php echo $ULang->t("Не выбрано"); ?></option>
                              <option value="Вопрос по платным услугам" ><?php echo $ULang->t("Вопрос по платным услугам"); ?></option>
                              <option value="Финансовые вопросы" ><?php echo $ULang->t("Финансовые вопросы"); ?></option>
                              <option value="Вопросы по работе сервиса" ><?php echo $ULang->t("Вопросы по работе сервиса"); ?></option>
                              <option value="Технические проблемы" ><?php echo $ULang->t("Технические проблемы"); ?></option>
                              <option value="Меня пытались обмануть" ><?php echo $ULang->t("Меня пытались обмануть"); ?></option>
                              <option value="Предложения" ><?php echo $ULang->t("Предложения"); ?></option>
                              <option value="Другое" ><?php echo $ULang->t("Другое"); ?></option>
                           </select>                          
                        </div>
                     </div>
                     <div class="row mt15" >
                        <div class="col-lg-4" >
                           <label><?php echo $ULang->t("Обращение"); ?></label>
                        </div>
                        <div class="col-lg-8" >
                           <textarea class="form-control" rows="4" name="text" ></textarea>                          
                        </div>
                     </div> 
                     <div class="row mt15" >
                        <div class="col-lg-4" >
                           <label><?php echo $ULang->t("Как к вам можно обращаться?"); ?></label>
                        </div>
                        <div class="col-lg-8" >
                           <input type="text" name="name" class="form-control" >                         
                        </div>
                     </div>
                     <div class="row mt15" >
                        <div class="col-lg-4" >
                           <label><?php echo $ULang->t("Почта, на которую мы ответим"); ?></label>
                        </div>
                        <div class="col-lg-8" >
                           <input type="text" name="email" class="form-control" >                          
                        </div>
                     </div>
                     <div class="row mt15" >
                        <div class="col-lg-4" ></div>
                        <div class="col-lg-8" >
                           <div class="captcha-container" >
                              <img class="captcha-update" src="" >   
                           </div>                       
                        </div>
                     </div>
                     <div class="row mt15" >
                        <div class="col-lg-4" >
                           <label><?php echo $ULang->t("Укажите код проверки"); ?></label>
                        </div>
                        <div class="col-lg-2" >
                           <input type="text" name="code" class="form-control" >                          
                        </div>
                     </div>                                     
                     <div class="row mt5" >
                        <div class="col-lg-4" ></div>
                        <div class="col-lg-8" >
                           <hr> 
                           <button class="btn-custom btn-color-blue" ><?php echo $ULang->t("Отправить обращение"); ?></button>                         
                        </div>
                     </div>

                     <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>" >

                   </form>
                 </div>

              </div>

            </div>
        </div>
         
          
       <div class="mt50" ></div>


    </div>


    <?php include $config["template_path"] . "/footer.tpl"; ?>

  </body>
</html>