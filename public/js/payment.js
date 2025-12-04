// Function to update total and breakdown
function updateTotal() {
    let total = 0;
    let total_interest = 0;
    let total_rebate = 0;
    let total_principal = 0;
    let total_penalty = 0;

    // Collect checked normal payments (Next Payment + Advance)
        $('.payment-option-next input[type="checkbox"]:checked').each(function () {
            total += (parseFloat($(this).attr('data-interest')) || 0) + (parseFloat($(this).attr('data-principal')) || 0)+ (parseFloat($(this).attr('data-penalty')) || 0);
            total_interest += parseFloat($(this).attr('data-interest')) || 0;
            total_principal += parseFloat($(this).attr('data-principal')) || 0;
            total_penalty += parseFloat($(this).attr('data-penalty')) || 0;
        });
        console.log(1,total,total_interest,total_interest,total_penalty,total_principal)

    if($('.payment-option-advance input[type="checkbox"]:checked').length > 0){ 
        $('.payment-option input[type="checkbox"]:checked').each(function () {
            
            if (!$('#payAllCheck').is(':checked')) {
                total += parseFloat($(this).attr('data-principal')) + parseFloat($(this).attr('data-interest'));
            }else{
                total += parseFloat($(this).attr('data-principal'));
            }
            total_rebate += parseFloat($(this).attr('data-interest'));
            total_interest += parseFloat($(this).attr('data-interest'));
            total_principal += parseFloat($(this).attr('data-principal'));
            total_penalty += parseFloat($(this).attr('data-penalty'));
        });
        $('.payment-total').attr('data-partial',0);
    }
    console.log(3,total,total_interest,total_interest,total_penalty,total_principal)

    // Collect checked partial payments
    if ($('.partially-pay[type="checkbox"]:checked').length > 0) {
        $('.payment-total').attr('data-partial',1);
        console.log('partially-pay')
        $('.payment-option-advance input[type="checkbox"]:checked').prop('checked', false);
        resetTotal()

        $('.next_par').each(function () {
            var dis = $(this).find('.partial-months');
            total_interest += parseFloat(dis.find('.partial-interest[type="checkbox"]:checked').attr('data-amount')) || 0;
            total_principal += parseFloat(dis.find('.partial-principal[type="checkbox"]:checked').attr('data-amount')) || 0;
            total_penalty += parseFloat(dis.find('.partial-penalty[type="checkbox"]:checked').attr('data-amount')) || 0;
            total =  total_interest +  total_principal +  total_penalty;
            console.log(4, total_interest, total_interest, total_penalty, total_principal)
        });
    
        console.log(5,total,total_interest,total_interest,total_penalty,total_principal)
    }

    
    $('.payment-total,#totalAmount').text('₱ ' + Number(total).toLocaleString('en-US', { minimumFractionDigits: 2 }));
    $('.payment-interest').text('₱ ' + Number(total_interest).toLocaleString('en-US', { minimumFractionDigits: 2 }));
    $('.payment-principal').text('₱ ' + Number(total_principal).toLocaleString('en-US', { minimumFractionDigits: 2 }));
    $('.payment-penalty').text('₱ ' + Number(total_penalty).toLocaleString('en-US', { minimumFractionDigits: 2 }));
    $('.payment-rebate').text('-₱ ' + Number(total_rebate).toLocaleString('en-US', { minimumFractionDigits: 2 }));

    $('.payment-total,#totalAmount').attr('data-amount',(total).toFixed(2))
    $('.payment-interest').attr('data-amount',(total_interest).toFixed(2))
    $('.payment-principal').attr('data-amount',(total_principal).toFixed(2))
    $('.payment-penalty').attr('data-amount',(total_penalty).toFixed(2))
    $('.payment-rebate').attr('data-amount',(total_rebate).toFixed(2))








}

function resetTotal(){
    $('.payment-total').text('₱ 0.00')
    $('.payment-interest').text('₱ 0.00')
    $('.payment-principal').text('₱ 0.00')
    $('.payment-penalty').text('₱ 0.00')

    $('.payment-total').attr('data-amount','0.00')
    $('.payment-interest').attr('data-amount','0.00')
    $('.payment-principal').attr('data-amount','0.00')
    $('.payment-penalty').attr('data-amount','0.00')
}

