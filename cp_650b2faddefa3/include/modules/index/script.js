$(document).ready(function() {
  $.getScript('files/js/javascript.js');

  var $primary = '#7367F0';
  var $danger = '#EA5455';
  var $warning = '#FF9F43';
  var $info = '#0DCCE1';
  var $primary_light = '#8F80F9';
  var $warning_light = '#FFC085';
  var $danger_light = '#f29292';
  var $info_light = '#1edec5';
  var $strok_color = '#b9c3cd';
  var $label_color = '#e7eef7';
  var $white = '#fff';

  var gainedChartoptions = {
    chart: {
      height: 100,
      type: 'area',
      toolbar: {
        show: false,
      },
      sparkline: {
        enabled: true
      },
      grid: {
        show: false,
        padding: {
          left: 0,
          right: 0
        }
      },
    },
    colors: [$primary],
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      width: 2.5
    },
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 0.9,
        opacityFrom: 0.7,
        opacityTo: 0.5,
        stops: [0, 80, 100]
      }
    },
    series: [{
      name: '',
      data: []
    }],
    xaxis: {
      type: 'datetime',
    },
    yaxis: [{
      y: 0,
      offsetX: 0,
      offsetY: 0,
      padding: { left: 0, right: 0 },
    }],
    tooltip: {
      x: {
            format: 'dd.MM.yyyy'
         },
      y: {
        formatter: function (y) {
          if (typeof y !== "undefined") {
            return y;
          }
          return y;

        }
      }      
    },
  }

  var clientsChart = new ApexCharts(
    document.querySelector("#clients-gain-chart"),
    gainedChartoptions
  );

  clientsChart.render();

  var gainedChartoptions = {
    chart: {
      height: 100,
      type: 'area',
      toolbar: {
        show: false,
      },
      sparkline: {
        enabled: true
      },
      grid: {
        show: false,
        padding: {
          left: 0,
          right: 0
        }
      },
    },
    colors: [$warning_light],
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      width: 2.5
    },
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 0.9,
        opacityFrom: 0.7,
        opacityTo: 0.5,
        stops: [0, 80, 100]
      }
    },
    series: [{
      name: '',
      data: []
    }],

    xaxis: {
      type: 'datetime',
    },
    yaxis: [{
      y: 0,
      offsetX: 0,
      offsetY: 0,
      padding: { left: 0, right: 0 },
    }],
    tooltip: {
      x: {
            format: 'dd.MM.yyyy'
         },
      y: {
        formatter: function (y) {
          if (typeof y !== "undefined") {
            return y;
          }
          return y;

        }
      }
    },
  }

  var adsChart = new ApexCharts(
    document.querySelector("#ads-gain-chart"),
    gainedChartoptions
  );

  adsChart.render();

  var gainedChartoptions = {
    chart: {
      height: 100,
      type: 'area',
      toolbar: {
        show: false,
      },
      sparkline: {
        enabled: true
      },
      grid: {
        show: false,
        padding: {
          left: 0,
          right: 0
        }
      },
    },
    colors: [$warning],
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      width: 2.5
    },
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 0.9,
        opacityFrom: 0.7,
        opacityTo: 0.5,
        stops: [0, 80, 100]
      }
    },
    
    series: [{
      name: '',
      data: []
    }],
    xaxis: {
      type: 'datetime',
    },
    yaxis: [{
      y: 0,
      offsetX: 0,
      offsetY: 0,
      padding: { left: 0, right: 0 },
    }],
    tooltip: {
      x: {
            format: 'dd.MM.yyyy'
         },      
      y: {
        formatter: function (y) {
          if (typeof y !== "undefined") {
            return number_format( y , 1, ',', ' ') + $("body").data("currency");
          }
          return y;

        }
      }
    },
  }

  var ordersChart = new ApexCharts(
    document.querySelector("#orders-gain-chart"),
    gainedChartoptions
  );

  ordersChart.render();

  var gainedChartoptions = {
    chart: {
      height: 100,
      type: 'area',
      toolbar: {
        show: false,
      },
      sparkline: {
        enabled: true
      },
      grid: {
        show: false,
        padding: {
          left: 0,
          right: 0
        }
      },
    },
    colors: [$info_light],
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      width: 2.5
    },
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 0.9,
        opacityFrom: 0.7,
        opacityTo: 0.5,
        stops: [0, 80, 100]
      }
    },
    series: [{
      name: '',
      data: []
    }],

    xaxis: {
      type: 'datetime',
    },
    yaxis: [{
      y: 0,
      offsetX: 0,
      offsetY: 0,
      padding: { left: 0, right: 0 },
    }],
    tooltip: {
      x: {
            format: 'dd.MM.yyyy'
         },
      y: {
        formatter: function (y) {
          if (typeof y !== "undefined") {
            return y;
          }
          return y;

        }
      }
    },
  }

  var trafficChart = new ApexCharts(
    document.querySelector("#traffic-gain-chart"),
    gainedChartoptions
  );

  trafficChart.render();


