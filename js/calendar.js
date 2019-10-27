var months = ["januari", "februari", "maart", "april", "mei", "juni", "juli", "augustus", "september", "oktober", "november", "december"];

var today = new Date();
const todayYear = today.getFullYear();
const todayMonth = today.getMonth();
const todayDate = today.getDate();

var currentYear = todayYear;
var currentMonth = todayMonth;
var currentDate = todayDate;

var selectedDate1 = null;
var selectedDate2 = null;

var idReservedDates = document.getElementById("reservedDates");
var reservedDates = [];
GetAllReservedDates();

var idCollectionDate = document.getElementById("collectionDate");
var idReturnDate = document.getElementById("returnDate");

var idMonthYear = document.getElementById("month+year");
var idDays = document.getElementById("days");
DisplayCurrenMonthYear();

function DisplayCurrenMonthYear() {
  idMonthYear.innerHTML = (months[currentMonth] + "<br><span style='font-size:18px'>" + currentYear + "</span>");
  CreateDays();
}

function CreateDays() {
  idDays.innerHTML = "";
  const dayOfWeek = GetDayOfWeek(currentYear, currentMonth, 1);
  const daysInMonth = GetDaysInMonth(currentYear, currentMonth);
  var documentFragment = document.createDocumentFragment();
  // Add some empty spaces if the first day of the month is not sunday
  for (var i=0;i<dayOfWeek;i++) {
    var li = document.createElement("LI");
    documentFragment.appendChild(li);
  }
  var reserved = false;
  // Add all of the days in to the calendar
  for (var i=1;i<=daysInMonth;i++) {
    var span = document.createElement("SPAN");
    span.id = ("day"+i);
    var li = document.createElement("LI");
    li.innerHTML = i;
    // If a date is in the past you can't select them and they will have a different background color
    if (IsDayInThePast(currentYear, currentMonth, i)) {
      span.className = "notAvailable";
      span.appendChild(li);
      documentFragment.appendChild(span);
    }
    else {
      var d = new Date(currentYear, currentMonth, i);
      if (IsDateReserved(d)) {
        span.className = "notAvailable";
        span.appendChild(li);
        documentFragment.appendChild(span);
      }
      else {
        span.className = "selected";
        if (selectedDate1 == null || d < selectedDate1) {
          span.className = "available";
          span.setAttribute("onclick", "SelectDate1("+(i)+")");
        }
        else if (d.getTime() == selectedDate1.getTime()) {
          span.className = "selected";
          span.setAttribute("onclick", "SelectDate1("+(i)+")");
        }
        else if (reserved == true || IsDateReservedBetween(selectedDate1, d)) {
          reserved = true;
          span.className = "available";
          span.setAttribute("onclick", "SelectDate1("+(i)+")");
        }
        else if (selectedDate2 == null) {
          span.className = "available";
          span.setAttribute("onclick", "SelectDate2("+(i)+")");
        }
        else if (d > selectedDate1 && d < selectedDate2) {
          span.className = "highLighted";
          span.setAttribute("onclick", "SelectDate1("+(i)+")");
        }
        else if (d.getTime() == selectedDate2.getTime()) {
          span.className = "selected";
          span.setAttribute("onclick", "SelectDate1("+(i)+")");
        }
        else {
          span.className = "available";
          span.setAttribute("onclick", "SelectDate1("+(i)+")");
        }
        span.setAttribute("onmouseover", "MouseOverDay("+(i)+")");
        span.setAttribute("onmouseout", "MouseOutDay("+(i)+")");
        span.appendChild(li);
        documentFragment.appendChild(span);
      }
    }
  }
  idDays.appendChild(documentFragment);
}

function SelectDate1(date) {
  selectedDate1 = new Date(currentYear, currentMonth, date);
  selectedDate2 = null;
  UpdateDates();
  const dayOfWeek = GetDayOfWeek(currentYear, currentMonth, 1);
  const childIndexSelectedDate = (date-1+dayOfWeek);
  idDays.childNodes[childIndexSelectedDate].className = "selected";
  idDays.childNodes[childIndexSelectedDate].setAttribute("onclick", "SelectDate1("+date+")");
  var sd1 = false;
  for (var i = dayOfWeek; i < idDays.childNodes.length; i++) {
    var d = new Date(currentYear, currentMonth, (i+1-dayOfWeek));
    if (idDays.childNodes[i].className == "notAvailable") {
      if (d.getTime() > selectedDate1.getTime()) {
        sd1 = true;
      }
    }
    else {
      if (d < selectedDate1) {
        idDays.childNodes[i].className = "available";
        idDays.childNodes[i].setAttribute("onclick", "SelectDate1("+(i+1-dayOfWeek)+")");
      }
      else if (d.getTime() != selectedDate1.getTime() && sd1 == true) {
        idDays.childNodes[i].className = "available";
        idDays.childNodes[i].setAttribute("onclick", "SelectDate1("+(i+1-dayOfWeek)+")");
      }
      else if (d > selectedDate1) {
        idDays.childNodes[i].className = "available";
        idDays.childNodes[i].setAttribute("onclick", "SelectDate2("+(i+1-dayOfWeek)+")");
      }
    }
  }
  MouseOutDay(date);
}

function SelectDate2(date) {
  selectedDate2 = new Date(currentYear, currentMonth, date);
  UpdateDates();
  const dayOfWeek = GetDayOfWeek(currentYear, currentMonth, 1);

  for (var i = dayOfWeek; i < idDays.childNodes.length; i++) {
    if (idDays.childNodes[i].className != "notAvailable") {
      var d = new Date(currentYear, currentMonth, (i+1-dayOfWeek));
      if (d > selectedDate1) {
        if (d < selectedDate2) {
          idDays.childNodes[i].className = "highLighted";
        }
        else {
          idDays.childNodes[i].className = "available";
        }
        idDays.childNodes[i].setAttribute("onclick", "SelectDate1("+(i+1-dayOfWeek)+")");
      }
    }
  }

  const childIndexSelectedDate = (date-1+dayOfWeek);
  idDays.childNodes[childIndexSelectedDate].className = "selected";
}