$(document).on('change', '.partially-pay', function () {
    const $changedPartial = $(this);
    $('#payAllCheck').prop('checked', false);
    const tenure = $(this).closest('.next_par').find('.due_payment').attr('data-tenure_id');
    // console.log(tenure)
    // Find the index of the .partial-months container this checkbox belongs to
    const $partialMonths = $('.partial-months');
    let currentMonthIndex = -1;

    $partialMonths.each(function (index) {
        if ($(this).find($changedPartial).length > 0) {
            currentMonthIndex = index; // zero-based index of the month
            return false; // break loop
        }
    });

    let monthsWithCheckedPartialPay = [];
    $partialMonths.each(function (index) {
        if ($(this).find('.partially-pay:checked').length > 0) {
            monthsWithCheckedPartialPay.push(index);
        }
    });

    if (monthsWithCheckedPartialPay.length > 0) {
    
        const maxCheckedMonth = Math.max(...monthsWithCheckedPartialPay);
        console.log('test')
        $('.due_payment').each(function (index) {
            if (index < maxCheckedMonth) {
                $(this).prop('checked', true);
            } else {
                $(this).prop('checked', false);
            }
        });
    } else {
        // $('.due_payment').prop('checked', false);
        $('.due_payment').each(function (index) {
            let tenureId = parseInt($(this).attr('data-tenure_id'), 10) || 0;
            if (tenureId > tenure) {
                $(this).prop('checked', false);
            }else{
            }
            
        });

    }

    updateTotal();
});

$(document).on('change', '.due_payment', function () {
    const $all = $('.due_payment');
    const $self = $(this);
    const index = $all.index(this);
    const $allToggle = $('.togglePartials');

    // Reset UI
    $('.partial-months').slideUp(200);
    $allToggle.find('i.bi').removeClass('bi-chevron-up').addClass('bi-chevron-down');
    $allToggle.removeClass('d-none');
    $all.slice(0, index + 1).closest('.next_par').find('.partially-pay').prop('checked', false);

    if ($self.is(':checked')) {
        // Show only self's toggle
        $allToggle.addClass('d-none');
        $all.eq(index).closest('.next_par').find('.togglePartials').removeClass('d-none');
        $all.slice(0, index + 1).prop('checked', true);
    } else {
        // Uncheck future months
        $all.slice(index + 1).prop('checked', false);
        $('.payment-option input[type="checkbox"]').prop('checked', false);

        // Show only previous toggle if it exists
        $allToggle.addClass('d-none');
        if (index > 0) {
            $all.eq(index - 1).closest('.next_par').find('.togglePartials').removeClass('d-none');
        }
    }

    updateTotal();
});

$(document).on('change', '.payment-option-advance input[type="checkbox"]', function () {

    const $all = $('.due_payment');
    const $self = $(this);
    const indexz = $all.index(this);
    const $allToggle = $('.togglePartials');

    //uncheck all partial 
    $('.partially-pay').prop('checked', false);


    // Reset UI
    $('.partial-months').slideUp(200);
    $allToggle.find('i.bi').removeClass('bi-chevron-up').addClass('bi-chevron-down');
    $allToggle.removeClass('d-none');
    $all.slice(0, indexz + 1).closest('.next_par').find('.partially-pay').prop('checked', false);

    const index = $('.payment-option input[type="checkbox"]').index(this);
    $('#payAllCheck').prop('checked', false);
    $('.rebate').addClass('d-none')
    
    if ($(this).is(':checked')) {
        // Auto-check previous months if one is checked
        $('.payment-option input[type="checkbox"]').slice(0, index + 1).prop('checked', true);
        $('.due_payment').prop('checked', true);
    } else {
        // Uncheck future months if unchecked
        $('.payment-option input[type="checkbox"]').slice(index + 1).prop('checked', false);
    }

    updateTotal();
});

// Handle Payment Type dropdown (Monthly or Full)
$(document).on('change', '#payAllCheck', function () {
    const $all = $('.due_payment');
    const $self = $(this);
    const indexz = $all.index(this);
    const $allToggle = $('.togglePartials');

    //uncheck all partial 
    $('.partially-pay').prop('checked', false);


    // Reset UI
    $('.partial-months').slideUp(200);
    $allToggle.find('i.bi').removeClass('bi-chevron-up').addClass('bi-chevron-down');
    $allToggle.removeClass('d-none');
    $all.slice(0, indexz + 1).closest('.next_par').find('.partially-pay').prop('checked', false);

    
     if ($(this).is(':checked')) {
        $('.payment-option input[type="checkbox"]').prop('checked', true);
        $('.due_payment').prop('checked', true);
        $('.rebate').removeClass('d-none')
    } else {
        $('.payment-option input[type="checkbox"]').prop('checked', false);
        $('.due_payment').prop('checked', true);
        $('.rebate').addClass('d-none')
    }

    // Reset partial section if open
    $('.partial-option input[type="checkbox"]').prop('checked', false);
    $('.partial_section').addClass('d-none');
    $('.advance_section').removeClass('d-none');

    updateTotal();
});