function loadStat(){

   $.ajax({type: "POST",url: "include/modules/index/handlers/update.php",data: "page=" + $("input[name=page]").val() ,dataType: "json",cache: false,success: function (data) { 

      if( parseInt(data["clients"]["count"]) != parseInt($(".clients-chart-count").html()) ){
        $(".clients-chart-count").html(data["clients"]["count"]);
        clientsChart.updateSeries([{
          name: 'Пользователей',
          data: data["clients"]["data"]
        }]);         
      }

      if( parseInt(data["ads"]["count"]) != parseInt($(".ads-chart-count").html()) ){
        $(".ads-chart-count").html(data["ads"]["count"]);
        adsChart.updateSeries([{
          name: 'Объявлений',
          data: data["ads"]["data"]
        }]);
      }

      if( parseInt(data["orders"]["count"]) != parseInt($(".orders-chart-count").html()) ){
        $(".orders-chart-count").html(data["orders"]["count"]);
        ordersChart.updateSeries([{
          name: 'Сумма продаж',
          data: data["orders"]["data"]
        }]);
      }

      if( parseInt(data["traffic"]["count"]) != parseInt($(".traffic-chart-count").html()) ){
        $(".traffic-chart-count").html(data["traffic"]["count"]);
        trafficChart.updateSeries([{
          name: 'Посетителей',
          data: data["traffic"]["data"]
        }]);
      }
      
      $(".data-list-ads").html(data["list_ads"]);
      $(".data-list-users").html(data["list_users"]);
      $(".data-list-traffic").html(data["list_traffic"]);
      $(".data-list-log-action").html(data["list_log_action"]);
      $(".data-list-chat-messages").html(data["list_chat_messages"]);

   }});

}


$(window).load(function() {

loadStat();

setInterval(function() {

loadStat();

}, 5000);

});

$(document).on('click','.metrics-route-more', function () { 
   
   id_metric = $(this).data("id");   
     
   $.ajax({type: "POST",url: "include/modules/index/handlers/route.php",data: "id="+id_metric ,dataType: "html",cache: false,success: function (data) { 
       
       $(".modal-metrics-route-content").html(data);
       $(".modal-metrics-route").show();
       $("body").css("overflow", "hidden");

   }});

     
});

$(document).on('click','.delete-notification', function (e) { 

   $.ajax({type: "POST",url: "include/modules/index/handlers/delete_notification.php",dataType: "html",cache: false,success: function (data) { 
       
       location.reload();

   }});

   e.preventDefault();

     
});

$(document).on('click','.new-update-notification-close', function (e) { 

   $.ajax({type: "POST",url: "include/modules/index/handlers/update_notification.php",data: "version="+$(this).data('version') ,dataType: "html",cache: false,success: function (data) { 
       
       $('.new-update-notification').hide();

   }});

   e.preventDefault();
     
});

$(document).on('click','.modal-metrics-route > i', function () { 
   
   $(".modal-metrics-route").hide();
   $("body").css("overflow", "auto");

});


});