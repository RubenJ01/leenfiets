/// @file inputFilter.js
///
/// @brief inputFilter script.
///
/// Hangt een event aan de gegeven classes die ervoor zorgt dat alleen de gewenste input kan worden ingevult. Zie de voorbeelden onderaa
///


// https://stackoverflow.com/questions/469357/html-text-input-allow-only-numeric-input#comment100682297_28838789
/// @brief Deze functie hangt een evenement aan alle classes die ervoor zorgt dat de input binnen een bepaalde criteria valt
/// @param textBox Een array met alle classes van die naam
/// @param inputFilter Een functie die check of de gegeven waarde aan de criteria voldoet
function setInputFilter(textBox, inputFilter) {
  // https://stackoverflow.com/questions/469357/html-text-input-allow-only-numeric-input#comment100682297_28838789
  ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
    for(var i=0;i<textBox.length;i++) {
      textBox[i].addEventListener(event, function() {
        if (inputFilter(this.value)) {
          this.oldValue = this.value;
          this.oldSelectionStart = this.selectionStart;
          this.oldSelectionEnd = this.selectionEnd;
        } else if (this.hasOwnProperty("oldValue")) {
          this.value = this.oldValue;
          this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
        }
      });
    }
  });
}

///. @brief Deze functie helpt om de classes met een inputFilter leeg te maken mocht je dat graag willen
/// Voorbeeld: <button onclick= "ResetTextBoxWithInputFilter()"> Clear </button>
function ResetTextBoxWithInputFilter() {
  var allClasses = ["uintTextBox", "intLimitTextBox", "intTextBox", "floatTextBox", "currencyTextBox", "basicLatinTextBox", "extendedLatinTextBox", "hexTextBox"];
  for (var i=0;i<allClasses.length;i++) {
    textBox = document.getElementsByClassName(allClasses[i]);
    for (var j=0;j<textBox.length;j++) {
      textBox[j].value = "";
    }
  }
}

// Installeert input filters voor classes.

// Integer (positive only):
setInputFilter(document.getElementsByClassName("uintTextBox"), function(value) {
  return /^\d*$/.test(value); });

// Integer (positive and <= 500):
setInputFilter(document.getElementsByClassName("intLimitTextBox"), function(value) {
  return /^\d*$/.test(value) && (value === "" || parseInt(value) <= 500); });

// Integer (both positive and negative):
setInputFilter(document.getElementsByClassName("intTextBox"), function(value) {
  return /^-?\d*$/.test(value); });

// Floating point (use . or , as decimal separator):
setInputFilter(document.getElementsByClassName("floatTextBox"), function(value) {
  return /^-?\d*[.,]?\d*$/.test(value); });

// Currency (at most two decimal places):
setInputFilter(document.getElementsByClassName("currencyTextBox"), function(value) {
  return /^-?\d*[.,]?\d{0,2}$/.test(value); });

// A-Z only:
setInputFilter(document.getElementsByClassName("basicLatinTextBox"), function(value) {
  return /^[a-z]*$/i.test(value); });

// Latin letters only (most European languages):
setInputFilter(document.getElementsByClassName("extendedLatinTextBox"), function(value) {
  return /^[a-z\u00c0-\u024f]*$/i.test(value); });

// Hexadecimal:
setInputFilter(document.getElementsByClassName("hexTextBox"), function(value) {
  return /^[0-9a-f]*$/i.test(value); });