// Toggle Advance Payments section
$('#toggleAdvance').on('click', function () {
    $('.extra-payment').addClass('d-none');
    $('#showMoreAdvance').show();
    $('.advance-months').slideToggle();
    $(this).find('i').toggleClass('bi-chevron-down bi-chevron-up');
});

$('#showMoreAdvance').on('click', function() {
    $('.extra-payment').removeClass('d-none');
    $(this).hide(); 
});

// Show Partial Payment Section
$(document).on('click', '.pay-partial-btn', function () {
    $('.partial_section').removeClass('d-none'); // Show Partial
    $('.advance_section').addClass('d-none'); // Hide Advance
    
    $('.pay-partial-btn').addClass('d-none');
    $('.pay-partial-btn_close').removeClass('d-none');
    
    // Uncheck all regular payment options
    $('.due_payment').prop('checked', false);
    $('.payment-option input[type="checkbox"]').prop('checked', false);

    updateTotal();
});

// Close Partial Payment Section
$(document).on('click', '.close-partial', function () {
    $('.partial_section').addClass('d-none');
    $('.advance_section').removeClass('d-none');
    $('.rebate').addClass('d-none')

    // Reset partial selections
    $('.partial-option input[type="checkbox"]').prop('checked', false);

    // Restore original next payment
    $('.due_payment').prop('checked', true);
    $('#payAllCheck').prop('checked', false);

    updateTotal();
});

// Handle Partial Payment checkbox changes
$(document).on('change', '.partial-option input[type="checkbox"]', function () {
    updateTotal();
});

// Initial total update on page load
$(document).ready(function () {
    updateTotal();
});

let scale = 1;

$('#zoomInBtn').on('click', function () {
    scale += 0.1;
    $('#qrImage').css('transform', 'scale(' + scale + ')');
});

$('#zoomOutBtn').on('click', function () {
    scale = Math.max(0.5, scale - 0.1);
    $('#qrImage').css('transform', 'scale(' + scale + ')');
});

$('#qrCodeModal').on('hidden.bs.modal', function () {
    scale = 1;
    $('#qrImage').css('transform', 'scale(1)');
});

function triggerUpload() {
    document.getElementById('screenshot').click();
}

function handleFileUpload(event) {
    const file = event.target.files[0];
    if (file && file.type.startsWith('image/')) {
        previewImage(file);
    }
}

function handleDrop(event) {
    event.preventDefault();
    document.getElementById('dropzone').classList.remove('border-primary');

    const file = event.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        document.getElementById('screenshot').files = event.dataTransfer.files;
        previewImage(file);
    }
}

function previewImage(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('preview-image').src = e.target.result;
        document.getElementById('preview').classList.remove('d-none');
        document.getElementById('placeholder').classList.add('d-none');
    };
    reader.readAsDataURL(file);
}

function removeImage() {
    const input = document.getElementById('screenshot');
    input.value = ''; // Clear input
    document.getElementById('preview').classList.add('d-none');
    document.getElementById('preview-image').src = '';
    document.getElementById('placeholder').classList.remove('d-none');
}

$(document).on('click', '.loan_payment_confirm', function () {
    console.log('click');


    if ($('.payment-total').attr('data-amount') == 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Amount Cannot be ₱0.00',
          });
          return false;
    }


    // Toggle payment form visibility
    $('.payment_form_1').addClass('d-none');
    $('.payment_form_2').removeClass('d-none');

    // Update total amount summary
    const totalText = $('.payment-total').text();
    $('.total_amount_summary').text(totalText);

    // Determine payment type
    const $payAllCheck = $('#payAllCheck[type="checkbox"]');
    const isPartial = $('.payment-total').attr('data-partial') == 1;
    const $advanceChecks = $('.payment-option-advance input[type="checkbox"]');
    const advanceChecked = $advanceChecks.filter(':checked').length;
    const allAdvanceChecked = advanceChecked === $advanceChecks.length;

    console.log(isPartial + 'tetest')
    let categoryText = 'Normal Payment';
    let categoryType = 4;

    if ($payAllCheck.is(':checked')) {
        categoryText = 'Full Payment';
        categoryType = 1;
    } else if (isPartial) {
        categoryText = 'Partial Payment';
        categoryType = 2;
    } else if (advanceChecked > 0 && !allAdvanceChecked) {
        categoryText = 'Advanced Payment';
        categoryType = 3;
    }

    $('#confirmCategory').text(categoryText).attr('data-type', categoryType);
});




