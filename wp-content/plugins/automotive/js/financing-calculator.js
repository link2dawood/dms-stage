window.automotiveCalculate = function(calculator) {
  if(calculator.length){
    var cost        = calculator.find('.cost').val();
    var downPayment = calculator.find('.down_payment').val();
    var interest    = calculator.find('.interest').val();
    var loanYears   = calculator.find('.loan_years').val();
    var frequency   = calculator.find('.frequency').val();

    var frequencyRate = '';

    if (!cost || !downPayment || !interest || !loanYears || isNaN(cost) || isNaN(downPayment) || isNaN(interest) || isNaN(loanYears)) {
      if (!cost || isNaN(cost)) {
        calculator.find('.cost').addClass('error');
      } else {
        calculator.find('.cost').removeClass('error');
      }

      if (!downPayment || isNaN(downPayment)) {
        calculator.find('.down_payment').addClass('error');
      } else {
        calculator.find('.down_payment').removeClass('error');
      }

      if (!interest || isNaN(interest)) {
        calculator.find('.interest').addClass('error');
      } else {
        calculator.find('.interest').removeClass('error');
      }

      if (!loanYears || isNaN(loanYears)) {
        calculator.find('.loan_years').addClass('error');
      } else {
        calculator.find('.loan_years').removeClass('error');
      }

      return;
    }

    calculator.find('input').removeClass('error');

    switch (frequency) {
      case '0':
        frequencyRate = 26;
        break;
      case '1':
        frequencyRate = 52;
        break;
      case '2':
        frequencyRate = 12;
        break;
    }

    var payment = 0;
    var payments = 0;
    var difference = 0;

    // 0% interest rate
    if (interest === 0 || interest === '0') {
      difference = cost - downPayment;
      payments = loanYears * frequencyRate;

      payment = Math.floor((difference) / payments);
    } else {
      var interestRate = (interest) / 100;
      var rate = interestRate / frequencyRate;
      payments = loanYears * frequencyRate;
      difference = cost - downPayment;

      payment = Math.floor((difference * rate) / (1 - Math.pow((1 + rate), (-1 * payments))) * 100) / 100;
    }

    if (typeof listing_ajax.currency_separator !== 'undefined' && typeof payment !== 'undefined') {
      payment = parseInt(payment).toLocaleString();
    }

    var currencySymbol = (typeof calculator.data('currency-symbol') !== 'undefined' ? calculator.data('currency-symbol') : false);

    // backwards compatibility
    if (currencySymbol === false) {
      currencySymbol = (typeof listing_ajax.currency_symbol !== 'undefined' ? listing_ajax.currency_symbol : '$');
    }

    calculator.find('.payments').text(payments);
    if(listing_ajax.currency_placement == 0){
      calculator.find('.payment_amount').text(payment + currencySymbol);
    } else {
      calculator.find('.payment_amount').text(currencySymbol + payment);
    }
  }
}