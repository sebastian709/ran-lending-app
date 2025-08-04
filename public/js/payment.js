// Function to update total and breakdown
function updateTotal() {
    console.log('update_total')
    let total = 0;
    let total_interest = 0;
    let total_rebate = 0;
    let total_principal = 0;
    let total_penalty = 0;

    // Collect checked normal payments (Next Payment + Advance)
        const amount = parseFloat($('#totalAmount').find('span').text());
        const principal = parseFloat($('.partial-principal').attr('data-amount'));
        const interest = parseFloat($('.partial-interest').attr('data-amount'));
        const penalty = parseFloat($('.partial-penalty').attr('data-amount'));

        total = amount;
        total_interest = interest;
        total_penalty = penalty;
        total_principal = principal;
        
        console.log(1,total,total_interest,total_interest,total_penalty,total_principal)

    if($('.payment-option-advance input[type="checkbox"]:checked').length > 0){ 
        $('.payment-option input[type="checkbox"]:checked').each(function () {
            
            if (!$('#payAllCheck').is(':checked')) {
                total += parseFloat($(this).attr('data-principal')) +  parseFloat($(this).attr('data-interest'));
                total_interest += parseFloat($(this).attr('data-interest'));
            }else{
                total += parseFloat($(this).attr('data-principal') + 0);
            }
            total_rebate += parseFloat($(this).attr('data-interest'));
            
            total_principal += parseFloat($(this).attr('data-principal'));
            total_penalty += parseFloat($(this).attr('data-penalty'));
            console.log(2,total,total_interest,total_interest,total_penalty,total_principal)
        });

    }
    console.log(3,total,total_interest,total_interest,total_penalty,total_principal)
    
    $('.payment-total').text('₱ ' + (total).toFixed(2))
    $('.payment-interest').text('₱ ' + (total_interest).toFixed(2))
    $('.payment-principal').text('₱ ' + (total_principal).toFixed(2))
    $('.payment-penalty').text('₱ ' + (total_penalty).toFixed(2))
    $('.payment-rebate').text('₱ ' + (total_rebate).toFixed(2))

    $('.payment-total').attr('data-amount',(total).toFixed(2))
    $('.payment-interest').attr('data-amount',(total_interest).toFixed(2))
    $('.payment-principal').attr('data-amount',(total_principal).toFixed(2))
    $('.payment-penalty').attr('data-amount',(total_penalty).toFixed(2))
    $('.payment-rebate').attr('data-amount',(total_rebate).toFixed(2))
    

    $('.payment-total').attr('data-partial',0);
    
    // Collect checked partial payments
    if ($('.partial-option input[type="checkbox"]:checked').length > 0) {
        $('.payment-option-advance input[type="checkbox"]:checked').prop('checked', false);
        resetTotal()

        var partial_total = 0;

        var partial_total_interest = $('.partial-interest').is(':checked') 
            ? parseFloat($('.partial-interest').attr('data-amount')) || 0
            : 0;

        var partial_total_principal = $('.partial-principal').is(':checked') 
            ? parseFloat($('.partial-principal').attr('data-amount')) || 0
            : 0;

        var partial_total_penalty = $('.partial-penalty').is(':checked') 
            ? parseFloat($('.partial-penalty').attr('data-amount')) || 0
            : 0;

        partial_total = partial_total_interest + partial_total_principal + partial_total_penalty;
        
        $('.payment-interest').text('₱ ' + (partial_total_interest).toFixed(2));
        $('.payment-principal').text('₱ ' + (partial_total_principal).toFixed(2));
        $('.payment-penalty').text('₱ ' + (partial_total_penalty).toFixed(2));
        $('.payment-total').text('₱ ' + (partial_total).toFixed(2));

        $('.payment-interest').attr('data-amount',(partial_total_interest).toFixed(2));
        $('.payment-principal').attr('data-amount',(partial_total_principal).toFixed(2));
        $('.payment-penalty').attr('data-amount',(partial_total_penalty).toFixed(2));
        $('.payment-total').attr('data-amount',(partial_total).toFixed(2));

        $('.payment-total').attr('data-partial',1);
    }
    console.log(4,total,total_interest,total_interest,total_penalty,total_principal)

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

// Handle advance/next payment checkbox changes
$(document).on('change', '.payment-option input[type="checkbox"]', function () {
    const index = $('.payment-option input[type="checkbox"]').index(this);

    if ($(this).is(':checked')) {
        // Auto-check previous months if one is checked
        $('.payment-option input[type="checkbox"]').slice(0, index + 1).prop('checked', true);
    } else {
        // Uncheck future months if unchecked
        $('.payment-option input[type="checkbox"]').slice(index + 1).prop('checked', false);
    }

    updateTotal();
});

// Handle Payment Type dropdown (Monthly or Full)
$(document).on('change', '#payAllCheck', function () {
     if ($(this).is(':checked')) {
        $('.payment-option input[type="checkbox"]').prop('checked', true);
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

    // Reset partial selections
    $('.partial-option input[type="checkbox"]').prop('checked', false);

    // Restore original next payment
    $('.due_payment').prop('checked', true);

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


// $(document).on('click', '.loan_payment_confirm', function () {
    //     console.log('click')

    //     $('.payment_form_1').addClass('d-none');
    //     $('.payment_form_2').removeClass('d-none');
        
    //     $('.total_amount_summary').text($('.payment-total').text());

    //     //Payment Type
    //     if($('#payAllCheck[type="checkbox"]:checked').length > 0){
    //         $('#confirmCategory').text('Full Payment');
    //         $('#confirmCategory').attr('data-type',1);
    //     }else if($('.payment-total').attr('data-partial') == 1){
    //         $('#confirmCategory').text('Partial Payment');
    //         $('#confirmCategory').attr('data-type',2);
    //     }else if($('.payment-option-advance input[type="checkbox"]:checked').length > 0 && $('.payment-option-advance input[type="checkbox"]:checked').length != $('.payment-option-advance input[type="checkbox"]').length){
    //         $('#confirmCategory').text('Advanced Payment');
    //         $('#confirmCategory').attr('data-type',3);
    //     }else{
    //         $('#confirmCategory').text('Normal Payment');
    //         $('#confirmCategory').attr('data-type',4);
    //     }
// });
$(document).on('click', '.loan_payment_confirm', function () {
    console.log('click');

    // Toggle payment form visibility
    $('.payment_form_1').addClass('d-none');
    $('.payment_form_2').removeClass('d-none');

    // Update total amount summary
    const totalText = $('.payment-total').text();
    $('.total_amount_summary').text(totalText);

    // Determine payment type
    const $payAllCheck = $('#payAllCheck[type="checkbox"]');
    const isPartial = $('.payment-total').data('partial') == 1;
    const $advanceChecks = $('.payment-option-advance input[type="checkbox"]');
    const advanceChecked = $advanceChecks.filter(':checked').length;
    const allAdvanceChecked = advanceChecked === $advanceChecks.length;

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
    // var is_partial = $('.payment-total').attr('data-partial');
    // var payment_total = $('.payment-total').text()
    // var payment_interest = $('.payment-interest').text()
    // var payment_principal = $('.payment-principal').text()
    // var payment_penalty = $('.payment-penalty').text()


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
    var id = $(this).attr('data-id');
    var type = $('#confirmCategory').attr('data-type');
    var next_id = $('.due_payment').attr('data-id');
    var reference_code = $('#referenceCode').val().trim();
    var remarks = $('#remarks').val().trim();
    //Partial Data
    var partial_principal = parseFloat($('.partial-principal:checked').attr('data-amount'));
    var partial_interest = parseFloat($('.partial-interest:checked').attr('data-amount'));
    var partial_penalty = parseFloat($('.partial-penalty:checked').attr('data-amount')); 
    
    //IF ADVANCE PAYMENT
    var paymentData = [];
    paymentData.push({
        id : next_id,
    });
    $($('.payment-option-advance input[type="checkbox"]:checked')).each(function () {
        var id = $(this).attr('data-tenure_id');
        paymentData.push({
            id : id,
        });
    });
    // console.log(paymentData);

    $.ajax({
        url: '/payment/submit',
        method: 'POST',
        data: {
            id:id,
            next_id : next_id,
            total : total,
            paymentData : paymentData,
            partial_principal : partial_principal,
            partial_interest : partial_interest,
            partial_penalty : partial_penalty,
            type : type,

            reference_code : reference_code,
            remarks : remarks,
            
        },
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