$(document).on('click', '#submit_payment', function () {

    let referenceCode = $('#referenceCode').val().trim();
    if (referenceCode == null) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Reference Code',
            text: 'Please enter the reference code.',
            confirmButtonText: 'Got it!',
            background: '#fefefe',
            customClass: {
                popup: 'swal2-border-radius',
                confirmButton: 'swal2-confirm-button'
            }
        });
    }
    let total = parseFloat($('.total_amount_summary').text().replace(/[^\d.]/g, ''));
   
   //gather data based on type
    var reference_code = $('#referenceCode').val().trim();
    var remarks = $('#remarks').val().trim();
    var id = $(this).attr('data-id');
    var fullpayment = 0;

    var paymentDue = [];
    var paymentPar = [];
    var paymentData = [];

    if ($('#payAllCheck').is(':checked')) {
        fullpayment = 1;
    }

    // GET DUE FULL PAYMENT
    $($('.payment-option-next input[type="checkbox"]:checked')).each(function () {
        var id = $(this).attr('data-tenure_id');
        paymentDue.push({
            id : id,
        });
    });

    // GET DUE PARTIAL
    $('.next_par').each(function () {
        const $nextPar = $(this);
        const checked = $nextPar.find('.partially-pay:checked');
    
        // Only process if there’s at least one checked partial
        if (checked.length) {
            const tenureId = $nextPar.find('.due_payment').data('tenure_id');
            const data = { id: tenureId };
    
            // Add interest if checked
            const $interest = $nextPar.find('.partial-interest:checked');
            if ($interest.length) {
                data.interest = parseFloat($interest.data('amount')) || 0;
            }
    
            // Add principal if checked
            const $principal = $nextPar.find('.partial-principal:checked');
            if ($principal.length) {
                data.principal = parseFloat($principal.data('amount')) || 0;
            }
    
            paymentPar.push(data);
        }
    });
    console.log(paymentPar);
    
    $($('.payment-option-advance input[type="checkbox"]:checked')).each(function () {
        var id = $(this).attr('data-tenure_id');
        paymentData.push({
            id : id,
        });
    });

    let attachment = $("#screenshot")[0].files[0];

    let formData = new FormData();
    formData.append('id', id);
    formData.append('total', total);
    formData.append('reference_code', reference_code);
    formData.append('remarks', remarks);
    formData.append('fullpayment', fullpayment);
    formData.append('attachment', attachment);

    formData.append('paymentDue', JSON.stringify(paymentDue));
    formData.append('paymentPar', JSON.stringify(paymentPar));
    formData.append('paymentData', JSON.stringify(paymentData));

    $.ajax({
        url: '/payment/submit',
        method: 'POST',
        data: formData,
         processData: false,
        contentType: false,
        cache: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            window.location.href = response.redirect;
        },
    });

});

$(document).on('click', '#payment_return', function () {
    $('.payment_form_2').addClass('d-none');
    $('.payment_form_1').removeClass('d-none');

    // confirmTotal
});

$(document).on('click', '.pay-partial-btn_close', function () {
    $('.pay-partial-btn_close').addClass('d-none');
    $('.pay-partial-btn').removeClass('d-none');

});

$(document).ready(function () {
  $('.togglePartials').on('click', function (e) {
    e.preventDefault();

    const $btn = $(this);
    const $partialMonths = $btn.closest('label.payment-option-next').next('.partial-months');
    const $icon = $btn.find('i.bi');

    $partialMonths.slideToggle(200, function () {
      // Update icon based on visibility after animation ends
      if ($partialMonths.is(':visible')) {
        $icon.removeClass('bi-chevron-down').addClass('bi-chevron-up');
      } else {
        $icon.removeClass('bi-chevron-up').addClass('bi-chevron-down');
      }
    });
  });
});

$(document).on('change', '.partial-months .partially-pay', function () {
    const $container = $(this).closest('.partial-months');
    const total = $container.find('.partially-pay').length;
    const checked = $container.find('.partially-pay:checked').length;

    $('.advance-months').slideUp();
    $('#toggleAdvance').find('i').toggleClass('bi-chevron-up bi-chevron-down');

    if (total === checked) {
        $(this).closest('.next_par').find('.due_payment').prop('checked', true);
        $(this).closest('.next_par').find('.partially-pay').prop('checked', false);
        console.log('All checkboxes in this .partial-months are checked!');
        $('.payment-total').attr('data-partial',0);

    }
});
