// Function to update total and breakdown
function updateTotal() {
    console.log('update_total')
    let total = 0;
    let total_interest = 0;
    let total_principal = 0;
    let total_penalty = 0;
    let breakdownHTML = '';

    // Collect checked normal payments (Next Payment + Advance)
    $('.payment-option-advance input[type="checkbox"]:checked').each(function () {
        const amount = parseFloat($(this).attr('data-amount'));
        const principal = parseFloat($(this).attr('data-principal'));
        const interest = parseFloat($(this).attr('data-interest'));

        total += amount;
        total_interest += interest;
        total_principal += principal;
    });

    if($('.payment-option-advance input[type="checkbox"]:checked').length > 0){ 
        total += parseFloat($('.due_payment').attr('data-amount'));
        total_interest += parseFloat($('.partial-interest').attr('data-amount'));
        total_principal += parseFloat($('.partial-principal').attr('data-amount'));
        total_penalty += parseFloat($('.partial-penalty').attr('data-amount'));

        $('.payment-total').text('₱ ' + (total).toFixed(2))
        $('.payment-interest').text('₱ ' + (total_interest).toFixed(2))
        $('.payment-principal').text('₱ ' + (total_principal).toFixed(2))
        $('.payment-penalty').text('₱ ' + (total_penalty).toFixed(2))
    }
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
        $('.payment-total').attr('data-partial',1);
        console.log('end');
    }


}

function resetTotal(){
    $('.payment-total').text('₱ 0.00')
    $('.payment-interest').text('₱ 0.00')
    $('.payment-principal').text('₱ 0.00')
    $('.payment-penalty').text('₱ 0.00')
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
    } else {
        $('.payment-option input[type="checkbox"]').prop('checked', false);
        $('.due_payment').prop('checked', true);
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


$(document).on('click', '.loan_payment_confirm', function () {
    console.log('click')

    var is_partial = $('.payment-total').attr('data-partial');
    var payment_total = $('.payment-total').text('₱ 0.00')
    var payment_interest = $('.payment-interest').text('₱ 0.00')
    var payment_principal = $('.payment-principal').text('₱ 0.00')
    var payment_penalty = $('.payment-penalty').text('₱ 0.00')

    //IF ADVANCE PAYMENT
    var paymentData = [];
    
    $('.advance-payment-month:checked').each(function () {
        var month = $(this).data('month');
        var amount = $(this).data('amount');

        paymentData.push({
            month: month,
            amount: amount
        });
    });

    $.ajax({
        url: '/borrower/save-precheck',
        method: 'POST',
        data: {

        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // add CSRF if needed
        },
        beforeSend: function () {
            proceedToEligibility();
        },
        success: function (r) {
            loan_application_id = r.loan_application_id;
            gb_refferal_type = r.referral_type;

            console.log(gb_refferal_type, loan_application_id);
        },
        error: function () {
            console.error('Failed to save precheck data.');
            proceedToEligibility(); // still continue even if saving failed
        }
    });
});