function UpdateDates() {
  if (selectedDate1 != null) {
    var d = (selectedDate1.getFullYear() + "-" + (selectedDate1.getMonth()+1) + "-" + selectedDate1.getDate());
    idCollectionDate.setAttribute("value", d);
    if (selectedDate2 != null) {
      d = (selectedDate2.getFullYear() + "-" + (selectedDate2.getMonth()+1) + "-" + selectedDate2.getDate());
      idReturnDate.setAttribute("value", d);
    }
    else {
      idReturnDate.setAttribute("value", d);
    }
  }
}

function MouseOverDay(date) {
  const dayOfWeek = GetDayOfWeek(currentYear, currentMonth, 1);
  const childIndexSelectedDate = (date-1+dayOfWeek);
  if (idDays.childNodes[childIndexSelectedDate].className != "selected") {
    idDays.childNodes[childIndexSelectedDate].className = "highLighted";
  }
  if (selectedDate1 != null && selectedDate2 == null) {
    var selectedDate = new Date(currentYear, currentMonth, date);
    for (var i = dayOfWeek; i < idDays.childNodes.length; i++) {
      var d = new Date(currentYear, currentMonth, (i+1-dayOfWeek));
      if (d > selectedDate1 && d < selectedDate) {
        if (idDays.childNodes[i].className == "notAvailable" || IsDateReservedBetween(selectedDate1, d)) { break; }
        idDays.childNodes[i].className = "highLighted";
      }
    }
  }
}

function MouseOutDay(date) {
  const dayOfWeek = GetDayOfWeek(currentYear, currentMonth, 1);
  const childIndexSelectedDate = (date-1+dayOfWeek);
  /*if (idDays.childNodes[childIndexSelectedDate].className == "highLighted") {
    idDays.childNodes[childIndexSelectedDate].className = "available";
  }*/
  var selectedDate = new Date(currentYear, currentMonth, date);
  for (var i = dayOfWeek; i < idDays.childNodes.length; i++) {
    if (idDays.childNodes[i].className != "selected" && idDays.childNodes[i].className != "notAvailable") {
      var d = new Date(currentYear, currentMonth, (i+1-dayOfWeek));
      if (selectedDate1 == null || d < selectedDate1) {
        idDays.childNodes[i].className = "available";
      }
      else if (selectedDate2 == null || d > selectedDate2) {
        idDays.childNodes[i].className = "available";
      }
    }
  }
}

function IsDayInThePast(year, month, date) {
  if (date < todayDate && month == todayMonth && year == todayYear) {
    return true;
  }
  return false;
}

function previousMonth() {
  // You can't change the calendar to go back in to the past
  if (currentYear == todayYear && currentMonth == todayMonth) {
    return;
  }

  currentMonth--;
  // If the currentMonth is less then zero that means we are trying to go from januari to december
  if (currentMonth < 0) {
    currentMonth = 11;
    currentYear--;
  }
  DisplayCurrenMonthYear();
}

function nextMonth() {
  currentMonth++;
  // If the currentMonth is greater then zero that means we are trying to go from december to januari
  if (currentMonth > 11) {
    currentMonth = 0;
    currentYear++;
  }
  DisplayCurrenMonthYear();
}

// Returns the amount of day there are in that specific month
function GetDaysInMonth(year, month) {
  // Day 0 is the last day in the previous month
  return new Date(year, month+1, 0).getDate();
}

// Return day of the week. 0 is for sunday, 1 for monday and so on
function GetDayOfWeek(year, month, day) {
  return new Date(year, month, day).getDay();
}

function GetAllReservedDates() {
  // For some reason is the first and last childNode empty so we need to skip those
  for (var i = 1; i < idReservedDates.childNodes.length-1; i++) {
    var row = idReservedDates.childNodes[i];
    var cMoment = row.childNodes[0].innerHTML;
    cMoment = cMoment.split("-");
    cMoment[2] = cMoment[2].split(" ")[0];
    var cDate = new Date(cMoment[0], cMoment[1]-1, cMoment[2]);
    var rMoment = row.childNodes[1].innerHTML;
    rMoment = rMoment.split("-");
    rMoment[2] = rMoment[2].split(" ")[0];
    var rDate = new Date(rMoment[0], rMoment[1]-1, rMoment[2]);
    // Add the reservedDate to array
    reservedDates.push([cDate, rDate]);
  }
}

function IsDateReserved(date) {
  for (var i = 0; i < reservedDates.length; i++) {
    // Break if the reservedDate is greater then the givenn date because then it will never be reserved because the reservedDates are ordered
    if (reservedDates[i][0].getTime() > date.getTime()) {
      break;
    }
    if (date.getTime() >= reservedDates[i][0].getTime() && date.getTime() <= reservedDates[i][1].getTime()) {
      return true;
    }
  }
  return false;
}

function IsDateReservedBetween(date1, date2) {
  for (var i = 0; i < reservedDates.length; i++) {
    // Break if the reservedDate is greater then the givenn date because then it will never be reserved because the reservedDates are ordered
    if (reservedDates[i][0].getTime() > date2.getTime()) {
      break;
    }
    if ((reservedDates[i][0].getTime() > date1.getTime() && reservedDates[i][0].getTime() < date2.getTime()) || (reservedDates[i][1].getTime() > date1.getTime() && reservedDates[i][1].getTime() < date2.getTime())) {
      return true;
    }
  }
  return false;
}
