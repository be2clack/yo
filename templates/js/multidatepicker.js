$(function(){
  var MultiDate = {
    datepickerDiv: ".catalog-list-options-content-change-date",
    startDateField: ".change-date-from",
    endDateField: ".change-date-to",
    startDate: null,
    endDate: null,
    clearEndWhenSelectingStart: true,
    disableOutsideDates: false,
    numberOfMonths: 1,
    
    // Set either the start or the end date
    _changeDate: function(){
      var date = this.value;
      var startDate = MultiDate.startDate;
      var endDate = MultiDate.endDate;
      var dateTime = moment(MultiDate._convertStringToJSDate(date));
      if(startDate && dateTime.isSame(startDate)) {
        MultiDate._clearStartDate();
      } else if (endDate && dateTime.isSame(endDate)) {
        MultiDate._clearEndDate();
      } else if(startDate && dateTime.isBefore(startDate)) {
        MultiDate.setStartDate(date);
      } else if (endDate && dateTime.isAfter(endDate)) {
        MultiDate.setEndDate(date);
      } else if (startDate && !endDate) {
        MultiDate.setEndDate(date);
      } else {
        MultiDate.setStartDate(date);
      }
    },
    
    _updateStartDateEvent: function(e) {
      var date = MultiDate._convertStringToJSDate(e.target.value, true);
      if(date !== MultiDate.startDate) {
        MultiDate.startDate = date;
        MultiDate.$datepicker.datepicker("refresh");
        MultiDate.moveToFirstDay();
      }
    },
    
    _updateEndDateEvent: function(e) {
      var date = MultiDate._convertStringToJSDate(e.target.value, true);
      if(date !== MultiDate.endDate) {
        MultiDate.endDate = date;
        MultiDate.$datepicker.datepicker("refresh");
      }
    },
    
    // Clear the end date
    _clearEndDate: function(){
      MultiDate.endDate = null;
      MultiDate.$endDate.val("");
      if(MultiDate.disableOutsideDates) {
        MultiDate.$datepicker.datepicker("option", "maxDate", "");
      }
    },
    
    _clearStartDate: function(){
      MultiDate.startDate = null;
      MultiDate.$startDate.val("");
      if(MultiDate.disableOutsideDates) {
        MultiDate.$datepicker.datepicker("option", "minDate", "");
      }
    },
    
    _convertStringToJSDate: function(date, asMoment) {
      asMoment = asMoment || false;
      if(date) {
        var split = date.split("/");
        var day = split[0];
        var month = split[1] - 1;
        var year = split[2];
        if(asMoment) {
          return moment(new Date(year, month, day));
        } else {
          return new Date(year, month, day);
        }
      } else {
        return null;
      }
    },
    
    _shouldDateBeSelected: function(date){
      var startDate = MultiDate.startDate;
      var endDate = MultiDate.endDate;
      if(!moment.isMoment(date)) {
        date = moment(date);
      }
      if(startDate && endDate && date.isSameOrAfter(startDate) && date.isSameOrBefore(endDate)) {
        return true;
      } else if (startDate && date.isSame(startDate) || endDate && date.isSame(endDate)) {
        return true;
      } else {
        return false;
      }
    },
    
    _disableInputs: function(){
      MultiDate.$startDate[0].disabled = true;
      MultiDate.$endDate[0].disabled = true;
    },
    
    _enableInputs: function(){
      MultiDate.$startDate[0].disabled = false;
      MultiDate.$endDate[0].disabled = false;
    },
    
    _choosePeriodEvent: function(e) {
      var value = e.target.value;

      MultiDate._enableInputs();
      MultiDate.$startDate.focus();

      MultiDate.sendDatesToInputs();
      MultiDate.moveToFirstDay();
      MultiDate.$datepicker.datepicker("refresh");
    },
    
    _clearDatesEvent: function(e){
      e.preventDefault();
      MultiDate._clearStartDate();
      MultiDate._clearEndDate();
      MultiDate.$period.val("custom");
      MultiDate._enableInputs();
      MultiDate.$datepicker.datepicker("refresh");
    },
    
    sendDatesToInputs: function(){
      if(MultiDate.startDate) {
        MultiDate.$startDate.val(MultiDate.startDate.format("YYYY-MM-DD"));
      }
      if(MultiDate.endDate) {
        MultiDate.$endDate.val(MultiDate.endDate.format("YYYY-MM-DD"));
      }
    },
    
    setStartDate: function(value, keepEndDate){
      keepEndDate = keepEndDate || false;
      if(!keepEndDate && MultiDate.clearEndWhenSelectingStart) {
        MultiDate._clearEndDate();
      }
      MultiDate.startDate = moment(MultiDate._convertStringToJSDate(value));
      MultiDate.sendDatesToInputs();
      if(MultiDate.disableOutsideDates) {
        MultiDate.$datepicker.datepicker("option", "minDate", value);
      }
    },
    
    setEndDate: function(value){
      MultiDate.endDate = moment(MultiDate._convertStringToJSDate(value));
      MultiDate.sendDatesToInputs();
      if(MultiDate.disableOutsideDates) {
        MultiDate.$datepicker.datepicker("option", "maxDate", value);
      }
    },
    
    moveToFirstDay: function(){
      if(MultiDate.startDate) {
        MultiDate.$datepicker.datepicker("setDate", MultiDate.startDate.toDate());
      }
    },
    
    getNumberOfCalendars() {
      return 1;
    },
    
    resizeCalendar() {
      var currentNumber = MultiDate.numberOfMonths;
      var newNumber = MultiDate.getNumberOfCalendars();
      if(currentNumber !== newNumber) {
        MultiDate.$datepicker.datepicker('option', "numberOfMonths", newNumber);
        MultiDate.numberOfMonths = newNumber;
        MultiDate.moveToFirstDay();
      }
    },
    
    init: function(){
      var numberOfMonths = MultiDate.getNumberOfCalendars();
      MultiDate.datePickerSettings = {
        beforeShowDay: function(date){
          var className = MultiDate._shouldDateBeSelected(date) ? "change-active-date" : "";
          return [true, className];
        },
        numberOfMonths: 1,
        minDate: 0,
        dateFormat: "dd/mm/yy"
      };
      // Setting elements
      MultiDate.$startDate = $(MultiDate.startDateField);
      MultiDate.$endDate = $(MultiDate.endDateField);
      MultiDate.$period = $(MultiDate.periodField);
      MultiDate.$clear = $(MultiDate.clearButton);
      // Binding Datepicker
      MultiDate.$datepicker = $(MultiDate.datepickerDiv).datepicker(MultiDate.datePickerSettings);
      MultiDate.$datepicker.on("change", MultiDate._changeDate);

    }
  }
  
  MultiDate.init();
  window.MultiDate = MultiDate;
  window.addEventListener("resize", MultiDate.resizeCalendar);
});